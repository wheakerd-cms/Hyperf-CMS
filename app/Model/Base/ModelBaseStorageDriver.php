<?php
declare(strict_types=1);

namespace App\Model\Base;

use App\Abstract\AbstractModel;
use Hyperf\ModelCache\Cacheable;

/**
 * 文件存储驱动
 *
 * @ModelBaseStorageDriver
 * @\App\Model\Base\ModelBaseStorageDriver
 *
 * @property integer $id    主键
 * @property string  $name  驱动名称
 */
final class ModelBaseStorageDriver extends AbstractModel
{
	use Cacheable;

	protected ?string $table = 'base_storage_driver';

	protected array $fillable = [
		'name',
	];

	protected array $casts = [
		'name' => 'string',
	];
}