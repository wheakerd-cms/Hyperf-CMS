<?php
declare(strict_types=1);

return [
	'http' => [
		Hyperf\Session\Middleware\SessionMiddleware::class,
		App\Middleware\CrossDomain\AppCrossDomainMiddleware::class,
	],
];