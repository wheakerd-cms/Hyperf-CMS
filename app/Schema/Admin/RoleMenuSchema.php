<?php
declare (strict_types=1);

namespace App\Schema\Admin;

use App\Abstract\AbstractMigration;
use App\Model\Admin\RoleMenuModel;
use Hyperf\Database\Schema\Blueprint;

/**
 * @RoleMenuSchema
 * @\App\Schema\Admin\RoleMenuSchema
 */
final class RoleMenuSchema extends AbstractMigration
{
	public function __construct(RoleMenuModel $model)
	{
		parent::__construct($model);
	}

	public function schema(Blueprint $blueprint): void
	{
		$blueprint->increments('id')->nullable(false)->comment('主键');
		$blueprint->unsignedInteger('role_id')->nullable(false)->comment('角色ID，关联admin_role.id');
		$blueprint->unsignedInteger('menu_id')->nullable(false)->comment('菜单ID，关联admin_menu.id');
	}

	/**
	 * @inheritDoc
	 */
	public function data(): array
	{
		return [
			[
				'roleId' => 1,
				'menuId' => 1,
			],
			[
				'roleId' => 1,
				'menuId' => 2,
			],
			[
				'roleId' => 1,
				'menuId' => 3,
			],
			[
				'roleId' => 1,
				'menuId' => 4,
			],
			[
				'roleId' => 1,
				'menuId' => 5,
			],
			[
				'roleId' => 1,
				'menuId' => 6,
			],
			[
				'roleId' => 1,
				'menuId' => 7,
			],
			[
				'roleId' => 1,
				'menuId' => 8,
			],
			[
				'roleId' => 1,
				'menuId' => 9,
			],
			[
				'roleId' => 1,
				'menuId' => 10,
			],
			[
				'roleId' => 2,
				'menuId' => 1,
			],
			[
				'roleId' => 2,
				'menuId' => 2,
			],
			[
				'roleId' => 2,
				'menuId' => 3,
			],
			[
				'roleId' => 2,
				'menuId' => 4,
			],
			[
				'roleId' => 2,
				'menuId' => 5,
			],
			[
				'roleId' => 2,
				'menuId' => 6,
			],
			[
				'roleId' => 2,
				'menuId' => 7,
			],
			[
				'roleId' => 2,
				'menuId' => 8,
			],
			[
				'roleId' => 2,
				'menuId' => 9,
			],
			[
				'roleId' => 2,
				'menuId' => 10,
			],
		];
	}
}