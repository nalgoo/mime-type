<?php
declare(strict_types=1);

namespace Nalgoo\MimeType\Detectors;

use Nalgoo\MimeType\Blob;
use Nalgoo\MimeType\MimeTypeDetector;

class MagicNumberDetector implements MimeTypeDetector
{
	public function detectFromBuffer(string $buffer, ?string $path): ?string
	{
		$blob = Blob::fromBinary($buffer);

		if ($type = $this->guessImageType($blob)) {
			return $type;
		}

		if ($type = $this->guessDocumentType($blob)) {
			return $type;
		}

		return 'application/octet-stream';
	}

	protected function guessImageType(Blob $blob): ?string
	{
		switch (true) {
			case $blob->startsWith(0xFF, 0xD8, 0xFF):
				return 'image/jpeg';
			case $blob->startsWith(0x89, 0x50, 0x4E, 0x47, 0x0D, 0x0A, 0x1A, 0x0A):
				return 'image/png';
			case $blob->startsWith(0x47, 0x49, 0x46):
				return 'image/gif';
			case $blob->offset(8)->startsWith(0x57, 0x45, 0x42, 0x50):
				return 'image/webp';
			case $blob->startsWith(0x46, 0x4C, 0x49, 0x46):
				return 'image/flif';
			case $blob->startsWith(0x49, 0x49, 0x2A, 0x0) || $blob->startsWith(0x4D, 0x4D, 0x0, 0x2A):
				return $blob->offset(8)->startsWith(0x43, 0x52)
					? 'image/x-canon-cr2' // .cr2
					: 'image/tiff';
			case $blob->startsWith(0x42, 0x4D):
				return 'image/bmp';
			case $blob->startsWith(0x49, 0x49, 0xBC):
				return 'image/vnd.ms-photo'; // .jxr
			case $blob->startsWith(0x38, 0x42, 0x50, 0x53):
				return 'image/vnd.adobe.photoshop';
		}

		return null;
	}

	protected function guessDocumentType(Blob $blob): ?string
	{
		// PDF
		if ($blob->startsWith('%PDF')) {
			return 'application/pdf';
		}

		// Zipped formats
		if ($zipHeaderName = $this->getNextZipHeaderName($blob)) {

			// open documents
			switch(true) {
				case $zipHeaderName->startsWith('mimetypeapplication/vnd.oasis.opendocument.text'):
					return 'application/vnd.oasis.opendocument.text';
				case $zipHeaderName->startsWith('mimetypeapplication/vnd.oasis.opendocument.spreadsheet'):
					return 'application/vnd.oasis.opendocument.spreadsheet';
				case $zipHeaderName->startsWith('mimetypeapplication/vnd.oasis.opendocument.presentation'):
					return 'application/vnd.oasis.opendocument.presentation';
			}

			// office open XML
			do {
				switch(true) {
					case $zipHeaderName->startsWith('word/'):
						return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
					case $zipHeaderName->startsWith('ppt/'):
						return 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
					case $zipHeaderName->startsWith('xl/'):
						return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
				}
			} while ($zipHeaderName = $this->getNextZipHeaderName($blob));
		}

		// MS OLE formats (doc, xls and other)
		if ($blob->startsWith(0xD0, 0xCF, 0x11, 0xE0, 0xA1, 0xB1, 0x1A, 0xE1)) {
			$trailer = $blob->offset(-8192);

			if ($trailer->contains('Microsoft Word 97-2003 Document')) {
				return 'application/msword';
			}

			if ($trailer->contains('Microsoft Excel')) {
				return 'application/msword';
			}
		}

		return null;
	}

	protected function getNextZipHeaderName(Blob $blob): ?Blob
	{
		$header = $blob->seekTo(0x50, 0x4B, 0x3, 0x4);

		return $header ? $header->offset(30) : null;
	}
}
