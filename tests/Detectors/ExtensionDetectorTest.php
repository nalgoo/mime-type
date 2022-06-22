<?php
declare(strict_types=1);

namespace Nalgoo\MimeType\Tests\Detectors;

use Nalgoo\MimeType\Detectors\ExtensionDetector;
use PHPUnit\Framework\TestCase;

class ExtensionDetectorTest extends TestCase
{
	private $detector;

	protected function setUp(): void
	{
		$this->detector = new ExtensionDetector();
	}

	/**
	 * @dataProvider getMap
	 */
	public function testDetectFromBuffer($path, $type): void
	{
		$this->assertSame($type, $this->detector->detectFromBuffer('', $path));
	}

	public function getMap(): array
	{
		return [
			['file.pdf', 'application/pdf'],
			['file.gif', 'image/gif'],
			['file.png', 'image/png'],
			['file.jpg', 'image/jpeg'],
			['file.jpeg', 'image/jpeg'],
			['file.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
		];
	}
}
