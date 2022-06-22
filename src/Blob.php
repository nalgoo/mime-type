<?php
declare(strict_types=1);

namespace Nalgoo\MimeType;

class Blob
{
	/**
	 * @var string
	 */
	private $data;

	public function __construct(string $data)
	{
		$this->data = $data;
	}

	public static function fromBinary(string $data): Blob
	{
		return new self($data);
	}

	public function offset(int $offset, int $length = null): Blob
	{
		// emulating php8+ functionality
		if (is_null($length)) {
			return new self(substr($this->data, $offset));
		}

		return new self(substr($this->data, $offset, $length));
	}

	public function startsWith(...$args): bool
	{
		$needle = $this->getStr($args);

		/** @noinspection SubStrUsedAsStrPosInspection should be faster then strpos */
		return substr($this->data, 0, strlen($needle)) === $needle;
//		return strpos($this->data, $needle) === 0;
	}

	public function seekTo(...$args): ?Blob
	{
		$needle = $this->getStr($args);

		$pos = strpos($this->data, $needle);

		if ($pos !== false) {
			return $this->offset($pos);
		}

		return null;
	}

	private function getStr(array $args): string
	{
		if (count($args) === 0) {
			throw new \InvalidArgumentException('Expecting at least one parameter');
		}

		$str = implode('', array_map(function ($a) {
			return is_int($a) ? chr($a) : $a;
		}, $args));

		return $str;
	}

	public function contains(...$args): bool
	{
		$needle = $this->getStr($args);

		return strpos($this->data, $needle) !== false;
	}
}
