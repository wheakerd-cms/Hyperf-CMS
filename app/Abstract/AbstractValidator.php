<?php
declare(strict_types=1);

namespace App\Abstract;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

/**
 * @AbstractValidator
 * @\App\Abstract\AbstractValidator
 */
abstract class AbstractValidator
{
	#[Inject]
	protected RequestInterface $request;

	#[Inject]
	protected ValidatorFactoryInterface $validatorFactory;
}