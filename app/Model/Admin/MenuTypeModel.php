<?php
declare(strict_types=1);

namespace App\Model\Admin;

use App\Abstract\AbstractModel;

/**
 * 后台菜单类型表
 *
 * @MenuTypeModel
 * @\App\Model\Admin\MenuTypeModel
 *
 * @property int    $id   主键
 * @property string $name 菜单类型名称
 */
final class MenuTypeModel extends AbstractModel
{
	protected ?string $table = 'admin_menu_type';

	public bool $timestamps = false;

	protected array $fillable = [
		'id',
		'name',
	];

	protected array $casts = [
		'id'   => 'integer',
		'name' => 'string',
	];
}