<?php
declare(strict_types=1);

namespace App\Abstract;

use App\Exception\CustomMessageException;
use App\Utils\ArrayFunction;
use App\Utils\Functions;
use Hyperf\Database\ConnectionInterface;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Model\Model;
use Hyperf\Stringable\Str;
use RuntimeException;

/**
 * @AbstractDao
 * @\App\Abstract\AbstractDao
 *
 * @property-read ConnectionInterface $getConnection Get the database connection for the model.
 * @property-read Model               $newInstance   Create a new instance of the given model.
 * @property-read Builder             $newQuery      Get a new query builder for the model's table.
 * @property-read Builder             $newModelQuery Get a new query builder that doesn't have any global scopes or
 *                                                   eager loading.
 */
abstract readonly class AbstractDao
{
	public function __construct(private AbstractModel $model)
	{
	}

	/**
	 * Delete administrator by ID list.
	 *
	 * @param array|int $ids
	 *
	 * @return bool
	 */
	final public function delete(array|int $ids): bool
	{
		$dataset = [];

		$ids = is_array($ids) ? $ids : func_get_args();
		$key = $this->model->getKeyName();

		foreach ($this->model->newQuery()->whereIn($key, $ids)->get() as $model) {
			if (!$model->delete()) {
				$dataset[] = $model->get($key);
			}
		}

		if (count($ids) > 1 && !empty($dataset)) {
			throw new CustomMessageException('ID为 %s的数据删除失败，请刷新后重试以检查数据是否存在！');
		}

		return !empty($dataset);
	}

	/**
	 * @param array $inputs
	 *
	 * @return bool
	 */
	final public function save(array $inputs): bool
	{
		$primaryKey = $this->model->getKeyName();

		$id = $inputs[$primaryKey] ?? null;

		/* @var Model|null $model */
		$model = is_null($id)
			? $this->model->newInstance()
			: $this->model->newQuery()->find($id);

		if (is_null($model)) {
			throw new CustomMessageException('数据不存在，请刷新后重试！');
		}

		return $model->fill($inputs)->save();
	}

	/**
	 * @param array $search
	 * @param array $sorts
	 * @param int   $currentPage
	 * @param int   $perPage
	 *
	 * @return array
	 */
	public function table(array $search, array $sorts, int $currentPage, int $perPage): array
	{
		$query = $this->newQuery;

		foreach ($search as $field => $value) {
			if (!is_string($field)) continue;

			if (method_exists($this->model, $field)) {
				if (!is_array($value)) continue;
				$related = $this->model->getRelation($field);

				$query = $query->whereHas($field, function ($query) use ($related, $value) {
					/* @var AbstractModel $model */
					$model  = $related->newQuery()->getModel();
					$search = $value;

					foreach ($search as $field => $value) {
						if (!$model->isFillable($field)) continue;
						if (!$model->hasCast($field)) continue;

						$type  = $this->model->getCastType($field);
						$type  = strpos($type, ':') ? strstr($type, ':', true) : $type;
						$query = match ($type) {
							'boolean',
							'integer'  => $query->where($field, $value),
							'string'   => $query->where($field, 'like', "%$value%"),
							'datetime' => $query->whereBetween($field, $value),
							default    => $query,
						};
					}
				});
			}
			if ($this->model->isFillable($field)) {
				$field = Str::snake($field);

				if (!$this->model->hasCast($field)) continue;

				$type = $this->model->getCastType($field);
				$type = strpos($type, ':') ? strstr($type, ':', true) : $type;

				$query = match ($type) {
					'boolean',
					'integer'  => $query->where($field, $value),
					'string'   => $query->where($field, 'like', "%$value%"),
					'datetime' => $query->whereBetween($field, $value),
					default    => $query,
				};
			}
		}

		$total = $query->count();

		foreach ($sorts as $field => $direction) {
			if (
				$this->model->isFillable($field)
				&& in_array($direction, [
					'asc',
					'desc',
					'ASC',
					'DESC',
				],          true)
			) continue;
			$query = is_int($field) ? $query->orderBy($field) : $query->orderBy($field, $direction);
		}
		$query = $query
			->orderByDesc($this->model->getKeyName())
			->offset(($currentPage - 1) * $perPage)
			->limit($perPage);

		$list = $query->get()->toArray();

		return compact('total', 'list');
	}

	/**
	 * @param string $name
	 * @param array  $arguments
	 *
	 * @return mixed
	 */
	final public function __call(string $name, array $arguments): mixed
	{
		if (method_exists($this->model, $name)) {
			return call_user_func_array(
				[
					$this->model,
					$name,
				], $arguments,
			);
		}

		throw new RuntimeException("Method $name does not exist");
	}

	/**
	 * @param string $name
	 *
	 * @return Builder|Model|ConnectionInterface
	 */
	final public function __get(string $name): Builder|Model|ConnectionInterface
	{
		return match ($name) {
			'getConnection' => $this->model->getConnection(),
			'newQuery'      => $this->model->newQuery(),
			'newInstance'   => $this->model->newInstance(),
			'newModelQuery' => $this->model->newModelQuery(),
			default         => throw new RuntimeException("Property $name does not exist"),
		};
	}
}