<?php
declare (strict_types=1);

namespace App\Dao\Admin;

use App\Abstract\AbstractDao;
use App\Model\Admin\MenuModel;
use App\Utils\ArrayFunction;

/**
 * @MenuDao
 * @\App\Dao\Admin\MenuDao
 */
final readonly class MenuDao extends AbstractDao
{
	public function __construct(MenuModel $model)
	{
		parent::__construct($model);
	}

	/**
	 *
	 * @return array<integer>
	 */
	public function getList(): array
	{
		$list = $this->newQuery->get()->map(function (MenuModel $model) {
			$model['typeName'] = $model->type()->get()->where('id', $model->type)->value('name') ?? 'UNKNOWN';
			return $model;
		})->toArray();

		return ArrayFunction::listTree($list, 'id', 'parentId');
	}
}