<?php
declare(strict_types=1);

namespace App\Dao\Admin;

use App\Abstract\AbstractDao;
use App\Model\Admin\ModelAdminAdministrator;

/**
 * @DaoAdminAdministrator
 * @\App\Dao\Admin\DaoAdminAdministrator
 */
final readonly class DaoAdminAdministrator extends AbstractDao
{
	public function __construct(ModelAdminAdministrator $model)
	{
		parent::__construct($model);
	}

	/**
	 * 通过用户名称查询用户信息（取出一条数据）
	 *
	 * @param string $username
	 *
	 * @return ModelAdminAdministrator|null
	 */
	public function getUserinfoByUsername(string $username): ?ModelAdminAdministrator
	{
		return $this->newQuery->where('username', $username)->first();
	}

	public function getUserinfoById(int $userid): ?ModelAdminAdministrator
	{
		return $this->newQuery->find($userid);
	}

	public function getAdministratorByIdWithRole(int $id): ?ModelAdminAdministrator
	{
		return $this->newQuery->find($id)->with(['roles'])->get()->first();
	}

	/**
	 * 指定角色组进行查询用户
	 *
	 * @param array       $roleGroup
	 * @param string|null $username
	 * @param int         $currentPage
	 * @param int         $perPage
	 *
	 * @return array{
	 *     list: array,
	 *     total: integer,
	 * }
	 */
	public function getAdministratorsByRoleGroup(
		array   $roleGroup = [],
		?string $username = null,
		int     $currentPage = 1,
		int     $perPage = 20,
	): array
	{
		$query = $this->newQuery->whereIn('role_id', $roleGroup)->with(['roles'])->orderByDesc('id');

		$query = null === $username ? $query : $query->where('username', $username);

		$list = $query->offset(($currentPage - 1) * $perPage)->limit($perPage)->get()->toArray();

		$total = $query->count();

		return [
			$list,
			$total,
		];
	}
}