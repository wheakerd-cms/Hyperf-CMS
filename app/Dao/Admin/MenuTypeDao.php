<?php
declare(strict_types=1);

namespace App\Dao\Admin;

use App\Abstract\AbstractDao;
use App\Model\Admin\MenuTypeModel;

/**
 * @MenuTypeDao
 * @\App\Dao\Admin\MenuTypeDao
 */
final readonly class MenuTypeDao extends AbstractDao
{
	public function __construct(MenuTypeModel $model)
	{
		parent::__construct($model);
	}

	public function getSelect(): array
	{
		return $this->newQuery->select()->get()->toArray();
	}
}