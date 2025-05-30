<?php
declare(strict_types=1);

return [
	'http' => [
		App\Middleware\CrossDomain\AppCrossDomainMiddleware::class,
		Hyperf\Validation\Middleware\ValidationMiddleware::class,
	],
];