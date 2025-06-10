<?php
declare(strict_types=1);

namespace App\Model\Admin;

use App\Abstract\AbstractModel;
use Carbon\Carbon;
use Hyperf\Database\Model\Relations\BelongsToMany;

/**
 * 管理员与角色的中间表
 *
 * @RoleModel
 * @\App\Model\Admin\RoleModel
 *
 * @property integer $id         主键
 * @property integer $parentId   上级角色组ID
 * @property string  $name       角色名称
 * @property boolean $isSystem   系统管理员，true为系统管理员，false为非系统管理员
 * @property boolean $status     状态
 * @property Carbon  $createTime 创建时间
 * @property Carbon  $updateTime 更新时间
 */
final class RoleModel extends AbstractModel
{
	protected ?string $table = 'admin_role';

	protected array $fillable = [
		'id',
		'parent_id',
		'name',
		'is_system',
		'status',
		'create_time',
		'update_time',
	];

	protected array $casts = [
		'id'          => 'integer',
		'name'        => 'string',
		'is_system'   => 'boolean',
		'status'      => 'boolean',
		'create_time' => 'datetime:Y-m-d H:i:s',
		'update_time' => 'datetime:Y-m-d H:i:s',
	];

	protected array $with = [
		'routes',
	];

	public function routes(): BelongsToMany
	{
		return $this->belongsToMany(MenuModel::class, (new RoleMenuModel())->getTable(), 'role_id', 'menu_id');
	}
}