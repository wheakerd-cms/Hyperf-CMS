<?php
declare(strict_types=1);

namespace App\Model\Admin;

use App\Abstract\AbstractModel;
use App\Model\Base\RoleMenuModel;
use Carbon\Carbon;
use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\Database\Model\Relations\HasMany;

/**
 * 管理员与角色的中间表
 *
 * @RoleModel
 * @\App\Model\Admin\RoleModel
 *
 * @property integer $id         主键
 * @property integer $parentId   上级角色组ID
 * @property string  $name       角色名称
 * @property array   $routes     路由权限
 * @property boolean $status     状态
 * @property Carbon  $createTime 创建时间
 * @property Carbon  $updateTime 更新时间
 */
final class RoleModel extends AbstractModel
{
	protected ?string $table = 'base_role';

	protected array $fillable = [
		'id',
		'name',
		'routes',
		'create_time',
		'update_time',
	];

	protected array $casts = [
		'id'          => 'integer',
		'name'        => 'string',
		'create_time' => 'datetime:Y-m-d H:i:s',
		'update_time' => 'datetime:Y-m-d H:i:s',
	];

	/**
	 * @return HasMany
	 */
	public function roleMenu(): HasMany
	{
		return $this->hasMany(RoleMenuModel::class, 'role_id', 'id');
	}

	public function routes(): BelongsToMany
	{
		return $this->belongsToMany(ModelAdminMenuModel::class, 'base_role_menu', 'role_id', 'menu_id');
	}
}