<?php
declare(strict_types=1);

namespace App\Model\Base;

use App\Abstract\AbstractModel;
use Carbon\Carbon;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\SoftDeletes;

/**
 * 存储服务提供商表
 *
 * @ModelBaseStorageProviders
 * @\App\Model\Base\ModelBaseStorageProviders
 *
 * @property-read string $driverName 驱动类型名称
 *
 * @property integer     $id         主键
 * @property integer     $driverType 文件存储驱动类型（关联base_storage_driver.id）
 * @property integer     $name       存储服务名称
 * @property integer     $alias      友好的显示名称
 * @property string      $endpoint   API 访问地址
 * @property string      $accessKey  访问 Key
 * @property string      $secretKey  访问密钥
 * @property string      $bucketName 存储桶名称
 * @property string      $region     区域
 * @property Carbon      $createTime 创建时间
 * @property Carbon      $updateTime 更新时间
 * @property integer     $deleteTime 软删除，删除时间
 */
final class ModelBaseStorageProviders extends AbstractModel
{
	use SoftDeletes;

	protected ?string $table = 'base_storage_providers';

	protected array $fillable = [
		'driver_type',
		'name',
		'alias',
		'endpoint',
		'access_key',
		'secret_key',
		'bucket_name',
		'region',
		'create_time',
		'update_time',
		'delete_time',
	];

	protected array $casts = [
		'id'          => 'integer',
		'driver_type' => 'integer',
		'name'        => 'string',
		'alias'       => 'string',
		'endpoint'    => 'string',
		'access_key'  => 'string',
		'secret_key'  => 'string',
		'bucket_name' => 'string',
		'region'      => 'string',
		'create_time' => 'datetime:Y-m-d H:i:s',
		'update_time' => 'datetime:Y-m-d H:i:s',
		'delete_time' => 'integer',
	];

	protected array $hidden = [
		'driver_name',
	];

	/**
	 * 文件驱动名称
	 *
	 * @return string
	 * @see     ModelBaseStorageProviders::driverName
	 * @used-by ModelBaseStorageProviders
	 */
	public function getDriverNameAttribute(): string
	{
		/* @var ModelBaseStorageDriver $storageDriver */
		$storageDriver = $this->withStorageDriver()->first();

		return $storageDriver->name;
	}

	/**
	 * 模型关联（多对一）
	 *
	 * @used-by ModelBaseStorageProviders
	 * @return BelongsTo
	 */
	public function withStorageDriver(): BelongsTo
	{
		return $this->belongsTo(ModelBaseStorageDriver::class, 'driver_type', 'id');
	}
}
