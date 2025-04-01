<?php
declare(strict_types=1);

namespace App\Controller\Admin\Permissions;

use App\Abstract\AbstractHttpController;
use App\Middleware\Authentication\MiddlewareAdminAuthentication;
use App\Model\Admin\ModelAdminAdministrator;
use App\Service\Admin\RolesService;
use Hyperf\Context\Context;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * @RolesController
 * @\App\Controller\Admin\Permissions\RolesController
 */
#[
	Controller(prefix: '/admin/permissions/roles'),
	Middlewares([
		MiddlewareAdminAuthentication::class,
	]),
]
final class RolesController extends AbstractHttpController
{
	public function __construct(private readonly RolesService $rolesService)
	{
	}

	/**
	 * @return ResponseInterface
	 * @api /admin/permissions/roles/list
	 */
	#[
		RequestMapping(path: 'list', methods: 'GET'),
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

		/* @var ModelAdminAdministrator $userinfo */
		$userinfo = Context::get('userinfo');

		return $this->response->success(
			$this->rolesService->list(
				parentKey: 'parentId',
				key      : $userinfo->roleId,
				params   : $params,
				sorts    : ['id' => 'asc'],
			),
		);
	}

	/**
	 * @return ResponseInterface
	 * @api /admin/permissions/roles/select
	 */
	#[
		RequestMapping(path: 'select', methods: 'GET'),
	]
	public function select(): ResponseInterface
	{
		$inputs = $this->validatorFactory->make(
			$this->request->all(),
			[
				'params' => 'sometimes|json',
			],
		)->validate();

		$params = $inputs['params'] ?? null;

		return $this->response->success(
			$this->rolesService->select(
				params: $params,
				sorts : ['id' => 'asc'],
			),
		);
	}

	/**
	 * @return ResponseInterface
	 * @api /admin/permissions/roles/list
	 */
	#[
		RequestMapping(path: 'save', methods: ['POST']),
	]
	public function save(): ResponseInterface
	{
		$inputs = $this->validatorFactory->make(
			$this->request->all(),
			[
				'id'       => 'sometimes|integer:strict',
				'parentId' => 'required|integer:strict',
				'name'     => 'required|string',
				'router'   => 'required|nullable|array',
				'status'   => 'required|boolean:strict',
			],
		)->validate();

		$res = $this->rolesService->save($inputs);

		return $res ? $this->response->success() : $this->response->error();
	}

	/**
	 * @return ResponseInterface
	 * @api /admin/permissions/roles/delete
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

		$res = $this->rolesService->delete($inputs['ids']);

		return $res ? $this->response->success() : $this->response->error();
	}
}