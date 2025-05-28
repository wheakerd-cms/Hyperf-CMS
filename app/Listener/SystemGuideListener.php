<?php
declare(strict_types=1);

namespace App\Listener;

use Hyperf\Contract\ContainerInterface;
use Hyperf\Database\ConnectionResolverInterface;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BeforeMainServerStart;
use Hyperf\Support\Composer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

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
			BeforeMainServerStart::class,
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
			throw new RuntimeException('The. env configuration file does not exist');
		}

		$this->container->get(ConnectionResolverInterface::class)->connection('default')->getSchemaBuilder()->dropAllTables();

		$migrations = $this->getAllMigrations();

		foreach ($migrations as $migration) {
			$schema = $this->container->get($migration);
			$schema->before();
			$schema->after();
		}
	}

	private function getAllMigrations(): array
	{
		return array_keys(
			array_filter(
				Composer::getLoader()->getClassMap(),
				fn($classname) => str_starts_with($classname, 'App\\Schema\\'),
				ARRAY_FILTER_USE_KEY,
			));
	}
}