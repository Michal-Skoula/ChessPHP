<?php

namespace Chess\Domain\Board\Entity;

use Chess\Domain\Board\Service\ColNumberToChar;
use Chess\Domain\Piece\Entity\AbstractPiece;

class Square
{
	/**
	 * Piece coordinates in chess notation, e.g. `'e4'`
	 */
	public readonly string $chess;
	/**
	 * Piece coordinates in algebraic notation, e.g. `[7,4]`
	 */
	public readonly array $algebraic;

	/**
	 * Id of the square, counting from the top left corner `[a1 => id=1]`
	 */
	public readonly int $id;

	/**
	 * Piece currently on the square. Can also be `null`, meaning an empty square.
	 */
	public ?AbstractPiece $piece = null {
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

		// Chess notation
		$columnChar = ColNumberToChar::convert($col);
		$rowInt = $row+1;
		$this->chess = "{$columnChar}{$rowInt}";

		// Algebraic notation
		$this->algebraic = ['r' => $row, 'c' => $col];
	}

	public function getChessNotationColumn(): string
	{
		return $this->chess[0];
	}

	public function getChessNotationRow(): int {
		return $this->chess[1];
	}

}