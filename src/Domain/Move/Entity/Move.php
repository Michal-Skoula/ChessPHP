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
	public string $algebraicNotation;
	public string $movedBy;
	public ChessBoard $state;
	public PieceType $pieceMoved;
	public ?PieceType $pieceCaptured;

	public Coordinate $from;
	public Coordinate $to;
	protected ?Move $lastMove;
	protected ?Move $nextMove;

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

		$this->movedBy = $fromSquare->piece()->color;
		$this->pieceMoved = $fromSquare->piece()->type;
		$this->pieceCaptured = $toSquare->piece()->type ?? null;
		$this->algebraicNotation = ConvertMoveToAlgebraicNotation::convert($this->pieceMoved, $from, $to);

		// Chain the moves linked list
		$this->lastMove = $lastMove;
		if($lastMove) $lastMove->nextMove = $this;

		// Update board state with the move
		$this->state = $this->getNewState($startingState);

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

	public function isCapture(): bool
	{
		return $this->pieceCaptured != null;
	}

	public function isPromotion(): bool
	{
		return false;
	}

	public function isShortCastle(): bool
	{
		return false;
	}

	public function isLongCastle(): bool
	{
		return false;
	}

	public function isEnPassantLeft(): bool
	{
		return false;
	}

	public function isValid(): bool
	{
		return true;
	}
}