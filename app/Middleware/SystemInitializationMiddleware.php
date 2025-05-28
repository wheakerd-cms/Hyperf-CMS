<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @SystemInitializationMiddleware
 * @\App\Middleware\SystemInitializationMiddleware
 */
final class SystemInitializationMiddleware implements MiddlewareInterface
{
	/**
	 * @param ServerRequestInterface  $request
	 * @param RequestHandlerInterface $handler
	 *
	 * @return ResponseInterface
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$response = $handler->handle($request);

		return $response->withHeader('Access-Control-Allow-Origin', '*');
	}
}