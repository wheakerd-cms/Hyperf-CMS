<?php
declare(strict_types=1);

return [
	'http' => [
		App\Middleware\SystemInitializationMiddleware::class,
		App\Middleware\CrossDomain\AppCrossDomainMiddleware::class,
		Hyperf\Validation\Middleware\ValidationMiddleware::class,
	],
];