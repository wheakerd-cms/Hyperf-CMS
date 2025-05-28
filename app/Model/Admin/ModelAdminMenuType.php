<?php
declare(strict_types=1);

namespace App\Model\Admin;

use App\Abstract\AbstractModel;

/**
 * 后台菜单类型表
 *
 * @ModelAdminMenuType
 * @\App\Model\Admin\ModelAdminMenuType
 *
 * @property int    $id    主键
 * @property string $label 菜单类型名称
 */
final class ModelAdminMenuType extends AbstractModel
{
	protected ?string $table = 'base_menu_type';

	protected array $fillable = [
		'id',
		'label',
	];

	protected array $casts = [
		'id'    => 'integer',
		'label' => 'string',
	];
}