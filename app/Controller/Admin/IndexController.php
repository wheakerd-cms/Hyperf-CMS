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
use Hyperf\Validation\Annotation\Scene;
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
	private readonly AdministratorService $serviceAdminAdministrator;

	public function __construct()
	{
	}

	/**
	 * 登录
	 *
	 * @param AdministratorValidator $loginValidator
	 * @param AdminSecurity          $adminSecurity
	 *
	 * @return ResponseInterface
	 *
	 * @api /admin/index/login
	 */
	#[
		RequestMapping(path: 'login', methods: ['POST']),
		Scene(scene: 'login', argument: 'loginValidator'),
	]
	public function login(AdministratorValidator $loginValidator, AdminSecurity $adminSecurity): ResponseInterface
	{
		$inputs = $loginValidator->validated();

		$userinfo = $this->serviceAdminAdministrator->login(... $inputs);

		$token = $adminSecurity->create($userinfo);

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

	/**
	 * @return void
	 *
	 * @api /test
	 */
	#[
		RequestMapping(path: '/test', methods: ['GET']),
	]
	public function test()
	{

	}
}