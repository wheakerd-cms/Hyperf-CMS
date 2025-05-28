<?php
declare(strict_types=1);

namespace App\Model\Base;

use App\Abstract\AbstractModel;
use App\Model\Admin\RoleModel;
use Hyperf\Database\Model\Relations\MorphTo;

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
	protected ?string $table = 'base_role_menu';

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

	public function role(): MorphTo
	{
		return $this->morphTo(RoleModel::class . 'role_id', '');
	}
}