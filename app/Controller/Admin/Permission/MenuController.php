<?php
declare(strict_types=1);

namespace App\Controller\Admin\Permission;

use App\Abstract\AbstractHttpController;
use App\Dao\Admin\MenuDao;
use App\Middleware\Authentication\AuthenticationMiddleware;
use App\Validator\Admin\MenuValidator;
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
}