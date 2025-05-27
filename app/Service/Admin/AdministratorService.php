<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Abstract\AbstractService;
use App\Cache\Admin\AdministratorCache;
use App\Dao\Admin\DaoAdminAdministrator;
use App\Dao\Admin\DaoAdminRoles;
use App\Dao\Admin\DaoAdminRouter;
use App\Exception\CustomMessageException;
use App\Model\Admin\ModelAdminAdministrator;
use App\Model\Admin\ModelAdminRoles;
use App\Security\AdminSecurity;
use App\Utils\Functions;

/**
 * @AdministratorService
 * @\App\Service\Admin\AdministratorService
 */
final class AdministratorService extends AbstractService
{
	public function __construct(
		private readonly AdministratorCache    $administratorCache,
		private readonly AdminSecurity         $adminSecurity,
		private readonly DaoAdminAdministrator $daoAdminAdministrator,
		private readonly DaoAdminRoles         $daoAdminRoles,
		private readonly DaoAdminRouter        $daoAdminRouter,
	)
	{
	}

	/**
	 * 通过主键获取管理员数据
	 *
	 * @param int $id
	 *
	 * @return ModelAdminAdministrator|null
	 */
	public function getAdministratorInfoById(int $id): ?ModelAdminAdministrator
	{
		return $this->daoAdminAdministrator->newQuery()->find($id);
	}

	/**
	 * @param string $username
	 * @param string $password
	 *
	 * @return array
	 */
	public function login(string $username, string $password): array
	{
		$userinfo = $this->daoAdminAdministrator->getUserinfoByUsername($username);

		if (is_null($userinfo) || !password_verify($password, $userinfo->password)) {
			throw new CustomMessageException('账号或者密码错误');
		}

		if (!$userinfo->status) {
			throw new CustomMessageException('该账号已被禁用');
		}

		$token = $this->adminSecurity->create(
			[
				'id' => $userinfo->id,
			], 60 * 60 * 24 * 30,
		);

		$this->administratorCache->setToken($token, $userinfo->id);

		return [
			$token,
			$userinfo->toArray(),
		];
	}

	/**
	 * 通过凭证获取用户信息
	 *
	 * @param string $token
	 *
	 * @return false|ModelAdminAdministrator|null
	 */
	public function getAdministratorByToken(string $token): null|false|ModelAdminAdministrator
	{
		if (!strlen($token)) {
			return null;
		}

		$userid = $this->administratorCache->getToken($token);
		if (false === $userid) {
			return null;
		}

		$userinfo = $this->daoAdminAdministrator->getUserinfoById($userid);
		if (is_null($userinfo)) {
			return null;
		}

		if (false === $userinfo->status) {
			return false;
		}

		/* @var ModelAdminRoles|null $role */
		$role = $userinfo->roles()->first();

		if (true !== $role?->status) {
			return false;
		}

		return $userinfo;
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