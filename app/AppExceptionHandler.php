<?php
declare(strict_types=1);

namespace App;

use App\Contract\ResponseContract;
use App\Exception\CustomMessageException;
use App\Exception\LibraryException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\Annotation\ExceptionHandler;
use Hyperf\ExceptionHandler\ExceptionHandler as HyperfExceptionHandler;
use Hyperf\HttpMessage\Exception\MethodNotAllowedHttpException;
use Hyperf\HttpMessage\Exception\NotFoundHttpException;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use function Hyperf\Support\env;
use function sprintf;

/**
 * @AppExceptionHandler
 * @\App\Exception\Handler\AppExceptionHandler
 */
#[ExceptionHandler]
final class AppExceptionHandler extends HyperfExceptionHandler
{
	private readonly LoggerInterface $logger;

	public function __construct(
		LoggerFactory                          $loggerFactory,
		private readonly StdoutLoggerInterface $stdoutLogger,
		private readonly ResponseContract      $response,
	)
	{
		$this->logger = $loggerFactory->get();
	}

	public function isValid(Throwable $throwable): bool
	{
		return true;
	}

	public function handle(Throwable $throwable, ResponseInterface $response): MessageInterface|ResponseInterface
	{
		$logger = $this->getLogger();

		$this->stopPropagation();
		$logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
		$logger->error($throwable->getTraceAsString());
		$logger->error($throwable);

		return match (true) {
			$throwable instanceof MethodNotAllowedHttpException => $this->handleMethodNotAllowed($throwable),
			$throwable instanceof NotFoundHttpException         => $this->handleNotFound($throwable),
			$throwable instanceof ValidationException           => $this->handleValidator($throwable),
			$throwable instanceof LibraryException              => $this->handleLibrary($throwable),
			$throwable instanceof CustomMessageException        => $this->handleCustom($throwable),
			default                                             => $this->response->server('Internal Server Error.'),
		};
	}

	private function getLogger(): LoggerInterface|StdoutLoggerInterface
	{
		return env('APP_ENV', 'dev') === 'dev' ? $this->stdoutLogger : $this->logger;
	}

	public function handleNotFound(NotFoundHttpException $notFoundHttpException): MessageInterface
	{
		$message = $notFoundHttpException->getMessage();

		return $this->response->error($message);
	}

	/**
	 * The method is a validator exception handler.
	 *
	 * @param ValidationException $validationException
	 *
	 * @return MessageInterface
	 */
	public function handleValidator(ValidationException $validationException): MessageInterface
	{
		$message = $validationException->validator->errors()->first();

		return $this->response->validator($message);
	}

	/**
	 * The method is a custom exception handler.
	 *
	 * @param CustomMessageException $customMessageException
	 *
	 * @return ResponseInterface
	 */
	private function handleCustom(CustomMessageException $customMessageException): ResponseInterface
	{
		$message = $customMessageException->getMessage();

		return $this->response->error($message);
	}

	/**
	 * The method is a library exception handler.
	 *
	 * @param LibraryException $libraryException
	 *
	 * @return ResponseInterface
	 */
	private function handleLibrary(LibraryException $libraryException): ResponseInterface
	{
		$message = $libraryException->getMessage();

		return $this->response->error($message);
	}

	/**
	 * The exception handler is for methods not allowed.
	 *
	 * @param MethodNotAllowedHttpException $throwable
	 *
	 * @return ResponseInterface
	 */
	private function handleMethodNotAllowed(MethodNotAllowedHttpException $throwable): ResponseInterface
	{
		$message = $throwable->getMessage();

		return $this->response->message($message, 403);
	}
}