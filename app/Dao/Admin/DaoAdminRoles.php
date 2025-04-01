<?php
declare(strict_types=1);

namespace App\Dao\Admin;

use App\Abstract\AbstractDao;
use App\Model\Admin\ModelAdminRoles;

/**
 * @DaoAdminRoles
 * @\App\Dao\Admin\DaoAdminRoles
 */
final readonly class DaoAdminRoles extends AbstractDao
{
	public function __construct(ModelAdminRoles $model)
	{
		parent::__construct($model);
	}

	public function getRouters(int $id): array
	{
		/** @var ModelAdminRoles $routers */
		$routers = $this->newQuery->find($id);

		if (empty($routers) || !$routers->status) {
			return [];
		}

		return $routers->router;
	}
}