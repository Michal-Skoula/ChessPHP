<?php

namespace Chess\Domain\Board\Entity;

use Chess\Domain\Board\Service\ColNumberToChar;

/**
 * The main way of communicating with the board about what square you want to exit
 */
readonly class Coordinate
{
	public int $row;
	public int $col;

	public function __construct(int $row, int $col)
	{
		$this->col = $col;
		$this->row = $row;
	}

	/**
	 * @return array{'r': int, 'c': int} Returns an array `[row, col]`
	 */
	public function array(): array
	{
		return ['r' => $this->row, 'c' => $this->col];
	}

	public function algebraic(): string
	{
		$row = $this->row + 1; // a1 is [0,0]
		$col = $this->algebraicCol();

		return "$col$row";
	}

	public function algebraicCol(): string
	{
		return ColNumberToChar::toChar($this->col);
	}

	public function algebraicRow(): int
	{
		return $this->row + 1;
	}

	public static function fromCoords(int $row, int $col): Coordinate
	{
		return new self($row, $col);
	}

	public static function fromAlgebraic(string $notation): Coordinate
	{
		$col = ColNumberToChar::toInt($notation[0]);
		$row = (int)substr($notation, 1) - 1;

		return new Coordinate($row, $col);
	}
}