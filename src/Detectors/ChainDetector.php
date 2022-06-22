<?php
declare(strict_types=1);

namespace Nalgoo\MimeType\Detectors;

use Nalgoo\MimeType\MimeTypeDetector;

class ChainDetector implements MimeTypeDetector
{
	private const INCONCLUSIVE_MIME_TYPES = [
		'application/x-empty',
		'text/plain',
		'text/x-asm',
		'application/octet-stream',
		'inode/x-empty',
	];

	/**
	 * @var MimeTypeDetector[]
	 */
	private $detectors;

	/**
	 * @var string[]
	 */
	private $inconclusiveMimeTypes;

	public function __construct(
		array $detectors,
		array $inconclusiveMimeTypes = self::INCONCLUSIVE_MIME_TYPES
	) {

		$this->detectors = $detectors;
		$this->inconclusiveMimeTypes = $inconclusiveMimeTypes;
	}

	public function detectFromBuffer(string $buffer, ?string $path): ?string
	{
		foreach ($this->detectors as $detector) {
			$maybeType = $detector->detectFromBuffer($buffer, $path);
			if ($maybeType && !in_array($maybeType, $this->inconclusiveMimeTypes, true)) {
				return $maybeType;
			}
		}

		return null;
	}
}
