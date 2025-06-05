<?php
declare(strict_types=1);

return [
	'handler' => Hyperf\Session\Handler\RedisHandler::class,
	'options' => [
		'connection'       => 'default',
		'gc_maxlifetime'   => 1200,
		'session_name'     => 'HYPERF_SESSION_ID',
		'domain'           => null,
		'cookie_lifetime'  => 5 * 60 * 60,
		'cookie_same_site' => 'lax',
	],
];
