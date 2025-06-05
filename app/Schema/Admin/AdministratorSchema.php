<?php
declare(strict_types=1);

namespace App\Schema\Admin;

use App\Abstract\AbstractMigration;
use App\Model\Admin\AdministratorModel;
use Hyperf\Database\Schema\Blueprint;

/**
 * @AdministratorSchema
 * @\App\Schema\AdministratorSchema
 */
final class AdministratorSchema extends AbstractMigration
{
	public function __construct(AdministratorModel $model)
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
		$blueprint->string('username', 20)->nullable(false)->comment('用户名');
		$blueprint->string('password', 255)->nullable(false)->comment('密码（此字段数据较为特殊,请查阅加密算法文档）');
		$blueprint->unsignedTinyInteger('status')->nullable(false)->default(false)->comment('账号状态，true为正常，false为封禁');
		$blueprint->unsignedTinyInteger('is_system')->nullable(false)->default(false)->comment('系统管理员，true为系统管理员，false为非系统管理员');
		$blueprint->string('avatar', 255)->nullable()->comment('账号头像');
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
				'username' => 'administrator',
				'password' => '123456',
				'status'   => true,
			],
			[
				'username' => 'admin',
				'password' => '123456',
				'status'   => true,
			],
		];
	}
}