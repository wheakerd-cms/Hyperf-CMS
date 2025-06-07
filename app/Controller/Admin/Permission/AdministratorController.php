<?php
declare(strict_types=1);

namespace App\Controller\Admin\Permission;

use App\Abstract\AbstractHttpController;
use App\Dao\Admin\AdministratorDao;
use App\Middleware\Authentication\AuthenticationMiddleware;
use App\Validator\Admin\AdministratorValidator;
use App\Validator\ErasureValidator;
use App\Validator\PaginationValidator;
use Hyperf\Database\Exception\UniqueConstraintViolationException;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * @AdministratorController
 * @\App\Controller\Admin\Permission\AdministratorController
 */
#[
	Controller(prefix: '/admin/permission/administrator'),
	Middlewares([
		AuthenticationMiddleware::class,
	]),
]
final class AdministratorController extends AbstractHttpController
{
	public function __construct(
		private readonly AdministratorValidator $validator,
		private readonly AdministratorDao       $dao,
	)
	{
	}

	/**
	 * @return ResponseInterface
	 *
	 * @api {post} /admin/permission/administrator/save
	 */
	#[
		RequestMapping(path: 'save', methods: 'post'),
	]
	public function save(): ResponseInterface
	{
		$inputs = $this->validator->save();

		try {
			$this->dao->save($inputs);
		}
		catch (UniqueConstraintViolationException) {
			return $this->response->error('新增账号不得与现有账户名称重复');
		}

		return $this->response->success();
	}

	/**
	 * @return ResponseInterface
	 *
	 * @api {patch} /admin/permission/administrator/changeStatus
	 */
	#[
		RequestMapping(path: 'changeStatus', methods: 'patch'),
	]
	public function changeStatus(): ResponseInterface
	{
		$inputs = $this->validator->changeStatus();

		$this->dao->save($inputs);

		return $this->response->success();
	}

	/**
	 * @param ErasureValidator $validator
	 *
	 * @return ResponseInterface
	 *
	 * @api {delete} /admin/permission/administrator/delete
	 */
	#[
		RequestMapping(path: 'delete', methods: 'delete'),
	]
	public function delete(ErasureValidator $validator): ResponseInterface
	{
		$inputs = $validator->validated();

		$this->dao->delete($inputs);

		return $this->response->success();
	}

	/**
	 * @param PaginationValidator $validator
	 *
	 * @return ResponseInterface
	 *
	 * @api {get} /admin/permission/administrator/table
	 */
	#[
		RequestMapping(path: 'table', methods: 'get'),
	]
	public function table(PaginationValidator $validator): ResponseInterface
	{
		$inputs = $validator->validated();

		return $this->response->success(
			$this->dao->table(...$inputs),
		);
	}
}