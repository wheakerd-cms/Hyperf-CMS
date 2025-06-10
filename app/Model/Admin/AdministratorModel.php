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
 * @AdministratorModel
 * @\App\Model\Admin\AdministratorModel
 *
 * @property integer            $id         主键
 * @property string             $username   用户名
 * @property string             $password   密码（此字段数据较为特殊,请查阅加密算法文档）
 * @property boolean            $status     账号状态，true为正常，false为封禁
 * @property string             $nickname   昵称
 * @property string             $avatar     账号头像
 * @property Carbon             $createTime 创建时间
 * @property Carbon             $updateTime 更新时间
 * @property-read BelongsToMany $roles
 */
final class AdministratorModel extends AbstractModel implements CacheableInterface
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
		'status'      => 'boolean',
		'nickname'    => 'string',
		'create_time' => 'datetime:Y-m-d H:i:s',
		'update_time' => 'datetime:Y-m-d H:i:s',
	];

	protected array $hidden = [
		'password',
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
	 * @see AdministratorModel::$roles
	 */
	public function roles(): BelongsToMany
	{
		return $this->belongsToMany(RoleModel::class, (new AdministratorRoleModel())->getTable(), 'admin_id', 'role_id');
	}

	public function role(): HasOneThrough
	{
		return $this->hasOneThrough(
			related  : RoleModel::class,
			through  : AdministratorRoleModel::class,
			firstKey : 'admin_id',
			secondKey: 'id',
		);
	}
}