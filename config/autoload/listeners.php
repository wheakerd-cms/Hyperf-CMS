<?php
declare(strict_types=1);

return [
	Hyperf\Command\Listener\FailToHandleListener::class,
	Hyperf\ExceptionHandler\Listener\ErrorExceptionHandler::class,
	Hyperf\DbConnection\Listener\InitTableCollectorListener::class,
];