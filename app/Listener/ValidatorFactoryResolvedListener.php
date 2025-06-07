<?php
declare(strict_types=1);

namespace App\Listener;

use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\Event\ValidatorFactoryResolved;

/**
 * @ValidatorFactoryResolvedListener
 * @\App\Listener\ValidatorFactoryResolvedListener
 */
#[Listener]
final class ValidatorFactoryResolvedListener implements ListenerInterface
{
	public function listen(): array
	{
		return [
			ValidatorFactoryResolved::class,
		];
	}

	public function process(object $event): void
	{
		/**  @var ValidatorFactoryInterface $validatorFactory */
		$validatorFactory = $event->validatorFactory;
		//  注册数组验证器：所有值都必须是整型
		$validatorFactory->extend('array_int', function (
			string $attribute,
			mixed  $value,
		) {
			if (!is_array($value)) {
				return false;
			}

			foreach ($value as $item) {
				if (is_int($item)) {
					continue;
				}
				return false;
			}

			return true;
		});
		$validatorFactory->replacer('array_int', function (string $message, string $attribute) {
			return str_replace(':array_int', $attribute, $message);
		});
	}
}