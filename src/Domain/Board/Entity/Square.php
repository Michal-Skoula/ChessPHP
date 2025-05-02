<?php

namespace Chess\Domain\Board\Entity;

use Chess\Domain\Board\Service\ColNumberToChar;
use Chess\Domain\Piece\Entity\AbstractPiece;

class Square
{
	/**
	 * Piece coordinates in algebraic notation, e.g. `'e4'`
	 */
	public readonly string $algebraic;

	/**
	 * Piece coordinates in coordinate notation, e.g. `[8,4]. Indexes start at 1.`
	 */
	public readonly array $coords;

	/**
	 * Id of the square, counting from the top left corner `[a1 => id=1]`
	 */
	public readonly int $id;

	/**
	 * Piece currently on the square. Can also be `null`, meaning an empty square.
	 */
	protected ?AbstractPiece $piece = null {
		get {
			return $this->piece;
		}
		set {
			$this->piece = $value;
		}
	}

	public function __construct(int $row, int $col)
	{
		// Id
		static $id = 1;
		$this->id = $id; $id++;

		// Algebraic notation
		$columnChar = ColNumberToChar::convert($col);
		$rowInt = $row + 1;
		$this->algebraic = "{$columnChar}{$rowInt}";

		// Coordinates notation
		$this->coords = ['r' => $row, 'c' => $col];
	}

	public function getAlgebraicColumn(): string
	{
		return $this->algebraic[0];
	}

	public function getAlgebraicRow(): int {
		return $this->algebraic[1];
	}

}