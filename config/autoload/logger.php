<?php
declare(strict_types=1);

return [
	'default' => [
		'handler'   => [
			'class'       => Monolog\Handler\RotatingFileHandler::class,
			'constructor' => [
				'filename' => ROOT_PATH . '/runtime/logs/hyperf/hyperf.log',
				'level'    => Monolog\Level::Error,
				'maxFiles' => 0,
			],
		],
		'formatter' => [
			'class'       => Monolog\Formatter\LineFormatter::class,
			'constructor' => [
				'format'                => null,
				'dateFormat'            => 'Y-m-d H:i:s',
				'allowInlineLineBreaks' => true,
			],
		],
	],
	'sql'     => [
		'handler'   => [
			'class'       => Monolog\Handler\RotatingFileHandler::class,
			'constructor' => [
				'filename' => ROOT_PATH . '/runtime/logs/sql/sql.log',
				'level'    => Monolog\Level::Error,
				'maxFiles' => 0,
			],
		],
		'formatter' => [
			'class'       => Monolog\Formatter\LineFormatter::class,
			'constructor' => [
				'format'                => null,
				'dateFormat'            => 'Y-m-d H:i:s',
				'allowInlineLineBreaks' => true,
			],
		],
	],
	'library' => [
		'handler'   => [
			'class'       => Monolog\Handler\RotatingFileHandler::class,
			'constructor' => [
				'filename' => ROOT_PATH . '/runtime/logs/library/library.log',
				'level'    => Monolog\Level::Error,
				'maxFiles' => 0,
			],
		],
		'formatter' => [
			'class'       => Monolog\Formatter\LineFormatter::class,
			'constructor' => [
				'format'                => null,
				'dateFormat'            => 'Y-m-d H:i:s',
				'allowInlineLineBreaks' => true,
			],
		],
	],
];