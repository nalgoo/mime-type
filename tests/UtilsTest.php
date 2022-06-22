<?php
declare(strict_types=1);

namespace Nalgoo\MimeType\Tests;

use Nalgoo\MimeType\Utils;
use PHPUnit\Framework\TestCase;

final class UtilsTest extends TestCase
{
	/**
	 * @dataProvider getExtensionMap
	 */
	public function testGetExtension($path, $extension): void
	{
		$this->assertSame($extension, Utils::getExtension($path));
	}

	public function getExtensionMap(): array
	{
		return [
			['file.html', 'html'],
			['file.HTML', 'HTML'],
			['.html', null],
			['..html', null],
			['/good.morning/file.html', 'html'],
			['/good.morning/.html', null],
			['./good.morning/file.html', 'html'],
			['./good.morning/.html', null],
			['hey.......html', 'html'],
			['file', null],
			['.', null],
			['.', null],
			['file.', null],
			['file.life.sup', 'sup'],
		];
	}
}
