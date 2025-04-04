<?php
declare(strict_types=1);

namespace App\Model\Base;

use App\Abstract\AbstractModel;
use Carbon\Carbon;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\ModelCache\Cacheable;

/**
 * 存储文件表
 *
 * @ModelBaseStorageFiles
 * @\App\Model\Base\ModelBaseStorageFiles
 * @property integer $id         主键
 * @property integer $storageId  关联 base_storage_providers.id
 * @property string  $filename   文件原始名称
 * @property string  $filepath   云存储中的路径（Key）
 * @property integer $filesize   文件大小（字节）
 * @property string  $filetype   MIME 类型
 * @property string  $hash       文件哈希值（MD5/SHA1）
 * @property string  $visibility 文件访问权限
 * @property Carbon  $createTime 创建时间
 * @property Carbon  $updateTime 更新时间
 * @property integer $deleteTime 软删除，删除时间
 */
final class ModelBaseStorageFiles extends AbstractModel
{
	use SoftDeletes, Cacheable;

	protected ?string $table = 'base_storage_files';

	protected array $fillable = [
		'storage_id',
		'filename',
		'filepath',
		'filesize',
		'filetype',
		'hash',
		'visibility',
		'create_time',
		'update_time',
		'delete_time',
	];

	protected array $casts = [
		'id'          => 'integer',
		'storage_id'  => 'integer',
		'filesize'    => 'integer',
		'create_time' => 'datetime:Y-m-d H:i:s',
		'update_time' => 'datetime:Y-m-d H:i:s',
		'delete_time' => 'integer',
	];

	protected array $appends = [
		'base_uri',
	];

	/**
	 * @return null|string
	 *
	 * @used-by ModelBaseStorageFiles
	 */
	public function getBaseUriAttribute(): ?string
	{
		/* @var ModelBaseStorageProviders|null $model */
		$model = $this->withStorageProvider()->first();

		return $model?->endpoint;
	}

	/**
	 * 关联文件存储的服务商
	 *
	 * @return BelongsTo
	 */
	public function withStorageProvider(): BelongsTo
	{
		return $this->belongsTo(ModelBaseStorageProviders::class, 'storage_id', 'id');
	}
}
