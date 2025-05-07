<?php

namespace Chess\Domain\Board\Entity;

use Chess\Domain\Board\Service\ColNumberToChar;
use Stringable;

/**
 * The main way of communicating with the board about what square you want to exit
 */
readonly class Coordinate implements Stringable
{
	public int $row;
	public int $col;

	public function __construct(int $row, int $col)
	{
		$this->col = $col;
		$this->row = $row;
	}

	public function __toString(): string
	{
		return $this->algebraic();
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

	public static function fromNums(int $row, int $col): Coordinate
	{
		return new self($row, $col);
	}

	public static function fromAlgebraic(string $notation): Coordinate
	{
		$col = ColNumberToChar::toInt($notation[0]);
		$row = (int)substr($notation, 1) - 1;

		return new self($row, $col);
	}

	/**
	 * @param  array<int, int>  $rowCol
	 * @return Coordinate
	 */
	public static function fromArray(array $rowCol): Coordinate
	{
		return new self($rowCol[0], $rowCol[1]);
	}
}