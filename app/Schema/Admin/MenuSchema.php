<?php
declare(strict_types=1);

namespace App\Schema\Admin;

use App\Abstract\AbstractMigration;
use App\Model\Admin\ModelAdminMenuModel;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

/**
 * @MenuSchema
 * @\App\Schema\Admin\MenuSchema
 */
final class MenuSchema extends AbstractMigration
{
	public function __construct(readonly ModelAdminMenuModel $model)
	{
		parent::__construct($model);
	}

	/**
	 * @return void
	 */
	public function before(): void
	{
		Schema::create($this->model->getTable(), function (Blueprint $blueprint) {
			$blueprint->increments('id')->nullable(false)->comment('主键');

			$blueprint->unsignedInteger('parent_id')->nullable()->comment('父级路由');

			$blueprint->unsignedTinyInteger('type')->nullable(false)->comment('类型');

			$blueprint->string('title', 255)->nullable(false)->comment('路由名称');

			$blueprint->string('name', 255)->nullable(false)->comment('用户定义的路由记录的可能的名称');

			$blueprint->string('icon', 255)->nullable()->comment('图标');

			$blueprint->unsignedInteger('order')->nullable(false)->default(0)->comment('排序');

			$blueprint->integer('create_time', false, true)->nullable(false)->comment('创建时间');

			$blueprint->integer('update_time', false, true)->nullable(false)->comment('更新时间');
		});
	}

	/**
	 * @return void
	 */
	public function after(): void
	{
		$data = [
			[
				'id'       => 1,
				'parentId' => null,
				'type'     => 1,
				'title'    => '首页',
				'name'     => 'Index',
				'icon'     => 'carbon:home',
				'order'    => 9999,
			],
			[
				'id'       => 2,
				'parentId' => null,
				'type'     => 1,
				'title'    => '权限管理',
				'name'     => 'System',
				'icon'     => 'carbon:id-management',
				'order'    => 9999,
			],
			[
				'id'       => 3,
				'parentId' => 2,
				'type'     => 2,
				'title'    => '菜单管理',
				'name'     => 'SystemMenu',
				'icon'     => 'carbon:ibm-dynamic-route-server',
				'order'    => 0,
			],
			[
				'id'       => 4,
				'parentId' => 3,
				'type'     => 3,
				'title'    => '新增',
				'name'     => 'MenuAdd',
				'icon'     => null,
				'order'    => 0,
			],
			[
				'id'       => 5,
				'parentId' => 3,
				'type'     => 3,
				'title'    => '删除',
				'name'     => 'MenuDelete',
				'icon'     => null,
				'order'    => 0,
			],
			[
				'id'       => 6,
				'parentId' => 2,
				'type'     => 2,
				'title'    => '角色管理',
				'name'     => 'SystemRole',
				'icon'     => 'carbon:user-role',
				'order'    => 0,
			],
			[
				'id'       => 7,
				'parentId' => 3,
				'type'     => 3,
				'title'    => '添加下级',
				'name'     => 'MenuAddSubordinate',
				'icon'     => null,
				'order'    => 0,
			],
			[
				'id'       => 8,
				'parentId' => 3,
				'type'     => 3,
				'title'    => '编辑',
				'name'     => 'MenuEdit',
				'icon'     => null,
				'order'    => 0,
			],
			[
				'id'       => 9,
				'parentId' => 2,
				'type'     => 2,
				'title'    => '管理员管理',
				'name'     => 'MenuAdministrator',
				'icon'     => 'carbon:gateway-user-access',
				'order'    => 0,
			],
			[
				'id'       => 10,
				'parentId' => null,
				'type'     => 1,
				'title'    => '配置中心',
				'name'     => 'Configuration',
				'icon'     => 'carbon:gears',
				'order'    => 0,
			],
		];

		foreach ($data as $item) {
			$instance = $this->model->newInstance();

			foreach ($item as $key => $value) {
				$instance->{$key} = $value;
			}

			$instance->save();
		}
	}
}