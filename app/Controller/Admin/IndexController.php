<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Abstract\AbstractHttpController;
use App\Middleware\Authentication\MiddlewareAdminAuthentication;
use App\Security\AdminSecurity;
use App\Service\Admin\AdministratorService;
use App\Validator\Admin\AdministratorValidator;
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
	 * @param AdministratorValidator $validator
	 * @param AdminSecurity          $adminSecurity
	 *
	 * @return ResponseInterface
	 *
	 * @api /admin/index/login
	 */
	#[
		RequestMapping(path: 'login', methods: [
			'GET',
			'POST',
		]),
	]
	public function login(AdministratorValidator $validator, AdminSecurity $adminSecurity): ResponseInterface
	{
		var_dump(1);
		$inputs   = $validator->scene('login')->validated();
		$userinfo = $this->serviceAdminAdministrator->login(... $inputs);
		$token    = $adminSecurity->getToken($userinfo);

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
			MiddlewareAdminAuthentication::class,
		]),
	]
	public function logout(): ResponseInterface
	{
		$token = $this->request->header('Authorization');

		//  移除签发凭证
		$this->serviceAdminAdministrator->logout($token);

		return $this->response->success();
	}
}