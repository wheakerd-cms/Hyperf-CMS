<?php
declare(strict_types=1);

namespace App\Schema\Admin;

use App\Abstract\AbstractMigration;
use App\Model\Admin\RoleModel;
use Hyperf\Database\Schema\Blueprint;

/**
 * @RoleSchema
 * @\App\Schema\Admin\RoleSchema
 */
final class RoleSchema extends AbstractMigration
{
	public function __construct(RoleModel $model)
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
		$blueprint->string('name', 20)->nullable()->comment('角色名称');
		$blueprint->unsignedInteger('parent_id')->nullable()->comment('上级角色组ID，NULL为顶级');
		$blueprint->unsignedTinyInteger('is_system')->nullable(false)->default(false)->comment('系统管理员，true为系统管理员，false为非系统管理员');
		$blueprint->unsignedInteger('create_time')->nullable(false)->comment('创建时间');
		$blueprint->unsignedInteger('update_time')->nullable(false)->comment('更新时间');
	}

	/**
	 * @return array
	 */
	public function data(): array
	{
		return [
			[
				'id'       => 1,
				'name'     => '系统管理员',
				'isSystem' => true,
			],
			[
				'id'   => 2,
				'name' => '超级管理员',
			],
		];
	}
}