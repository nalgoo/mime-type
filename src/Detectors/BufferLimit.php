<?php
declare(strict_types=1);

namespace Nalgoo\MimeType\Detectors;

use Nalgoo\MimeType\MimeTypeDetector;

class BufferLimit implements MimeTypeDetector
{
	private const SAMPLE_SIZE = 4096;

	/**
	 * @var MimeTypeDetector
	 */
	private $wrappedDetector;

	/**
	 * @var int
	 */
	private $sampleSize;

	public function __construct(MimeTypeDetector $mimeTypeDetector, int $sampleSize = self::SAMPLE_SIZE)
	{
		$this->wrappedDetector = $mimeTypeDetector;
		$this->sampleSize = $sampleSize;
	}

	public function detectFromBuffer(string $buffer, ?string $path): ?string
	{
		return $this->wrappedDetector->detectFromBuffer(substr($buffer, 0, $this->sampleSize), $path);
	}
}
