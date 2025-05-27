<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Abstract\AbstractService;
use App\Dao\Admin\DaoAdminRoles;

/**
 * @RolesService
 * @\App\Service\Admin\RolesService
 */
final class RolesService extends AbstractService
{
	public function __construct(
		private readonly DaoAdminRoles $dao,
	)
	{
	}
}