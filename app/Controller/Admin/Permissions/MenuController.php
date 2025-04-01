<?php
declare(strict_types=1);

namespace App\Controller\Admin\Permissions;

use App\Abstract\AbstractHttpController;
use App\Dao\Admin\DaoAdminRouter;
use App\Middleware\Authentication\MiddlewareAdminAuthentication;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * @MenuController
 * @\App\Controller\Admin\Permissions\MenuController
 */
#[
	Controller(prefix: '/admin/permissions/menu'),
	Middlewares([
		MiddlewareAdminAuthentication::class,
	]),
]
final class MenuController extends AbstractHttpController
{
	public function __construct(private readonly DaoAdminRouter $daoAdminRouter)
	{
	}

	/**
	 * @return ResponseInterface
	 * @api /admin/permissions/menu/list
	 */
	#[
		RequestMapping(path: 'list', methods: ['GET']),
	]
	public function list(): ResponseInterface
	{
		$inputs = $this->validatorFactory->make(
			$this->request->all(),
			[
				'params' => 'sometimes|json',
			],
		)->validate();

		$params = $inputs['params'] ?? null;

		return $this->response->success(
			$this->daoAdminRouter->list(
				parentKey: 'parentId',
				params   : $params,
				sorts    : ['id' => 'asc'],
			),
		);
	}

	/**
	 * @return ResponseInterface
	 * @api /admin/permissions/menu/save
	 */
	#[
		RequestMapping(path: 'save', methods: ['POST']),
	]
	public function save(): ResponseInterface
	{
		$inputs = $this->validatorFactory->make(
			$this->request->all(),
			[
				'id'         => 'sometimes|integer:strict',
				'parentId'   => 'required|integer:strict',
				'type'       => 'required|integer:strict',
				'title'      => 'required|string',
				'name'       => 'required|string',
				'path'       => 'required|string',
				'component'  => 'required|string',
				'icon'       => 'required|string',
				'status'     => 'required|string',
				'sort'       => 'required|integer:strict',
				'redirect'   => 'required|string',
				'activeMenu' => 'required|boolean:strict',
				'hidden'     => 'required|boolean:strict',
				'alwaysShow' => 'required|boolean:strict',
				'noCache'    => 'required|boolean:strict',
				'breadcrumb' => 'required|boolean:strict',
				'affix'      => 'required|boolean:strict',
				'noTagsView' => 'required|boolean:strict',
				'canTo'      => 'required|boolean:strict',
			],
		)->validate();

		$res = $this->daoAdminRouter->save($inputs);

		return $res ? $this->response->success() : $this->response->error();
	}

	/**
	 * @return ResponseInterface
	 * @api /admin/permissions/menu/delete
	 */
	#[
		RequestMapping(path: 'delete', methods: ['DELETE']),
	]
	public function delete(): ResponseInterface
	{
		$inputs = $this->validatorFactory->make(
			$this->request->all(),
			[
				'ids' => 'required|array',
			],
		)->validate();

		$res = $this->daoAdminRouter->delete($inputs['ids']);

		return $res ? $this->response->success() : $this->response->error();
	}
}