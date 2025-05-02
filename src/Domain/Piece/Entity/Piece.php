<?php

namespace Chess\Domain\Piece\Entity;

use Chess\Domain\Piece\Exception\InvalidPieceException;
use Chess\Domain\Piece\ValueObject\Enums\PieceType;

abstract class Piece
{
	// Info
	public string $name = "Unlabeled piece";
	public PieceType $type = PieceType::EMPTY;
	public string $char;
	public int $value = 0;
	public array $icon = [
		'white' => '🞎',
		'black' => '■'
	];

	// Movement

	/**
	 * @var array<array<int,int>> Array of allowed move geometries.
	 */
	protected array $moves;

	/**
	 * How many times each move can be repeated. `-1` for infinite.
	 *
	 * Pieces like the knight or king can only move once in their shape *(L for the knight)*,
	 * but the rook, or bishop can move infinitely to the sides or on a d-pad
	 */
	protected int $moveRepetitions;

	/**
	 * @var array<array<int,int>> Array of allowed attack geometries.
	 *
	 * If empty, it is the same as `$moves`.
	 */
	protected array $attackMoves = [];

	/**
	 * Amount of times each move can be repeated. `-1` for infinite
	 *
	 * This behaviour doesn't happen in normal chess, as each piece can only attack once `1`.
	 * Reserved for custom pieces, e.g. multiple captures in checkers.
	 */
	protected int $attackMoveRepetitions = 1;

	/**
	 * @var array<string> Name(s) of functions that handle special move logic *like castling*.
	 */
	protected array $specialMoves = [];

	/**
	 * @var array<string> Name(s) of functions that handle special attack logic *like en passant*.
	 */
	protected array $specialAttackMoves = [];

	// Attributes assigned during the game

	public string $color;
	public ?int $lastMoved = null;


	public function __construct(string $color)
	{
		$this->color = $color;

		if($this->attackMoves = []) {
			$this->attackMoves = $this->moves;
		}

		$this->type = $this->getType();
		$this->char = $this->type->value;
	}

	public function isType(PieceType ...$types): bool
	{
		return array_any($types, fn($type) => $type === $this->getType());
	}

	public static function make(PieceType $type, string $color): ?Piece
	{
		$pieceType = $type->getClass();

		return $pieceType != "Empty"
			? new $pieceType($color)
			: null;
	}

	protected function getType(): ?PieceType
	{
		try {
			$type = PieceType::getTypeFromClass($this);
		}
		catch(InvalidPieceException) {
			$type = null;
		}

		return $type;
	}
}