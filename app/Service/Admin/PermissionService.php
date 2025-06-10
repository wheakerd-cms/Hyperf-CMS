<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Abstract\AbstractService;
use App\Cache\Admin\AdministratorCache;
use App\Dao\Admin\AdministratorDao;
use App\Dao\Admin\MenuDao;
use App\Exception\CustomMessageException;
use App\Model\Admin\AdministratorModel;
use App\Model\Admin\RoleModel;
use stdClass;

/**
 * @PermissionService
 * @\App\Service\Admin\PermissionService
 */
final class PermissionService extends AbstractService
{
	public function __construct(
		private readonly AdministratorCache $administratorCache,
		private readonly AdministratorDao   $daoAdminAdministrator,
		private readonly MenuDao            $daoAdminRouter,
	)
	{
	}

	/**
	 * @param string $username
	 * @param string $password
	 *
	 * @return stdClass
	 * @phpstan-return AdministratorModel
	 */
	public function login(string $username, string $password): object
	{
		$userinfo = $this->daoAdminAdministrator->getAdministrator($username, $password);

		if (!$userinfo) {
			throw new CustomMessageException('账号或者密码错误');
		}

		if (!$userinfo->status) {
			throw new CustomMessageException('该账号已被禁用');
		}

		return (object)$userinfo->makeVisible(
			[
				'username',
				'avatar',
			],
		)->toArray();
	}

	/**
	 * @param string $token
	 *
	 * @return void
	 */
	public function logout(string $token): void
	{
		$this->administratorCache->delToken($token);
	}

	public function getMenuList(AdministratorModel $model): array
	{
		$routes = [];

		/* @var RoleModel $role */
		foreach ($model->roles as $role) {
			if ($role->isSystem) {
				$routes = $this->daoAdminRouter->getList();
				break;
			}
			$routes = $role->routes();
		}

		$getMenuList = function (array &$list, int $parentId = 0) use (&$getMenuList) {
			$data = [];

			foreach ($list as &$item) if ($item ['parentId'] == $parentId) {

				$metadata          = array_filter(
					$item,
					fn($value, $key) => in_array($key, [
							'name',
							'path',
							'component',
							'redirect',
						]) && !is_null($value),
					ARRAY_FILTER_USE_BOTH,
				);
				$metadata ['meta'] = $item;

				if ($children = $getMenuList($list, $item ['id'])) {
					$metadata += compact('children');
				}
				$data [] = $metadata;
				unset($item);
			}

			return $data;
		};

		return $getMenuList($routes);
	}
}