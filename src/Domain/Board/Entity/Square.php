<?php

namespace Chess\Domain\Board\Entity;

use Chess\Domain\Board\Service\ColNumberToChar;
use Chess\Domain\Piece\Entity\Piece;
use Chess\Domain\Piece\ValueObject\Enums\PieceType;

class Square
{
	/**
	 * Piece coordinates in algebraic notation, e.g. `'e4'`
	 */
	public readonly string $algebraic;

	/**
	 * Piece coordinates in coordinate notation, e.g. `[7,0]`.
	 *
	 * @var Coordinate $coords
	 */
	public readonly Coordinate $coords;

	/**
	 * ID of the square, counting from the top left corner `[a1 => id=1]`
	 */
	public readonly int $id;

	/**
	 * Piece currently on the square. Can also be `null`, meaning an empty square.
	 */
	protected ?Piece $piece = null;

	public function __construct(int $row, int $col)
	{
		static $id = 1;
		$this->id = $id; $id++;

		$this->coords = new Coordinate($row, $col);
		$this->algebraic = $this->coords->algebraic();
	}

	public function __clone(): void
	{
		if($this->hasPiece()) {
			$this->setPiece(clone $this->piece);
		}
		else {
			$this->setPiece(null);
		}
	}

	public function column(bool $asCoordinate = false): string|int
	{
		return $asCoordinate
			? $this->coords->col
			: $this->coords->algebraicCol();
	}

	public function row(bool $asCoordinate = false): string|int
	{
		return $asCoordinate
			? $this->coords->row
			: $this->coords->algebraicRow();
	}

	public function hasPiece(): bool
	{
		if(! $this->piece()) {
			return false;
		}
		return is_subclass_of($this->piece(), Piece::class);
	}

	public function isCheckingEnemyKing(): bool
	{
		return false; //TODO: finish logic for enemy being checked
	}

	public function piece(): ?Piece
	{
		return $this->piece;
	}

	public function setPiece(?Piece $piece): void
	{
		$this->piece = $piece;
	}

	public function getCoords(): Coordinate
	{
		return $this->coords;
	}

	/**
	 * @return string Gives a formatted piece name. If the Square is empty, returns string `Empty`.
	 */
	public function pieceName(): string
	{
		return $this->piece
			? $this->piece()->name
			: 'Empty';
	}
}