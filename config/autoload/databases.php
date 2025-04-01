<?php
declare(strict_types=1);

use function Hyperf\Support\env;

return [
	'default' => [
		'driver'    => env('DB_DRIVER', 'mysql'),
		'host'      => env('DB_HOST', 'localhost'),
		'port'      => env('DB_PORT', 3306),
		'database'  => env('DB_DATABASE', 'database'),
		'username'  => env('DB_USERNAME', 'root'),
		'password'  => env('DB_PASSWORD', 123456),
		'charset'   => env('DB_CHARSET', 'utf8mb4'),
		'collation' => env('DB_COLLATION', 'utf8mb4_general_ci'),
		'prefix'    => env('DB_PREFIX', ''),
		'pool'      => [
			'min_connections' => (integer)env('DB_MIN_CONNECTIONS', 1),
			'max_connections' => (integer)env('DB_MAX_CONNECTIONS', 10),
			'connect_timeout' => (float)env('DB_CONNECT_TIMEOUT', 10.0),
			'wait_timeout'    => (float)env('DB_WAIT_TIMEOUT', 3.0),
			'heartbeat'       => (integer)env('DB_HEARTBEAT', -1),
			'max_idle_time'   => (float)env('DB_MAX_IDLE_TIME', 60),
		],
		'commands'  => [
			'gen:model' => [
				'path'             => 'app/Model',
				'force_casts'      => true,
				'inheritance'      => 'AbstractModel',
				'uses'             => App\Abstract\AbstractModel::class,
				'refresh_fillable' => true,
				'table_mapping'    => [],
				'with_comments'    => true,
				'property_case'    => Hyperf\Database\Commands\ModelOption::PROPERTY_CAMEL_CASE,
				'visitors'         => [
					Hyperf\Database\Commands\Ast\ModelRewriteInheritanceVisitor::class,
					Hyperf\Database\Commands\Ast\ModelRewriteKeyInfoVisitor::class,
					Hyperf\Database\Commands\Ast\ModelRewriteSoftDeletesVisitor::class,
				],
			],
		],
		'cache'     => [
			'handler'           => Hyperf\ModelCache\Handler\RedisHandler::class,
			'cache_key'         => 'mc:%s:m:%s:%s:%s',
			'prefix'            => 'default',
			'ttl'               => 3600 * 24,
			'empty_model_ttl'   => 3600,
			'load_script'       => true,
			'use_default_value' => true,
		],
	],
];