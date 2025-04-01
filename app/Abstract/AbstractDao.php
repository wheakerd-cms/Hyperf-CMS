<?php
declare(strict_types=1);

namespace App\Abstract;

use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Model\Model;
use RuntimeException;

/**
 * @AbstractDao
 * @\App\Abstract\AbstractDao
 *
 * @property-read Model         $newInstance      Create a new instance of the given model.
 * @property-read Builder       $newQuery         Get a new query builder for the model's table.
 * @property-read Model|Builder $newModelQuery    Get a new query builder that doesn't have any global scopes or eager
 *                                                loading.
 */
abstract readonly class AbstractDao
{
	/**
	 * @param Model $model
	 */
	public function __construct(private Model $model)
	{
	}

	final public function getPrimaryKey(): string
	{
		return $this->model->getKeyName();
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 * @see Model::isFillable
	 */
	final public function isFillable(string $key): bool
	{
		return $this->model->isFillable(...func_get_args());
	}

	/**
	 * @param string               $key
	 * @param null|string|string[] $types
	 *
	 * @return bool
	 * @see Model::hasCast
	 */
	final public function hasCast(string $key, string|array|null $types = null): bool
	{
		return $this->model->hasCast(...func_get_args());
	}

	/**
	 * @param string $key
	 *
	 * @return string
	 * @see Model::getCastType
	 */
	final public function getCastType(string $key): string
	{
		return $this->model->getCastType(...func_get_args());
	}

	final public function __get(string $name): Builder|Model
	{
		return match ($name) {
			'newQuery'      => $this->model->newQuery(),
			'newInstance'   => $this->model->newInstance(),
			'newModelQuery' => $this->model->newModelQuery(),
			default         => throw new RuntimeException("Property $name does not exist"),
		};
	}
}