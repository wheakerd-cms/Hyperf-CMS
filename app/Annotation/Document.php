<?php
declare(strict_types=1);

namespace App\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * Collect and provide API documentation.
 *
 * @Document
 * @\App\Annotation\Document
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class Document extends AbstractAnnotation
{
	public function __construct(?array $params = null)
	{
	}
}