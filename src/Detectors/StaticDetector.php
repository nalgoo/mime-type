<?php
declare(strict_types=1);

namespace Nalgoo\MimeType\Detectors;

use Nalgoo\MimeType\MimeTypeDetector;

class StaticDetector implements MimeTypeDetector
{
	private const RETURNED_TYPE = 'application/octet-stream';

	/**
	 * @var string
	 */
	private $returnedType;

	public function __construct(string $returnedType = self::RETURNED_TYPE)
	{
		$this->returnedType = $returnedType;
	}

	public function detectFromBuffer(string $buffer, ?string $path): ?string
	{
		return $this->returnedType;
	}
}
