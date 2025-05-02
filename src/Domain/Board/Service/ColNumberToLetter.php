<?php

namespace Chess\Domain\Board\Service;

use Chess\Domain\Board\BoardException;

class ColNumberToLetter
{
	/**
	 * The last char before the lowercase alphabet starts. meaning that `chr(n + 1) = a`
	 */
	protected static int $startOfAsciiLowercaseAlphabet = 96;
	protected static int $lettersInAsciiAlphabet = 25;

	/**
	 * @throws BoardException
	 */
	public static function convert(int $num): string
	{
		$charsCount = self::$lettersInAsciiAlphabet;

		if($num > $charsCount) {
			throw new BoardException("The ASCII alphabet only has $charsCount letters. The {$num}th letter doesn't exist.");
		}

		return chr(self::$startOfAsciiLowercaseAlphabet + $num);
	}
}