<?php

namespace Chess\Domain\Piece\Entity;
use Chess\Domain\Piece\Service\GetMoveFromArrayNotation;
use Chess\Domain\Piece\Service\GetMoveFromChessNotation;

abstract class AbstractPiece
{
	// Info
	public string $name = "Unlabeled piece";
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
	}

	public function play(string|array $play)
	{
		$move = match(gettype($play)) {
			'string' => GetMoveFromChessNotation::convert($play),
			'array' => GetMoveFromArrayNotation::convert($play),
		};

		if(! $move->isValid()) {
			echo 'Move is invalid';
		}

		if($move->isPawnPromotion()) {

		}
	}
 }

 // TODO: when implementing move logic, invert the $col value for black