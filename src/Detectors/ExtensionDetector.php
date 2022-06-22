<?php
declare(strict_types=1);

namespace Nalgoo\MimeType\Detectors;

use Nalgoo\MimeType\MimeTypeDetector;
use Nalgoo\MimeType\Utils;

class ExtensionDetector implements MimeTypeDetector
{
	/**
	 * @var array
	 */
	private $map;

	public function __construct(array $map = null)
	{
		if (is_null($map)) {
			$this->map = require(__DIR__ . '/../../data/ext-to-type.php');
		} else {
			$this->map = $map;
		}
	}

	public function detectFromBuffer(string $buffer, ?string $path): ?string
	{
		if ($path && array_key_exists($ext = Utils::getExtension($path), $this->map)) {
			return $this->map[$ext];
		}

		return null;
	}
}
