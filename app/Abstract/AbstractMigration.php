<?php
declare(strict_types=1);

namespace App\Abstract;

use Hyperf\Database\Migrations\Migration;

/**
 * @AbstractMigration
 * @\App\Abstract\AbstractMigration
 */
abstract class AbstractMigration extends Migration
{
	public function __construct(private readonly AbstractModel $model)
	{
	}

	abstract public function before(): void;

	abstract public function after(): void;
}