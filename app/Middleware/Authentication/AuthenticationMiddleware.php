<?php
declare(strict_types=1);

namespace App\Middleware\Authentication;

use App\Contract\ResponseContract;
use App\Dao\Admin\AdministratorDao;
use App\Security\AdminSecurity;
use Carbon\Carbon;
use Hyperf\Context\Context;
use Hyperf\Context\RequestContext;
use Hyperf\Contract\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @AuthenticationMiddleware
 * @\App\Middleware\Http\AuthenticationMiddleware
 */
final readonly class AuthenticationMiddleware implements MiddlewareInterface
{
	public function __construct(
		private SessionInterface $session,
		private ResponseContract $response,
		private AdminSecurity    $security,
		private AdministratorDao $daoAdminAdministrator,
	)
	{
	}

	/**
	 * @param ServerRequestInterface  $request
	 * @param RequestHandlerInterface $handler
	 *
	 * @return ResponseInterface
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$userid = $this->session->get('userid');

		if (is_null($userid)) {
			goto loginAgain;
		}

		$token = $request->getHeaderLine('Authorization');

		if (!$this->security->verify($token)) {
			goto loginAgain;
		}

		$payload = $this->security->payload($token);

		if ($payload->data !== $userid) {
			return $this->response->auth('异常操作，请重新登录');
		}

		$userinfo = $this->daoAdminAdministrator->getUserinfoById($userid);

		if (is_null($userinfo)) {
			goto loginAgain;
		}

		if (!$userinfo->status) {
			return $this->response->auth('账户已被封禁');
		}

		//  TODO: This context writing here follows the system settings.
		Context::set('userinfo', $userinfo);

		$response = $handler->handle($request);

		//  TODO: Reissue the voucher.
		$newToken = $this->barter($payload);
		if (!is_null($newToken)) {
			$response = $response->withHeader('Authorization', $newToken);
		}

		return $response;

		loginAgain:
		return $this->response->auth('未登录或者登录已过期，请重新登录');
	}

	/**
	 * 换取令牌
	 *
	 * @param object $payload
	 *
	 * @return string|null
	 *
	 * @used-by
	 */
	private function barter(object $payload): ?string
	{
		if ($payload->exp > Carbon::now()->subDays(7)->getTimestamp()) {
			return null;
		}

		return $this->security->create($payload->data);
	}
}