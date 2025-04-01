<?php
declare(strict_types=1);

namespace App\Abstract;

use App\Exception\CustomMessageException;
use App\Utils\Functions;
use Closure;
use Hyperf\Stringable\Str;

/**
 * @AbstractService
 * @\App\Abstract\AbstractService
 */
abstract readonly class AbstractService
{
	public function __construct(private AbstractDao $dao)
	{
	}

	/**
	 * 删除
	 *
	 * @param integer[] $values
	 * @param bool      $force 是否强制删除
	 *
	 * @return bool
	 */
	final public function delete(array $values, bool $force = false): bool
	{
		$query = $this->dao->newQuery->whereIn($this->dao->getPrimaryKey(), $values);

		return $force ? (boolean)$query->forceDelete() : $query->delete();
	}

	/**
	 * 列表（也可作用于多级菜单、搜索菜单等）
	 *
	 * @param string        $parentKey
	 * @param int|null      $key
	 * @param string|null   $params 搜索项的json字符串
	 * @param array         $sorts
	 * @param array         $with
	 * @param callable|null $where
	 * @param array         $columns
	 *
	 * @return array
	 */
	final public function list(
		string    $parentKey,
		?int      $key = null,
		?string   $params = null,
		array     $sorts = [],
		array     $with = [],
		?callable $where = null,
		array     $columns = ['*'],
	): array
	{
		['list' => $list] = $this->getTable($params, $sorts, $with, $where, $columns);

		return Functions::listTree($list, $this->dao->getPrimaryKey(), $parentKey, $key);
	}

	/**
	 * 新增、编辑
	 *
	 * @param array $inputs
	 *
	 * @return bool
	 */
	final public function save(array $inputs): bool
	{
		$primaryKey = $this->dao->getPrimaryKey();

		$id = $inputs[$primaryKey] ?? null;

		if (is_null($id)) {
			return $this->dao->newInstance->fillable($inputs)->save();
		}

		$query = $this->dao->newQuery->find($id);

		if (is_null($query)) {
			throw new CustomMessageException('数据不存在，请刷新后重试！');
		}

		return $query->fill($inputs)->save();
	}

	/**
	 * 列表（仅作用于一级菜单）
	 *
	 * @param string|null   $params
	 * @param array         $sorts
	 * @param array         $with
	 * @param callable|null $where
	 * @param array         $columns
	 *
	 * @return array
	 */
	final public function select(
		?string   $params = null,
		array     $sorts = [],
		array     $with = [],
		?callable $where = null,
		array     $columns = ['*'],
	): array
	{
		['list' => $list] = $this->getTable(...func_get_args());

		return $list;
	}

	/**
	 * @param string|null   $params
	 * @param array         $sorts
	 * @param array         $with
	 * @param callable|null $where
	 * @param array         $columns
	 *
	 * @return array
	 */
	public function table(
		?string   $params = null,
		array     $sorts = [],
		array     $with = [],
		?callable $where = null,
		array     $columns = ['*'],
	): array
	{
		return $this->getTable(...func_get_args() + [true]);
	}

	/**
	 * 表格
	 *
	 * @param string|null   $params
	 * @param array         $sorts
	 * @param array         $with
	 * @param callable|null $where
	 * @param array         $columns
	 *
	 * @return array
	 */
	private function getTable(
		?string   $params = null,
		array     $sorts = [],
		array     $with = [],
		?callable $where = null,
		array     $columns = ['*'],
	): array
	{
		$search      = [];
		$perPage     = 1;
		$currentPage = 20;

		//  如果存在搜索条件，转换统一数据格式
		if (!is_null($params)) {
			$params = json_decode($params, true);

			$currentPage = $params['currentPage'] ?? $currentPage;
			$perPage     = $params['perPage'] ?? $perPage;
			$search      = array_combine(array_map(fn($key) => Str::snake($key), array_keys($params)), $params);
		}

		if ($where instanceof Closure) {
			$where($query = $this->dao->newQuery);
		} else {
			$query = $this->dao->newQuery->where($where);
		}

		foreach ($search as $field => $value) {
			if (!$this->dao->isFillable($field)) continue;
			if (!$this->dao->hasCast($field)) continue;

			$type = $this->dao->getCastType($field);
			$type = strpos($type, ':') ? strstr($type, ':', true) : $type;

			$query = match ($type) {
				'boolean',
				'integer'  => $query->where($field, $value),
				'string'   => $query->where($field, 'like', "%$value%"),
				'datetime' => $query->whereBetween($field, $value),
				default    => $query,
			};
		}
		$query = $query->with($with);

		foreach ($sorts as $field => $direction) {
			$query = is_int($field) ? $query->orderBy($field) : $query->orderBy($field, $direction);
		}

		$total = $query->count();

		if (is_integer($currentPage) && is_integer($perPage)) {
			$query = $query->offset(($currentPage - 1) * $perPage)->limit($perPage);
		}

		$list = $query->get($columns)->toArray();

		return compact('total', 'list');
	}
}