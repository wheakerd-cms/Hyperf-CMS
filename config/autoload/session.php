<?php
declare(strict_types=1);

return [
	'handler' => Hyperf\Session\Handler\RedisHandler::class,
	'options' => [
		'connection'       => 'default',
		'gc_maxlifetime'   => 60 * 60 * 24 * 30,
		'session_name'     => 'SWOOLE_SESSION_ID',
		'domain'           => '',
		'cookie_lifetime'  => 60 * 60 * 24 * 30,
		'cookie_same_site' => 'lax',
	],
];
