<?php

namespace Chess\Domain\Board\Service;


use Chess\Domain\Piece\Exception\InvalidPieceException;
use Chess\Domain\Piece\ValueObject\Piece;

/**
 * Interprets chars representing pieces into their respective class string based on the `Piece` enum definitions
 */
class LayoutCharParser
{
	public function __construct(
		public string $char
	) {}

	/**
	 * @throws InvalidPieceException
	 */
	public function getType(): string
	{
		return Piece::tryFrom(strtolower($this->char))
				?->getClass()
				?? throw new InvalidPieceException("Invalid piece char: $this->char. Defaulting to null.");
	}

	/**
	 * Determines if the piece is white or black.
	 *
	 * White pieces use a capital letter (`R`),
	 * black pieces use a lowercase letter (`r`)
	 *
	 * @return string Either `white` or `black`
	 */
	public function getColor(): string
	{
		return strtoupper($this->char) === $this->char ? 'white' : 'black';
	}
}