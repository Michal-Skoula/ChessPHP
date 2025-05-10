<?php

namespace Chess\Domain\Game\Entity;

use Chess\Domain\Board\Entity\ChessBoard;
use Chess\Domain\Board\Entity\Coordinate;
use Chess\Domain\Board\Entity\Square;
use Chess\Domain\Board\Exception\MaxBoardSizeException;
use Chess\Domain\Board\Exception\SquareOutOfBoundsException;
use Chess\Domain\Move\Entity\Move;
use Chess\Domain\Move\Exception\InvalidMoveException;
use Chess\Domain\Piece\Entity\Piece;
use Chess\Infrastructure\Logging\Logger;
use Chess\Infrastructure\Logging\LogLevel;

class Game
{
	/**
	 * @var array<Move>
	 */
	public array $moves = [];
	protected ChessBoard $board;


	/**
	 * @param  array<array<string>>  $layout
	 * @param  int  $boardRows
	 * @param  int  $boardCols
	 */
	public function __construct(array $layout, int $boardRows = 8, int $boardCols = 8)
	{
		try {
			$this->board = new ChessBoard($layout, $boardRows, $boardCols);
			visualize($this->board, true);
//			var_dump($this->board->getSquareFromAlgebraic('a2')->piece());
//			var_dump($this->board->getSquare(Coordinate::fromAlgebraic('a2'))->piece()); works


		}
		catch (MaxBoardSizeException) {
			Logger::log("The board you are trying to create is too large. Exiting.", LogLevel::ERROR);
			exit();
		}
	}

	public function getValidMoves(Coordinate $square)
	{

	}

	public function playMoveFromCoords(Coordinate $from, Coordinate $to): void
	{
		$lastMove = $this->movesCount() !== 0
			? $this->lastMove()
			: null;

		try {
			$move = new Move($this->board, $from, $to, $lastMove);

			$this->addMove($move);

			// TODO: improve logging, probably implement into Move itself
//			$move->isCapture()
//				? Logger::log("Captured {$move->pieceTypeCaptured->name}", LogLevel::INFO)
//				: Logger::log("Moved piece {$move->pieceTypeMoved->name} to {$move->state->getSquare($to)->algebraic}", LogLevel::INFO);

		}
		catch (InvalidMoveException) {
			Logger::log("Invalid move. Try a different one.", LogLevel::INFO);
		}
	}

	protected function coordsAreInBounds(Coordinate $coords): bool
	{
		return $this->board->isSquareInBoard($coords);
	}

	/**
	 * @throws SquareOutOfBoundsException
	 */
	public function playMove(string $from, string $to): void
	{
		$fromCoords = Coordinate::fromAlgebraic($from);
		$toCoords = Coordinate::fromAlgebraic($to);

		if($this->coordsAreInBounds($fromCoords) && $this->coordsAreInBounds($toCoords)) {
			$this->playMoveFromCoords($fromCoords, $toCoords);
		}
		else {
			throw new SquareOutOfBoundsException(
				"Squares {$fromCoords->algebraic()} or {$toCoords->algebraic()} are outside of the chess board."
			);
		}
	}

	public function getSquare(Coordinate $coords): ?Square
	{
		if($this->coordsAreInBounds($coords)) {
			return $this->board->getSquare($coords);
		}
		else {
			Logger::log("Square {$coords->algebraic()} is out of bounds of chessboard.", LogLevel::WARNING);
			return null;
		}
	}

	public function getPiece(Coordinate $coords): ?Piece
	{
		return $this->board->getSquare($coords)->piece();
	}

	protected function addMove(Move $move): void
	{
		$this->moves[] = $move;
		$this->board = $move->state; // Updates the state to the latest board state

		Logger::log("New move is being added. Count: " . count($this->moves));

	}

	public function firstMove(): Move
	{
		return $this->moves[0];
	}

	public function lastMove(): Move
	{
		return $this->moves[$this->movesCount() - 1];
	}
	public function movesCount(): int
	{
		return count($this->moves);
	}

	/**
	 * Returns a move from the moves[] array
	 *
	 * @throws InvalidMoveException
	 */
	public function getMove(int $moveNumber): Move
	{
		if($moveNumber < $this->movesCount()) {
			return $this->moves[$moveNumber];
		}
		else {
			throw new InvalidMoveException("The move with index $moveNumber doesn't exist in the game instance.");
		}
	}
}