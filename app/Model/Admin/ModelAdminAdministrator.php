<?php
declare(strict_types=1);

namespace App\Model\Admin;

use App\Abstract\AbstractModel;
use Carbon\Carbon;
use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\Database\Model\Relations\HasOneThrough;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;

/**
 * @ModelAdminAdministrator
 * @\App\Model\Admin\ModelAdminAdministrator
 *
 * @property integer $id         主键
 * @property string  $username   用户名
 * @property string  $password   密码（此字段数据较为特殊,请查阅加密算法文档）
 * @property boolean $status     账号状态，true为正常，false为封禁
 * @property string  $nickname   昵称
 * @property string  $avatar     账号头像
 * @property boolean $isSystem   系统管理员，true为系统管理员，false为非系统管理员
 * @property Carbon  $createTime 创建时间
 * @property Carbon  $updateTime 更新时间
 */
final class ModelAdminAdministrator extends AbstractModel implements CacheableInterface
{
	use Cacheable;

	protected ?string $table = 'admin_administrator';

	protected array $fillable = [
		'id',
		'username',
		'is_system',
		'status',
		'nickname',
		'password',
		'avatar',
		'create_time',
		'update_time',
	];

	protected array $casts = [
		'id'          => 'integer',
		'username'    => 'string',
		'is_system'   => 'boolean',
		'status'      => 'boolean',
		'nickname'    => 'string',
		'create_time' => 'datetime:Y-m-d H:i:s',
		'update_time' => 'datetime:Y-m-d H:i:s',
	];

	protected array $hidden = [
		'password',
		'is_system',
	];

	/**
	 * @used-by BaseAdministratorModel::$password
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	public function setPasswordAttribute(string $value): void
	{
		$this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
	}

	/**
	 * @return BelongsToMany
	 * @used-by BaseAdministratorModel
	 */
	public function roles(): BelongsToMany
	{
		return $this->belongsToMany(RoleModel::class, 'base_administrator_role', 'userid', 'role_id');
	}

	public function role(): HasOneThrough
	{
		return $this->hasOneThrough(
			related  : RoleModel::class,
			through  : ModelAdminAdministratorRole::class,
			firstKey : 'userid',
			secondKey: 'id',
		);
	}
}