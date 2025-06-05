<?php
declare(strict_types=1);

namespace App\Listener;

use App\Abstract\AbstractMigration;
use Hyperf\Contract\ContainerInterface;
use Hyperf\Database\ConnectionResolverInterface;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;
use Hyperf\Di\ReflectionManager;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;
use Hyperf\Support\Composer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Throwable;

/**
 * 系统引导
 *
 * @SystemGuideListener
 * @\App\Listener\SystemGuideListener
 */
#[Listener(priority: 999)]
final readonly class SystemGuideListener implements ListenerInterface
{
	public function __construct(private ContainerInterface $container)
	{
	}

	/**
	 * @return string[]
	 */
	public function listen(): array
	{
		return [
			BootApplication::class,
		];
	}

	/**
	 * @param object $event
	 *
	 * @return void
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function process(object $event): void
	{
		if (!file_exists(ROOT_PATH . '/.env')) {
			throw new RuntimeException('The. env configuration file doesnt exist');
		}

		/** @noinspection PhpPossiblePolymorphicInvocationInspection */
		$this->container->get(ConnectionResolverInterface::class)->connection('default')->getSchemaBuilder()->dropAllTables();

		$migrations = $this->getAllMigrations();

		foreach ($migrations as $migration) {
			/* @phpstan-var AbstractMigration $schema */
			$schema = $this->container->get($migration);

			Schema::create($schema->table(), fn(Blueprint $blueprint) => $schema->schema($blueprint));

			foreach ($schema->data() as $item) {
				$instance = $schema->newInstance($item);
				$instance->save();
			}
		}
	}

	private function getAllMigrations(): array
	{
		$migrations = [];

		$classMap = array_keys(Composer::getLoader()->getClassMap());

		foreach ($classMap as $classname) {
			try {
				if (!class_exists($classname)) {
					continue;
				}
				if (!ReflectionManager::reflectClass($classname)->isSubclassOf(AbstractMigration::class)) {
					continue;
				}

				$migrations [] = $classname;
			}
			catch (Throwable) {
				continue;
			}
		}

		return $migrations;
	}
}