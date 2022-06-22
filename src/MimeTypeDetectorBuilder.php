<?php
declare(strict_types=1);

namespace Nalgoo\MimeType;

use Nalgoo\MimeType\Detectors\ChainDetector;
use Nalgoo\MimeType\Detectors\BufferLimit;
use Nalgoo\MimeType\Detectors\ExtensionDetector;
use Nalgoo\MimeType\Detectors\FinfoDetector;
use Nalgoo\MimeType\Detectors\MagicNumberDetector;

final class MimeTypeDetectorBuilder
{
	public static function create(): MimeTypeDetector
	{
		$detectors = [
			function_exists('finfo_open') ? new BufferLimit(new FinfoDetector()) : null,
			new MagicNumberDetector(),
			new ExtensionDetector(),
		];

		return new ChainDetector(array_filter($detectors));
	}
}
