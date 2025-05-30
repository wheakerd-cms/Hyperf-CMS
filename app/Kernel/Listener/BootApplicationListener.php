<?php
declare(strict_types=1);

namespace App\Kernel\Listener;

use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;
use Hyperf\Process\ProcessManager;
use Hyperf\Server\ServerManager;
use function pcntl_signal;

/**
 * @BootApplicationListener
 * @\App\Kernel\Listener\BootApplicationListener
 */
#[Listener]
final class BootApplicationListener implements ListenerInterface
{

	/**
	 * @return array
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
	 */
	public function process(object $event): void
	{
		pcntl_async_signals(true);
		pcntl_signal(SIGTERM, [
			$this,
			'onSignal',
		]);
	}

	public function register()
	{

	}

	public function onSignal(int $signal)
	{
//		var_dump('触发信号', $signal);
		ProcessManager::setRunning(false);

		foreach (ServerManager::list() as [$type, $server]) {
			$server->shutdown();
		}
	}
}