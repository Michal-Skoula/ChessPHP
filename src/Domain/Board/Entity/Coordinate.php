<?php

namespace Chess\Domain\Board\Entity;

use Chess\Domain\Board\Service\ColNumberToChar;

class Coordinate
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
	public function getArray(): array
	{
		return ['r' => $this->row, 'c' => $this->col];
	}

	public function getAlgebraic(): string
	{
		$row = $this->row;
		$col = $this->getAlgebraicCol();

		return "$col$row";
	}

	public function getAlgebraicCol(): string
	{
		return ColNumberToChar::toChar($this->col);
	}

	public static function fromCoords(int $row, int $col): Coordinate
	{
		return new self($row, $col);
	}

	public static function fromAlgebraic(string $notation): Coordinate
	{
		$col = ColNumberToChar::toInt($notation[0]);
		$row = (int)$notation[1];

		return new Coordinate($row, $col);
	}
}