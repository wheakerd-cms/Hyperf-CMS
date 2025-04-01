<?php
declare (strict_types=1);

namespace App\Dao\Admin;

use App\Abstract\AbstractDao;
use App\Model\Admin\ModelAdminRouter;

/**
 * @DaoAdminRouter
 * @\App\Dao\Admin\DaoAdminRouter
 */
final readonly class DaoAdminRouter extends AbstractDao
{
	public function __construct(ModelAdminRouter $model)
	{
		parent::__construct($model);
	}

	public function getAllId(): array
	{
		return $this->newQuery->pluck('id')->toArray();
	}

	public function getSelectInId(array $routerIds): array
	{
		return $this->newQuery->find($routerIds)->toArray();
	}
}