<?php
declare(strict_types=1);

namespace Nalgoo\MimeType;

class Utils
{
	/**
	 * used instead of pathinfo, because we want ".something" file to not have an extension
	 */
	public static function getExtension(string $path): ?string
	{
		if (preg_match('/(?<=\w)\.+([^\/.]+)$/', $path, $matches)) {
			return $matches[1];
		}

		return null;
	}
}
