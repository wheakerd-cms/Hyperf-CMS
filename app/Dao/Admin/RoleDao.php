<?php
declare(strict_types=1);

namespace App\Dao\Admin;

use App\Abstract\AbstractDao;
use App\Model\Admin\RoleModel;

/**
 * @RoleDao
 * @\App\Dao\Admin\RoleDao
 */
final readonly class RoleDao extends AbstractDao
{
	public function __construct(RoleModel $model)
	{
		parent::__construct($model);
	}

	public function getRouters(int $id): array
	{
		/** @var RoleModel $routers */
		$routers = $this->newQuery->find($id);

		if (empty($routers) || !$routers->status) {
			return [];
		}

		return $routers->router;
	}
}