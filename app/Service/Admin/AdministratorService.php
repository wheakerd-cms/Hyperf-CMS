<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Abstract\AbstractService;
use App\Cache\Admin\AdministratorCache;
use App\Dao\Admin\AdministratorDao;
use App\Dao\Admin\RoleDao;
use App\Dao\Admin\MenuDao;
use App\Exception\CustomMessageException;
use App\Model\Admin\AdministratorModel;
use App\Model\Admin\ModelAdminRoles;
use App\Security\AdminSecurity;
use App\Utils\Functions;
use stdClass;

/**
 * @AdministratorService
 * @\App\Service\Admin\AdministratorService
 */
final class AdministratorService extends AbstractService
{
	public function __construct(
		private readonly AdministratorCache $administratorCache,
		private readonly AdminSecurity      $adminSecurity,
		private readonly AdministratorDao   $daoAdminAdministrator,
		private readonly RoleDao            $daoAdminRoles,
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

		return (object)$userinfo->toArray();
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

	public function getMenuList(int $roleId): array
	{
		$routerIds = $roleId === 1
			? $this->daoAdminRouter->getAllId()
			: $this->daoAdminRoles->getRouters($roleId);

		$routes = $this->daoAdminRouter->getSelectInId($routerIds);

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

	/**
	 * 获取该用户角色下的所有管理员
	 *
	 * @param int         $roleId
	 * @param string|null $params
	 *
	 * @return array
	 */
	public function getAdministratorBelongToRole(int $roleId, ?string $params = null): array
	{
		$roleSelect = $this->daoAdminRoles->newQuery->select()->get()->toArray();

		/* @var integer[] $roleGroup */
		$roleGroup = Functions::extraColumn($roleSelect, 'id', 'parentId', 'id', $roleId);

		return $this->table(
			params: $params,
			sorts : ['id' => 'desc'],
			with  : ['roles'],
			where : fn($query) => $query->whereIn('role_id', $roleGroup),
		);
	}
}