<?php
declare(strict_types=1);

namespace App\Model\Admin;

use App\Abstract\AbstractModel;

/**
 * 后台角色与菜单的中间表
 *
 * @RoleMenuModel
 * @\App\Model\Base\RoleMenuModel
 *
 * @property int $id     主键
 * @property int $roleId 角色ID，关联admin_role.id
 * @property int $menuId 菜单ID，关联admin_menu.id
 */
final class RoleMenuModel extends AbstractModel
{
	protected ?string $table = 'admin_role_menu';

	public bool $timestamps = false;

	protected array $fillable = [
		'id',
		'role_id',
		'menu_id',
	];

	protected array $casts = [
		'id'      => 'integer',
		'role_id' => 'integer',
		'menu_id' => 'integer',
	];
}