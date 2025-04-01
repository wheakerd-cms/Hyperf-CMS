<?php
declare(strict_types=1);

namespace App\Model\Admin;

use App\Abstract\AbstractModel;
use Carbon\Carbon;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\ModelCache\Cacheable;

/**
 * @ModelAdminRouter
 * @\app\Model\Admin\ModelAdminRouter
 * @property integer      $id         主键
 * @property integer      $parentId   父级 ID
 * @property integer      $type       菜单类型
 * @property string       $title      菜单名称
 * @property string       $name       组件名称
 * @property string       $path       路由地址
 * @property string       $component  组件路径
 * @property string       $icon       图标
 * @property integer      $status     状态
 * @property integer      $sort       排序
 * @property string       $redirect   跳转地址
 * @property string       $activeMenu 显示高亮的路由路径
 * @property integer      $hidden     当设置 true 的时候该路由不会再侧边栏出现 如404，login等页面(默认 false)
 * @property integer      $alwaysShow 当你一个路由下面的 children 声明的路由大于1个时，自动会变成嵌套的模式，只有一个时，会将那个子路由当做根路由显示在侧边栏，若你想不管路由下面的
 *                                    children 声明的个数都显示你的根路由，你可以设置 alwaysShow: true，这样它就会忽略之前定义的规则，一直显示根路由(默认 false)
 * @property integer      $noCache    如果设置为true，则不会被 <keep-alive> 缓存(默认 false)
 * @property integer      $breadcrumb 如果设置为false，则不会在breadcrumb面包屑中显示(默认 true)
 * @property integer      $affix      如果设置为true，则会一直固定在tag项中(默认 false)
 * @property string       $noTagsView 如果设置为true，则不会出现在tag中(默认 false)
 * @property integer      $canTo      设置为true即使hidden为true，也依然可以进行路由跳转(默认 false)
 * @property Carbon       $createTime 创建时间
 * @property Carbon       $updateTime 更新时间
 * @property integer|null $deleteTime 软删除，删除时间
 */
final class ModelAdminRouter extends AbstractModel
{
	use Cacheable, SoftDeletes;

	protected ?string $table = 'admin_router';

	protected array $fillable = [
		'id',
		'parent_id',
		'type',
		'title',
		'name',
		'path',
		'component',
		'icon',
		'status',
		'sort',
		'redirect',
		'active_menu',
		'hidden',
		'always_show',
		'no_cache',
		'breadcrumb',
		'affix',
		'no_tags_view',
		'can_to',
		'create_time',
		'update_time',
		'delete_time',
	];

	protected array $casts = [
		'id'           => 'integer',
		'parent_id'    => 'integer',
		'type'         => 'integer',
		'title'        => 'string',
		'name'         => 'string',
		'path'         => 'string',
		'component'    => 'string',
		'icon'         => 'string',
		'status'       => 'boolean',
		'sort'         => 'integer',
		'redirect'     => 'string',
		'active_menu'  => 'boolean',
		'hidden'       => 'boolean',
		'always_show'  => 'boolean',
		'no_cache'     => 'boolean',
		'breadcrumb'   => 'boolean',
		'affix'        => 'boolean',
		'no_tags_view' => 'boolean',
		'can_to'       => 'boolean',
		'create_time'  => 'datetime:Y-m-d H:i:s',
		'update_time'  => 'datetime:Y-m-d H:i:s',
		'delete_time'  => 'integer',
	];
}
