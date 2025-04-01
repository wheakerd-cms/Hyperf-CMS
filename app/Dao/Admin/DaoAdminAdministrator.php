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
}