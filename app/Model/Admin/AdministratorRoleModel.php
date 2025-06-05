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
 * @property int $id     主键
 * @property int $userid 管理员ID
 * @property int $roleId 角色ID
 */
final class AdministratorRoleModel extends AbstractModel
{
	protected ?string $table = 'base_administrator_role';

	protected array $fillable = [
		'id',
		'userid',
		'role_id',
	];

	protected array $casts = [
		'id'      => 'integer',
		'userid'  => 'integer',
		'role_id' => 'integer',
	];
}