<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Abstract\AbstractHttpController;
use App\Middleware\Authentication\AuthenticationMiddleware;
use App\Security\AdminSecurity;
use App\Service\Admin\PermissionService;
use App\Validator\Admin\IndexValidator;
use Gregwar\Captcha\CaptchaBuilder;
use Hyperf\Context\Context;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * Fragmented functions.
 *
 * @IndexController
 * @\App\Controller\Admin\IndexController
 */
#[
	Controller(prefix: '/admin/index'),
]
final class IndexController extends AbstractHttpController
{
	public function __construct(
		private readonly IndexValidator    $validator,
		private readonly AdminSecurity     $security,
		private readonly PermissionService $permissionService,
	)
	{
	}

	/**
	 * Get a captcha.
	 *
	 * @return ResponseInterface
	 *
	 * @api {get} /admin/index/captcha
	 */
	#[
		RequestMapping(path: 'captcha', methods: 'GET'),
	]
	public function captcha(): ResponseInterface
	{
		$builder = (new CaptchaBuilder())->build();
		$phrase  = $builder->getPhrase();

		ob_start();
		$builder->output();
		$image = ob_get_clean();

		$base64 = 'data:image/png;base64,' . base64_encode($image);

		$this->session->set('captcha', $phrase);

		return $this->response->success($base64);
	}

	/**
	 * 登录
	 *
	 * @return ResponseInterface
	 *
	 * @api {post} /admin/index/login
	 */
	#[
		RequestMapping(path: 'login', methods: 'POST'),
	]
	public function login(): ResponseInterface
	{
		$inputs   = $this->validator->login();
		$userinfo = $this->permissionService->login(... $inputs);
		$userid   = $userinfo->id;
		$token    = $this->security->create($userid);

		$this->session->set('userid', $userid);

		return $this->response->success(
			data   : $userinfo,
			headers: [
				         'Authorization' => $token,
			         ],
		);
	}

	/**
	 * @return ResponseInterface
	 *
	 * @api {get} /admin/index/userinfo
	 */
	#[
		RequestMapping(path: 'userinfo', methods: 'GET'),
		Middlewares([
			AuthenticationMiddleware::class,
		]),
	]
	public function userinfo(): ResponseInterface
	{
		$userinfo = Context::get('userinfo');

		return $this->response->success($userinfo);
	}

	/**
	 * @return ResponseInterface
	 *
	 * @api {get} /admin/index/routes
	 */
	#[
		RequestMapping(path: 'routes', methods: 'GET'),
		Middlewares([
			AuthenticationMiddleware::class,
		]),
	]
	public function routes(): ResponseInterface
	{
		$userinfo = Context::get('userinfo');

		return $this->response->success(
			$this->permissionService->getMenuList($userinfo),
		);
	}

	/**
	 * 登出
	 *
	 * @return ResponseInterface
	 *
	 * @api {post} /admin/index/logout
	 */
	#[
		RequestMapping(path: 'logout', methods: 'POST'),
		Middlewares([
			AuthenticationMiddleware::class,
		]),
	]
	public function logout(): ResponseInterface
	{
		$token = $this->request->header('Authorization');

		//  移除签发凭证
		$this->permissionService->logout($token);

		return $this->response->success();
	}
}