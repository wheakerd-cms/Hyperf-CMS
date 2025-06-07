<?php
declare(strict_types=1);

namespace App\Dao\Admin;

use App\Abstract\AbstractDao;
use App\Exception\CustomMessageException;
use App\Model\Admin\AdministratorModel;

/**
 * @AdministratorDao
 * @\App\Dao\Admin\AdministratorDao
 */
final readonly class AdministratorDao extends AbstractDao
{
	public function __construct(AdministratorModel $model)
	{
		parent::__construct($model);
	}

	public function delete(array|int $ids): bool
	{
		$dataset = [];

		$ids = is_array($ids) ? $ids : func_get_args();
		$key = $this->model->getKeyName();

		/* @var AdministratorModel $model */
		foreach ($this->model->newQuery()->whereIn($key, $ids)->get() as $model) {
			if ($model->isSystem) {
				continue;
			}

			/** @noinspection PhpUnhandledExceptionInspection */
			if (!$model->delete()) {
				$dataset[] = $model->get($key);
			}
		}

		if (count($ids) > 1 && !empty($dataset)) {
			throw new CustomMessageException(
				sprintf('ID为 [%s] 的数据删除失败，请刷新后重试以检查数据是否存在！', implode(',', $dataset)),
			);
		}

		return !empty($dataset);
	}

	public function table(array $search, array $sorts, int $currentPage, int $perPage): array
	{
		$search['isSystem'] = false;

		//  TODO: Force coverage of search criteria, even if this search term exists.
		return parent::table(...compact('search', 'sorts', 'currentPage', 'perPage'));
	}

	/**
	 * 通过用户名称查询用户信息（取出一条数据）
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return false|AdministratorModel|null
	 */
	public function getAdministrator(string $username, string $password): null|false|AdministratorModel
	{
		/* @var null|AdministratorModel $model */
		$model = $this->newQuery->where('username', $username)->first();

		if (is_null($model)) {
			return null;
		}

		if (!password_verify($password, $model->password)) {
			return false;
		}

		return $model;
	}

	public function getUserinfoById(int $userid): ?AdministratorModel
	{
		return $this->newQuery->find($userid);
	}
}