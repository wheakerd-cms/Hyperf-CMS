<?php
declare(strict_types=1);

namespace App\Model\Admin;

use App\Abstract\AbstractModel;

/**
 * 管理员与角色的中间表
 *
 * @AdministratorRoleModel
 * @\App\Model\Admin\AdministratorRoleModel
 *
 * @property int $id      主键
 * @property int $adminId 管理员ID
 * @property int $roleId  角色ID
 */
final class AdministratorRoleModel extends AbstractModel
{
	protected ?string $table = 'admin_administrator_role';

	public bool $timestamps = false;

	protected array $fillable = [
		'id',
		'admin_id',
		'role_id',
	];

	protected array $casts = [
		'id'       => 'integer',
		'admin_id' => 'integer',
		'role_id'  => 'integer',
	];
}