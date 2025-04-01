<?php
declare(strict_types=1);

namespace App\Middleware\Authentication;

use App\Contract\ResponseContract;
use App\Service\Admin\AdministratorService;
use Hyperf\Context\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @MiddlewareAdminAuthentication
 * @\App\Middleware\Http\MiddlewareAdminAuthentication
 */
final readonly class MiddlewareAdminAuthentication implements MiddlewareInterface
{
	public function __construct(private ResponseContract $response, private AdministratorService $administratorService)
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
		$token = $request->getHeaderLine('Authorization');

		$userinfo = $this->administratorService->getAdministratorByToken($token);

		if (is_null($userinfo)) {
			return $this->response->auth('未登录或者登录已过期！');
		}

		if (false === $userinfo) {
			return $this->response->auth('账户或者所属角色已被封禁！');
		}

		Context::set('userinfo', $userinfo);

		return $handler->handle($request);
	}
}