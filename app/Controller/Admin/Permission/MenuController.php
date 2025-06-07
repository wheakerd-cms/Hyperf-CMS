<?php
declare(strict_types=1);

namespace App\Controller\Admin\Permission;

use App\Abstract\AbstractHttpController;
use App\Dao\Admin\MenuDao;
use App\Dao\Admin\MenuTypeDao;
use App\Middleware\Authentication\AuthenticationMiddleware;
use App\Validator\Admin\MenuValidator;
use App\Validator\ErasureValidator;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * @MenuController
 * @\App\Controller\Admin\Permission\MenuController
 */
#[
	Controller(prefix: '/admin/permission/menu'),
	Middlewares([
		AuthenticationMiddleware::class,
	]),
]
final class MenuController extends AbstractHttpController
{
	public function __construct(
		private readonly MenuValidator $validator,
		private readonly MenuDao       $dao,
	)
	{
	}

	/**
	 * 路由列表
	 *
	 * @return ResponseInterface
	 * @api {get} /permission/menu/list
	 */
	#[
		RequestMapping(path: 'list', methods: 'GET'),
	]
	public function list(): ResponseInterface
	{
		return $this->response->success(
			$this->dao->getList(),
		);
	}

	/**
	 * @param ErasureValidator $validator
	 *
	 * @return ResponseInterface
	 * @api /admin/permission/menu/delete
	 */
	#[
		RequestMapping(path: 'delete', methods: ['DELETE']),
	]
	public function delete(ErasureValidator $validator): ResponseInterface
	{
		$ids = $validator->validated();

		$this->dao->delete($ids);

		return $this->response->success();
	}

	/**
	 * @return ResponseInterface
	 *
	 * @api {post} /admin/permission/menu/save
	 */
	#[
		RequestMapping(path: 'save', methods: 'POST'),
	]
	public function save(): ResponseInterface
	{
		$inputs = $this->validator->save();

		$this->dao->save($inputs);

		return $this->response->success();
	}

	/**
	 * 菜单类型下拉列表
	 *
	 * @param MenuTypeDao $menuTypeDao
	 *
	 * @return ResponseInterface
	 * @api {get} /admin/permission/menu/menuTypeSelect
	 */
	#[
		RequestMapping(path: 'menuTypeSelect', methods: 'get'),
	]
	public function select(MenuTypeDao $menuTypeDao): ResponseInterface
	{
		return $this->response->success(
			$menuTypeDao->getSelect(),
		);
	}
}