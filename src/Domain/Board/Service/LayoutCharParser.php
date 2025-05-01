<?php

namespace Chess\Domain\Board\Service;

use Chess\Domain\Piece\Entity\Bishop;
use Chess\Domain\Piece\Entity\King;
use Chess\Domain\Piece\Entity\Knight;
use Chess\Domain\Piece\Entity\Pawn;
use Chess\Domain\Piece\Entity\Queen;
use Chess\Domain\Piece\Entity\Rook;

/**
 * Interprets chars representing pieces into their respective class string
 */
class LayoutCharParser
{
	public function __construct(public string $char)
	{}

	public function getType(): string
	{
		return match(strtolower($this->char)) {
			'r' => Rook::class,
			'b' => Bishop::class,
			'n' => Knight::class,
			'q' => Queen::class,
			'k' => King::class,
			'p' => Pawn::class,
			'_' => 'Empty',
			default => new \Exception('Invalid piece: ' . $this->char),
		};
	}

	/**
	 * Determines if the piece is white or black.
	 * White pieces use a capital letter (`C`)
	 * Black pieces use a lowercase letter (`c)`
	 *
	 * @return string Either `white` or `black`
	 */
	public function getColor(): string
	{
		return strtoupper($this->char) === $this->char ? 'white' : 'black';
	}
}