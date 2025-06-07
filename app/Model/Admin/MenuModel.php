<?php
declare(strict_types=1);

namespace App\Model\Admin;

use App\Abstract\AbstractModel;
use Carbon\Carbon;
use Hyperf\Database\Model\Relations\BelongsTo;

/**
 * 后台路由表
 *
 * @MenuModel
 * @\App\Model\Admin\MenuModel
 *
 * @property integer $id         主键
 * @property integer $type       类型
 * @property string  $name       用户定义的路由记录的可能的名称
 * @property string  $icon       图标
 * @property string  $title      路由名称
 * @property integer $order      排序
 * @property integer $parentId   父级路由
 * @property Carbon  $createTime 创建时间
 * @property Carbon  $updateTime 更新时间
 */
final class MenuModel extends AbstractModel
{
	protected ?string $table = 'admin_menu';

	protected array $fillable = [
		'id',
		'parent_id',
		'type',
		'title',
		'name',
		'icon',
		'order',
		'create_time',
		'update_time',
	];

	protected array $casts = [
		'id'          => 'integer',
		'parent_id'   => 'integer',
		'type'        => 'integer',
		'title'       => 'string',
		'name'        => 'string',
		'icon'        => 'string',
		'order'       => 'integer',
		'create_time' => 'datetime:Y-m-d H:i:s',
		'update_time' => 'datetime:Y-m-d H:i:s',
	];

	public function type(): BelongsTo
	{
		return $this->belongsTo(MenuTypeModel::class, 'type', 'id');
	}
}