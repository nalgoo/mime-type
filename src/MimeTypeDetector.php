<?php
declare(strict_types=1);

namespace Nalgoo\MimeType;

interface MimeTypeDetector
{
	public function detectFromBuffer(string $buffer, ?string $path): ?string;
}
