<?php
declare(strict_types=1);

namespace App\Controller\Admin\Permission;

use App\Abstract\AbstractHttpController;
use App\Dao\Admin\AdministratorDao;
use App\Middleware\Authentication\AuthenticationMiddleware;
use App\Validator\Admin\AdministratorValidator;
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
		RequestMapping(path: 'save', methods: 'POST'),
	]
	public function save(): ResponseInterface
	{
		$inputs = $this->validator->save();

		$this->dao->save($inputs);

		return $this->response->success();
	}

	/**
	 * @return ResponseInterface
	 *
	 * @api {patch} /admin/permission/administrator/changeStatus
	 */
	#[
		RequestMapping(path: 'changeStatus', methods: 'PATCH'),
	]
	public function changeStatus(): ResponseInterface
	{
		$inputs = $this->validator->changeStatus();

		$this->dao->save($inputs);

		return $this->response->success();
	}
}