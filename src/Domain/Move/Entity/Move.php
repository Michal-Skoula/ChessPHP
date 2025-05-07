<?php

namespace Chess\Domain\Move\Entity;

use Chess\Domain\Board\Entity\ChessBoard;
use Chess\Domain\Board\Entity\Coordinate;
use Chess\Domain\Board\Entity\Square;
use Chess\Domain\Move\Exception\InvalidMoveException;
use Chess\Domain\Move\Exception\NoNextMoveException;
use Chess\Domain\Move\Exception\NoPreviousMoveException;
use Chess\Domain\Move\Service\ConvertMoveToAlgebraicNotation;
use Chess\Domain\Piece\Entity\Piece;
use Chess\Domain\Piece\ValueObject\Enums\PieceType;
use Chess\Infrastructure\Logging\Logger;
use Chess\Infrastructure\Logging\LogLevel;

/**
 * Stores a particular move and optionally the last and next move in the sequence
 */
final class Move
{
	public readonly ?int $id;
	public readonly string $algebraicNotation;
	public readonly string $movedBy;
	public readonly PieceType $pieceTypeMoved;
	public readonly ?PieceType $pieceTypeCaptured;
	public readonly Coordinate $from;
	public readonly Coordinate $to;
	public ChessBoard $state;
	protected ?Move $lastMove;
	protected ?Move $nextMove;

	/*
	 * =============================
	 *
	 *        Setting state
	 *
	 * =============================
	 */

	/**
	 * @throws InvalidMoveException
	 */
	public function __construct(ChessBoard $startingState, Coordinate $from, Coordinate $to, ?Move $lastMove = null)
	{
		$fromSquare = $startingState->getSquare($from);
		$toSquare = $startingState->getSquare($to);

		if(! $fromSquare->hasPiece()) {
			throw new InvalidMoveException("From square $fromSquare->algebraic has no piece. The move is invalid.");
		}

		$lastMove?->visualise();

		// Set move information
		$this->id = $this->setId();
		$this->from = $from;
		$this->to = $to;

		$this->state = $startingState;
		$this->movedBy = $fromSquare->piece()->color;
		$this->pieceTypeMoved = $fromSquare->piece()->type;
		$this->pieceTypeCaptured = $toSquare->piece()->type ?? null;
		$this->algebraicNotation = ConvertMoveToAlgebraicNotation::convert($this->pieceTypeMoved, $from, $to);

		// Chain the moves linked list
		$this->lastMove = $lastMove;
		if($lastMove) $lastMove->nextMove = $this;

		// Update board state with the move
		$this->isValid()
			? $this->state = $this->getNewState($startingState)
			: throw new InvalidMoveException("Invalid move: $this->algebraicNotation");


		// Logging info
		Logger::log("Square {$this->getSquare($from)->algebraic} has piece: " . $fromSquare->pieceName(), LogLevel::INFO);
		Logger::log("Square {$this->getSquare($to)->algebraic} has piece: " . $toSquare->pieceName(), LogLevel::INFO);
		Logger::log("Move notation: $this->algebraicNotation", LogLevel::INFO);
	}

	protected function getNewState(ChessBoard $currentBoardState): ChessBoard
	{
		$newState = clone $currentBoardState;

		$newState->setPiece($this->to, $currentBoardState->getPiece($this->from));
		$newState->setPiece($this->from, null);

		return $newState;
	}

	protected function setId(): int
	{
		static $count = 0;
		$count++;

		return $count;
	}

	/*
	 * =============================
	 *
	 *    Move timeline traversal
	 *
	 * =============================
	 */

	/**
	 * @throws NoPreviousMoveException
	 */
	public function previous(): Move
	{
		return $this->lastMove ?? throw new NoPreviousMoveException(
			"The previous move doesn't exist. Are you at the start of the game?"
		);
	}

	/**
	 * @throws NoNextMoveException
	 */
	public function next(): Move
	{
		return $this->nextMove ?? throw new NoNextMoveException(
			"The next move doesn't exist. Are you at the end of the game?"
		);
	}

	/*
	 * =============================
	 *
	 *           Helpers
	 *
	 * =============================
	 */

	public function visualise(): void
	{
		$this->state->visualize();
	}

	public function getSquare(Coordinate $coords): Square
	{
		return $this->state->getSquare($coords);
	}

	public function getPiece(Coordinate $coords): ?Piece
	{
		return $this->state->getSquare($coords)->piece();
	}


