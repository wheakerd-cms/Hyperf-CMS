<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Abstract\AbstractService;
use App\Dao\Admin\DaoAdminRoles;

/**
 * @RolesService
 * @\App\Service\Admin\RolesService
 */
final readonly class RolesService extends AbstractService
{
	public function __construct(DaoAdminRoles $dao)
	{
		parent::__construct($dao);
	}
}