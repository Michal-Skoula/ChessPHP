<?php

namespace Chess\Domain\Move\Entity;

use Chess\Domain\Board\Entity\ChessBoard;
use Chess\Domain\Board\Entity\Coordinate;
use Chess\Domain\Board\Entity\Square;
use Chess\Domain\Move\Exception\InvalidMoveException;
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
	public string $algebraicNotation;
	public string $movedBy;
	public ChessBoard $state;
	public PieceType $pieceMoved;
	public ?PieceType $pieceCaptured;

	public Coordinate $from;
	public Coordinate $to;
	public Move $lastMove;
	public Move $nextMove;

	/**
	 * @throws InvalidMoveException
	 */
	public function __construct(ChessBoard $startingState, Coordinate $from, Coordinate $to)
	{
		$fromSquare = $startingState->getSquare($from);
		$toSquare = $startingState->getSquare($to);

		if(! $fromSquare->isOccupied()) {
			throw new InvalidMoveException("From square $fromSquare->algebraic has no piece. The move is invalid.");
		}

		// Set move information
		$this->from = $from;
		$this->to = $to;

		$this->movedBy = $fromSquare->piece()->color;
		$this->pieceMoved = $fromSquare->piece()->type;
		$this->pieceCaptured = $toSquare->piece()->type ?? null;
		$this->algebraicNotation = ConvertMoveToAlgebraicNotation::convert($this->pieceMoved, $from, $to);

		// Update board state with the move
		$newState = $startingState;

		$newState->setPieceFromCoords($toSquare->coords, $fromSquare->piece());
		$newState->setPieceFromCoords($fromSquare->coords, null);

		$this->state = $newState;

		$this->state->visualize();

		Logger::log("Square {$this->getSquare($from)->algebraic} has piece: " . $fromSquare->pieceName(), LogLevel::INFO);
		Logger::log("Square {$this->getSquare($to)->algebraic} has piece: " . $toSquare->pieceName(), LogLevel::INFO);

		Logger::log("Move notation: $this->algebraicNotation", LogLevel::INFO);
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