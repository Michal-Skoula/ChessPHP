<?php

namespace Chess\Domain\Board\Service;

class ColNumberToChar
{
	/*
	 * There are 25 ASCII lowercase alphabet characters a-z.
	 */

	/**
	 * Where `a` starts in the ASCII alphabet
	 */
	protected static int $startOfAsciiLowercaseAlphabet = 97;


	public static function toChar(int $num): string
	{
		return sprintf("%c", self::$startOfAsciiLowercaseAlphabet + $num);

	}

	/**
	 * Takes the ASCII number for the character and subtracts 97,
	 * which are all the previous characters, leaving the col number indexed from 0.
	 *
	 * @param  string  $char
	 * @return int
	 */
	public static function toInt(string $char): int
	{
		return ord($char) - self::$startOfAsciiLowercaseAlphabet;
	}
}