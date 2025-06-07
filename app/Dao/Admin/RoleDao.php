<?php
declare(strict_types=1);

namespace App\Dao\Admin;

use App\Abstract\AbstractDao;
use App\Exception\CustomMessageException;
use App\Model\Admin\RoleModel;
use Throwable;

/**
 * @RoleDao
 * @\App\Dao\Admin\RoleDao
 *
 * @property RoleModel $model
 */
final readonly class RoleDao extends AbstractDao
{
	public function __construct(RoleModel $model)
	{
		parent::__construct($model);
	}

	/**
	 * @param string     $name
	 * @param array|null $menuList
	 * @param int|null   $id
	 *
	 * @return bool
	 */
	public function saveByMenuList(string $name, ?array $menuList = null, ?int $id = null): bool
	{
		$manager = $this->newQuery->getConnection();
		$manager->beginTransaction();

		try {
			/* @var RoleModel $model */
			$model = is_null($id)
				? $this->newInstance
				: $this->newQuery->sharedLock()->find($id);

			if (is_null($model)) {
				throw new CustomMessageException('数据不存在，请刷新后重试！');
			}

			$model->name = $name;
			$model->save();

			$roleMenuRelation = $model->roleMenu();

			if (!is_null($id)) {
				$roleMenuRelation->delete();
			}

			$id ??= $model->id;

			//  重写
			$combined = array_map(
				fn($value) => [
					'roleId' => $id,
					'menuId' => $value,
				],
				$menuList ?? [],
			);

			$roleMenuRelation->createMany($combined);
			$manager->commit();
		}
		catch (Throwable) {
			$manager->rollBack();
			return false;
		}

		return true;
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