<?php
declare(strict_types=1);

namespace App\Exception;

use Hyperf\Server\Exception\ServerException;
use Throwable;

/**
 * Thrown a custom exception
 *
 * @CustomMessageException
 * @\App\Exception\CustomMessageException
 */
final class CustomMessageException extends ServerException
{
	public function __construct(string $message, int $code = 404, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}