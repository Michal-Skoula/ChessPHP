<?php

namespace Chess\Domain\Move\Service;

use Chess\Domain\Board\Entity\ChessBoard;
use Chess\Domain\Board\Entity\Coordinate;
use Chess\Infrastructure\Logging\Logger;

// TODO: If in the future the types `Coordinate|string` bites me in the ass make two methods one for string the other for coordinates

class MovesAsArrayService
{
	protected ChessBoard $board;
	protected Coordinate $from;

	public function __construct(ChessBoard $board, Coordinate $pieceCoords)
	{
		$this->board = $board;
		$this->from = $pieceCoords;
	}

	/**
	 * Returns moves that could happen on the chess board
	 * from all geometries assuming no chess rules are present
	 * @return array{
	 *   'moves': array<Coordinate|string>,
	 *   'attacks': array<Coordinate|string>
	 * }
	 */
	public function getAllMoves(bool $asAlgebraic = false): array
	{
		$moves = [];
		$attacks = [];
		$pieceBeingMoved = $this->board->getPiece($this->from);

		foreach ($pieceBeingMoved->moveGeometries as $direction)
		{
			$squaresFound = $this->allMovesInDirection(
				moveVector: $direction,
				repetitions: $pieceBeingMoved->moveRepetitions,
				asAlgebraic: $asAlgebraic
			);

			$moves = array_merge($moves, $squaresFound['moves']);
		}

		foreach ($pieceBeingMoved->attackGeometries as $direction)
		{
			$squaresFound = $this->allMovesInDirection(
				moveVector: $direction,
				repetitions: $pieceBeingMoved->attackRepetitions,
				attackingMovesOnly: true,
				asAlgebraic: $asAlgebraic
			);

			$attacks = array_merge($attacks, $squaresFound['attacks']);
		}

		return [
			'moves' => array_unique($moves),
			'attacks' => array_unique($attacks)
		];
	}

	/**
	 * @param  array<int,int>  $moveVector  Shape of the vector, structured as `[row, col]`
	 * @param  int  $repetitions  Number of repetitions for the move. `-1` for infinity.
	 * @param  bool  $attackingMovesOnly  Whether to return moves or attacks
	 * @param  bool  $asAlgebraic  Mainly for testing, returns move in algebraic notation instead of Coordinate object
	 * @return array{
	 *   'moves': array<Coordinate|string>,
	 *   'attacks': array<Coordinate|string>,
	 * }
	 */
	protected function allMovesInDirection(
		array $moveVector,
		int $repetitions,
		bool $attackingMovesOnly = false,
		bool $asAlgebraic = false
	): array
	{
		$pieceBeingMoved = $this->board->getPiece($this->from);

		// Setup
		$mvCoords = [];
		$atkCoords = [];

		$rIterator = $pieceBeingMoved->color === 'white' ? $moveVector[0] * -1 : $moveVector[0];
		$cIterator = $pieceBeingMoved->color === 'white' ? $moveVector[1] * -1 : $moveVector[1];

		$currCoords = Coordinate::fromNums(
			row: $this->from->row + $rIterator,
			col: $this->from->col + $cIterator
		);

		// Computing moves
		for ($i = 0; $i < $repetitions || $repetitions === -1; $i++)
		{
			if ($this->board->isSquareInBoard($currCoords))
			{
				$pieceOnCurrCoords = $this->board->getPiece($currCoords);

				if ($pieceOnCurrCoords === null)
				{
					// Square is empty; a move

					if (!$attackingMovesOnly) {
						$mvCoords[] = $asAlgebraic ? $currCoords->algebraic() : $currCoords;
					}
				}
				else if ($pieceOnCurrCoords->color !== $pieceBeingMoved->color)
				{
					// Square has piece of opposite color; an attack

					if ($attackingMovesOnly) {
						$atkCoords[] = $asAlgebraic ? $currCoords->algebraic() : $currCoords;
					}
					break;

				}
				else
				{
					// Piece is the same color; friendly fire

					break;
				}

				$currCoords = Coordinate::fromNums(
					row: $currCoords->row + $rIterator,
					col: $currCoords->col + $cIterator
				);
			}
			else
			{
				// Outside the chess board
				break;
			}
		}

		return [
			'moves' => $mvCoords,
			'attacks' => $atkCoords,
		];
	}

	/**
	 * @param  array{
	 *     'moves':array<Coordinate|string>,
	 *     'attacks':array<Coordinate|string>
	 * }  $coordinates
	 * @return void
	 */
	public static function print(array $coordinates): void
	{
		echo "\n\nMoves\n";
		foreach ($coordinates['moves'] as $move) echo "$move ";
		echo "\nAttacks\n";
		foreach ($coordinates['attacks'] as $move) echo "$move ";
		echo "\n\n";

	}
}