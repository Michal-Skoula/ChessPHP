<?php

namespace Chess\Domain\Piece\Entity;

class Rook extends Piece
{
	// Info
	public string $name = "Rook";
	public int $value = 5;
	public array $icon = [
		'white' => '♖',
		'black' => '♜',
	];

	// Movement

	/**
	 * @var array<array<int,int>> Array of allowed move geometries.
	 */
	public array $moveGeometries = [
		[0, 1], [1, 0], [0, -1], [-1, 0]
	];

	/**
	 * How many times each move can be repeated. `-1` for infinite.
	 *
	 * Pieces like the knight or king can only move once in their shape *(L for the knight)*,
	 * but the rook, or bishop can move infinitely to the sides or on a d-pad
	 */
	public int $moveRepetitions = -1;

	/**
	 * @var array<array<int,int>> Array of allowed attack geometries.
	 *
	 * If empty, it is the same as `$moves`.
	 */
	public array $attackGeometries = [];

	/**
	 * Amount of times each move can be repeated. `-1` for infinite
	 *
	 * This behaviour doesn't happen in normal chess, as each piece can only attack once `1`.
	 * Reserved for custom pieces, e.g. multiple captures in checkers.
	 */
	public int $attackRepetitions = -1;

	/**
	 * @var array<string> Name(s) of functions that handle special move logic *like castling*.
	 */
	public array $specialMoves = [];

	/**
	 * @var array<string> Name(s) of functions that handle special attack logic *like en passant*.
	 */
	public array $specialAttackMoves = [];
}