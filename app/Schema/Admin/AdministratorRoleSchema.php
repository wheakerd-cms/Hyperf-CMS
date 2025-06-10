<?php
declare(strict_types=1);

namespace App\Schema\Admin;

use App\Abstract\AbstractMigration;
use App\Model\Admin\AdministratorRoleModel;
use Hyperf\Database\Schema\Blueprint;

final class AdministratorRoleSchema extends AbstractMigration
{
	public function __construct(AdministratorRoleModel $model)
	{
		parent::__construct($model);
	}

	/**
	 * @param Blueprint $blueprint
	 *
	 * @return void
	 */
	public function schema(Blueprint $blueprint): void
	{
		$blueprint->increments('id')->nullable(false)->comment('主键');
		$blueprint->string('admin_id', 20)->nullable(false)->comment('管理员ID')->unique();
		$blueprint->string('role_id', 255)->nullable(false)->comment('角色ID');
	}

	/**
	 * @return array
	 */
	public function data(): array
	{
		return [
			[
				'adminId' => 1,
				'roleId'  => 1,
			],
			[
				'adminId' => 2,
				'roleId'  => 2,
			],
		];
	}
}