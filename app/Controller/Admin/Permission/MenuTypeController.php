<?php
declare(strict_types=1);

namespace App\Controller\Admin\Permission;

use App\Abstract\AbstractHttpController;
use App\Dao\Admin\MenuTypeDao;
use App\Middleware\Authentication\AuthenticationMiddleware;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * @MenuTypeController
 * @\App\Controller\Permission\MenuTypeController
 */
#[
	Controller(prefix: '/admin/permission/menuType'),
	Middlewares([
		AuthenticationMiddleware::class,
	]),
]
final class MenuTypeController extends AbstractHttpController
{
	public function __construct(private readonly MenuTypeDao $dao)
	{
	}

	/**
	 * 菜单类型下拉列表
	 *
	 * @return ResponseInterface
	 * @api {get} /admin/permission/menuType/select
	 */
	#[
		RequestMapping(path: 'select', methods: 'GET'),
	]
	public function select(): ResponseInterface
	{
		return $this->response->success(
			$this->dao->getSelect(),
		);
	}
}