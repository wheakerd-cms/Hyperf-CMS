<?php
declare(strict_types=1);

namespace App\Controller\Admin\Permission;

use App\Abstract\AbstractHttpController;
use App\Dao\Admin\RoleDao;
use App\Middleware\Authentication\AuthenticationMiddleware;
use App\Validator\Admin\RoleValidator;
use App\Validator\ErasureValidator;
use App\Validator\PaginationValidator;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * @RoleController
 * @\App\Controller\Admin\Permission\RoleController
 */
#[
	Controller(prefix: '/admin/permission/role'),
	Middlewares([
		AuthenticationMiddleware::class,
	]),
]
final class RoleController extends AbstractHttpController
{
	public function __construct(
		private readonly RoleDao       $dao,
		private readonly RoleValidator $validator,
	)
	{
	}

	/**
	 * @param PaginationValidator $validator
	 *
	 * @return ResponseInterface
	 * @api {get} /admin/permission/role/table
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

	/**
	 * @return ResponseInterface
	 * @api {post} /admin/permission/role/save
	 */
	#[
		RequestMapping(path: 'save', methods: 'post'),
	]
	public function save(): ResponseInterface
	{
		$inputs = $this->validator->save();

		$this->dao->saveByMenuList(...$inputs);

		return $this->response->success();
	}

	/**
	 * @param ErasureValidator $validator
	 *
	 * @return ResponseInterface
	 * @api {delete} /admin/permission/role/delete
	 */
	#[
		RequestMapping(path: 'delete', methods: 'delete'),
	]
	public function delete(ErasureValidator $validator): ResponseInterface
	{
		$ids = $validator->validated();

		$this->dao->delete($ids);

		return $this->response->success();
	}
}