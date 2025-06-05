<?php
declare(strict_types=1);

namespace App\Abstract;

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;

/**
 * @AbstractMigration
 * @\App\Abstract\AbstractMigration
 */
abstract class AbstractMigration extends Migration
{
	public function __construct(protected readonly AbstractModel $model)
	{
	}

	/**
	 * Return the model's table name.
	 *
	 * @return string
	 */
	public function table(): string
	{
		return $this->model->getTable();
	}

	public function newInstance(array $attributes = [], $exists = false): AbstractModel
	{
		return $this->model->newInstance(...func_get_args());
	}

	abstract public function schema(Blueprint $blueprint): void;

	/**
	 * If initialization data is required, it returns the array data used to create the table (must be a
	 * two-dimensional array), otherwise it returns an empty array.
	 *
	 * @return array
	 */
	abstract public function data(): array;
}