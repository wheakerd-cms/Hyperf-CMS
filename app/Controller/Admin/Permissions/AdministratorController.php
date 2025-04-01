<?php
declare(strict_types=1);

namespace App\Controller\Admin\Permissions;

use App\Abstract\AbstractHttpController;
use App\Dao\Admin\DaoAdminAdministrator;
use App\Middleware\Authentication\MiddlewareAdminAuthentication;
use App\Model\Admin\ModelAdminAdministrator;
use App\Service\Admin\AdministratorService;
use Hyperf\Context\Context;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * @AdministratorController
 * @\App\Controller\Admin\Permissions\AdministratorController
 */
#[
	Controller(prefix: '/admin/permissions/administrator'),
	Middlewares([
		MiddlewareAdminAuthentication::class,
	]),
]
final class AdministratorController extends AbstractHttpController
{
	public function __construct(
		private readonly DaoAdminAdministrator $daoAdminAdministrator,
		private readonly AdministratorService  $administratorService,
	)
	{
	}

	/**
	 * @return ResponseInterface
	 * @api /admin/permissions/administrator/table
	 */
	#[
		RequestMapping(path: 'table', methods: ['GET']),
	]
	public function table(): ResponseInterface
	{
		$params = $this->validatorFactory->make(
			$this->request->all(),
			[
				'params' => 'sometimes|json',
			],
		)->validate();

		$params = $params['params'] ?? null;

		/* @var ModelAdminAdministrator $userinfo */
		$userinfo = Context::get('userinfo');

		return $this->response->success(
			$this->administratorService->getAdministratorBelongToRole(
				$userinfo->roleId,
				$params,
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
				'id'       => 'sometimes|integer:strict',
				'username' => 'required|string',
				'password' => 'required|string',
				'roleId'   => 'required|integer:strict',
				'status'   => 'required|boolean:strict',
				'avatar'   => 'sometimes|string',
			],
		)->validate();

		$res = $this->daoAdminAdministrator->save($inputs);

		return $res ? $this->response->success() : $this->response->error();
	}

	/**
	 * @return ResponseInterface
	 * @api /admin/permissions/administrator/delete
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

		$res = $this->daoAdminAdministrator->delete($inputs['ids']);

		return $res ? $this->response->success() : $this->response->error();
	}
}