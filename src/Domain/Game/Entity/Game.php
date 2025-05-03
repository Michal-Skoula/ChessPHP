<?php

namespace Chess\Domain\Game\Entity;

use Chess\Domain\Board\Entity\ChessBoard;
use Chess\Domain\Board\Entity\Coordinate;
use Chess\Domain\Board\Entity\Square;
use Chess\Domain\Board\Exception\MaxBoardSizeException;
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
	protected array $moves = [];
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
//			visualize($this->board, true);

		}
		catch (MaxBoardSizeException) {
			Logger::log("The board you are trying to create is too large. Exiting.", LogLevel::ERROR);
			exit();
		}
	}

	public function playMove(Coordinate $from, Coordinate $to): void
	{
		try {
			$move = new Move($this->board, $from, $to);

			if($move->isValid())
			{
				$this->moves[] = $move;

				$move->isCapture()
					? Logger::log("Captured {$move->pieceCaptured->name}", LogLevel::INFO)
					: Logger::log("Moved piece {$move->pieceMoved->name} to {$move->state->getSquare($to)->algebraic}", LogLevel::INFO);
			}
			else {
				Logger::log("Invalid move. Try a different one.", LogLevel::INFO);
			}
		}
		catch (InvalidMoveException) {
			Logger::log("There is no piece at square {$this->board->getSquare($from)->algebraic}.", LogLevel::WARNING);
		}
	}

	public function playMoveFromAlgebraic(string $from, string $to): void
	{
		$fromCoords = Coordinate::fromAlgebraic($from);
		$toCoords = Coordinate::fromAlgebraic($to);

		$this->playMove($fromCoords, $toCoords);
	}

	public function getSquare(Coordinate $coords): Square
	{
		return $this->board->getSquare($coords);
	}

	public function getPiece(Coordinate $coords): ?Piece
	{
		return $this->board->getSquare($coords)->piece();
	}
}