	/*
	 * =============================
	 *
	 *    Move validation logic
	 *
	 * =============================
	 */

	/**
	 * Super-method that checks all validation rules
	 *
	 * @return bool
	 */
	public function isValid(): bool
	{
		/** @var array<string> $rules Array of function names to be used during validation*/
		$rules = [];

		$movesFound = $this->getAllMovesWithoutValidation();

		$moves = $movesFound['moves'];
		$atkMoves = $movesFound['attacks'];

		echo "\nMoves\n";
		foreach ($moves as $move) echo "$move ";

		echo "\nAttacks\n";
		foreach ($atkMoves as $move) echo "$move ";

		echo "\n\n";
		return true;
	}

	/**
	 * Returns moves that can happen on the chess board from all geometries
	 *
	 * @return array{
	 *   'moves': array<Coordinate>,
	 *   'attacks': array<Coordinate>
	 * }
	 */
	protected function getAllMovesWithoutValidation(): array
	{
		$moves = [];
		$attacks = [];
		$pieceBeingMoved = $this->state->getPiece($this->from);
		$movesAndAttacksAreTheSame = $pieceBeingMoved->moveGeometries === $pieceBeingMoved->attackGeometries;

		foreach($pieceBeingMoved->moveGeometries as $direction) {
//			Logger::log("== Checking for moves ==");
			$squaresFound = $this->allMovesInDirection($direction, $pieceBeingMoved->moveRepetitions);

			$moves = array_merge($moves, $squaresFound['moves']);
		}

		foreach($pieceBeingMoved->attackGeometries as $direction) {
//			Logger::log("== Checking for attacks ==");

			$squaresFound = $this->allMovesInDirection($direction, $pieceBeingMoved->attackRepetitions, true);

			$attacks = array_merge($attacks, $squaresFound['attacks']);
		}

		return [
			'moves' 	=> array_unique($moves),
			'attacks' 	=> array_unique($attacks)
		];
	}

	/**
	 * @param  array<int,int>  $moveVector
	 * @param  int $repetitions Number of repetitions for the move. `-1` for infinity.
	 * @param  bool $attackingMovesOnly Whether to return moves or attacks
	 *
	 * @return array{
	 *   'moves': array<Coordinate>,
	 *   'attacks': array<Coordinate>,
	 * }
	 */
	protected function allMovesInDirection(array $moveVector, int $repetitions, bool $attackingMovesOnly = false): array
	{
		$pieceBeingMoved = $this->getPiece($this->from);

		// Setup
		$mvCoords = [];
		$atkCoords = [];

		$cIterator = $pieceBeingMoved->color === 'white' ? $moveVector[0] * -1 : $moveVector[0];
		$rIterator = $pieceBeingMoved->color === 'white' ? $moveVector[1] * -1 : $moveVector[1];

		$currCoords = Coordinate::fromNums(
			row: $this->from->row + $rIterator,
			col: $this->from->col + $cIterator
		);

		// Computing moves
		for($i = 0; $i < $repetitions || $repetitions === -1; $i++ ) {
			if($this->isInsideBoard($currCoords)) {
//				Logger::log("Current coords: $currCoords");

				$pieceOnCurrCoords = $this->getPiece($currCoords);

				if($pieceOnCurrCoords == null) {
					// Square is empty; a move
					if(! $attackingMovesOnly) {
						$mvCoords[] = $currCoords;
//						Logger::log("Move");
					}
				}

				else if($pieceOnCurrCoords->color != $pieceBeingMoved->color) {
					// Square has piece of opposite color; an attack
					if($attackingMovesOnly) {
						$atkCoords[] = $currCoords;
//						Logger::log("Capture");
						break;
					}
				}

				else {
					// Piece is the same color; friendly fire
					break;
				}

				$currCoords = Coordinate::fromNums(
					row: $currCoords->row + $rIterator,
					col: $currCoords->col + $cIterator
				);
			}
			else {
				// Outside the chess board
				break;
			}
		}

		return [
			'moves' 	=> $mvCoords,
			'attacks' 	=> $atkCoords,
		];
	}

	protected function isInsideBoard(Coordinate $coords): bool
	{
		return $this->state->isSquareInBoard($coords);
	}

	protected function isCapture(): bool
	{
		return $this->pieceTypeCaptured != null;
	}





	public function isCheck(): bool
	{
		return false;
	}

	public function isCheckmate(): bool
	{
		return false;
	}

	public function isLegal(): bool
	{
		return true;
	}

	public function isPromotion(): bool
	{
		return false;
	}
}