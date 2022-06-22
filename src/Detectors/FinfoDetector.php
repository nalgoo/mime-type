<?php
declare(strict_types=1);

namespace Nalgoo\MimeType\Detectors;

use Nalgoo\MimeType\MimeTypeDetector;

class FinfoDetector implements MimeTypeDetector
{
	private const SAMPLE_SIZE = 4096;

	/**
	 * @var null|\finfo|resource
	 */
	private $finfo;

	public function detectFromBuffer(string $buffer, ?string $path): ?string
	{
		$finfo = $this->getInstance();

		return @$finfo->buffer($buffer) ?: null;
	}

	/**
	 * @return \finfo
	 */
	private function getInstance()
	{
		if (!$this->finfo) {
			$this->finfo = finfo_open(FILEINFO_MIME);
		}

		return $this->finfo;
	}

	public function __destruct()
	{
		if ($this->finfo) {
			finfo_close($this->finfo);
		}
	}
}
