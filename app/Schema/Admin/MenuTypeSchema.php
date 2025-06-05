<?php
declare(strict_types=1);

namespace App\Schema\Admin;

use App\Abstract\AbstractMigration;
use App\Model\Admin\MenuTypeModel;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

/**
 * @MenuTypeSchema
 * @\App\Schema\Admin\MenuTypeSchema
 */
final class MenuTypeSchema extends AbstractMigration
{
	public function __construct(MenuTypeModel $model)
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
		$blueprint->string('name', 10)->nullable(false)->comment('菜单类型名称');
	}

	/**
	 * @return array
	 */
	public function data(): array
	{
		return [
			[
				'id'   => 1,
				'name' => '目录',
			],
			[
				'id'   => 2,
				'name' => '菜单',
			],
			[
				'id'   => 3,
				'name' => 'API',
			],
		];
	}
}