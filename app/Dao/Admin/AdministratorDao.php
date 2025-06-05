<?php
declare(strict_types=1);

namespace App\Dao\Admin;

use App\Abstract\AbstractDao;
use App\Model\Admin\AdministratorModel;

/**
 * @AdministratorDao
 * @\App\Dao\Admin\AdministratorDao
 */
final readonly class AdministratorDao extends AbstractDao
{
	public function __construct(AdministratorModel $model)
	{
		parent::__construct($model);
	}

	/**
	 * 通过用户名称查询用户信息（取出一条数据）
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return false|AdministratorModel|null
	 */
	public function getAdministrator(string $username, string $password): null|false|AdministratorModel
	{
		/* @var null|AdministratorModel $model */
		$model = $this->newQuery->where('username', $username)->first();

		if (is_null($model)) {
			return null;
		}

		if (!password_verify($password, $model->password)) {
			return false;
		}

		return $model;
	}

	public function getUserinfoById(int $userid): ?AdministratorModel
	{
		return $this->newQuery->find($userid);
	}
}