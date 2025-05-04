<?php

namespace Chess\Domain\Board\Service;

class ColNumberToChar
{
	/**
	 * The last char before the lowercase alphabet starts. meaning that `chr(n + 1) = a`
	 */
	protected static int $startOfAsciiLowercaseAlphabet = 97;
	protected static int $lettersInAsciiAlphabet = 25;

	public static function toChar(int $num): string
	{
		return sprintf("%c", self::$startOfAsciiLowercaseAlphabet + $num - 1);
	}

	public static function toInt(string $char): int
	{
		return ord($char) - self::$startOfAsciiLowercaseAlphabet + 1;
	}
}