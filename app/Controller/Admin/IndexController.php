<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Abstract\AbstractHttpController;
use App\Middleware\Authentication\AuthenticationMiddleware;
use App\Security\AdminSecurity;
use App\Service\Admin\AdministratorService;
use App\Validator\Admin\ValidatorAdminAdministrator;
use Gregwar\Captcha\CaptchaBuilder;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * 零碎功能
 *
 * @IndexController
 * @\App\Controller\Admin\IndexController
 */
#[Controller(prefix: '/admin/index')]
final class IndexController extends AbstractHttpController
{
	public function __construct(private readonly AdministratorService $serviceAdminAdministrator)
	{
	}

	/**
	 * 登录
	 *
	 * @param ValidatorAdminAdministrator $validator
	 * @param AdminSecurity               $security
	 *
	 * @return ResponseInterface
	 *
	 * @api /admin/index/login
	 */
	#[
		RequestMapping(path: 'login', methods: 'POST'),
	]
	public function login(ValidatorAdminAdministrator $validator, AdminSecurity $security): ResponseInterface
	{
		$inputs   = $validator->login();
		$userinfo = $this->serviceAdminAdministrator->login(... $inputs);
		$userid   = $userinfo->id;
		$token    = $security->create($userid);

		$this->session->set('userid', $userid);

		return $this->response->success(
			data   : $userinfo,
			headers: [
				         'Authorization' => $token,
			         ],
		);
	}

	/**
	 * 登出
	 *
	 * @return ResponseInterface
	 *
	 * @api /admin/index/logout
	 */
	#[
		RequestMapping(path: 'logout', methods: ['POST']),
		Middlewares([
			AuthenticationMiddleware::class,
		]),
	]
	public function logout(): ResponseInterface
	{
		$token = $this->request->header('Authorization');

		//  移除签发凭证
		$this->serviceAdminAdministrator->logout($token);

		return $this->response->success();
	}

	#[
		RequestMapping(path: 'captcha', methods: ['GET']),
	]
	public function captcha(): void
	{
		$builder = (new CaptchaBuilder())->build();
		$phrase  = $builder->getPhrase();

		$this->session->set('captcha', $phrase);

		ob_start();
		$builder->output();
		$image = ob_get_clean();

		$response = $this->response->getSocket();

		$response->setHeader('Content-Type', 'image/png');
		$response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
		$response->setHeader('Pragma', 'no-cache');

		$response->end($image);
	}
}