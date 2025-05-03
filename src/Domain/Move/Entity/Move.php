<?php

namespace Chess\Domain\Move\Entity;

use Chess\Domain\Board\Entity\Square;
use Chess\Domain\Move\Exception\InvalidMoveException;
use Chess\Domain\Move\Exception\MoveException;
use Chess\Domain\Move\Service\ConvertMoveToAlgebraicNotation;
use Chess\Domain\Piece\Entity\Piece;
use Chess\Domain\Piece\ValueObject\Enums\PieceType;

/**
 * Stores a particular move
 */
final class Move
{
	public string $algebraicNotation;
	public string $movedBy;
	public Square $from;
	public Square $to;
	public PieceType $pieceMoved;
	public ?PieceType $pieceCaptured;

	/**
	 * @throws InvalidMoveException
	 */
	public function __construct(Square $from, Square $to)
	{
		$this->from = $from;
		$this->to = $to;


		if($from->piece() == null) {
			throw new InvalidMoveException("From square $from->algebraic has no piece. The move is invalid.");
		}

		$this->movedBy = $from->piece()->color;
		$this->pieceMoved = $from->piece()->type;
		$this->pieceCaptured = $to->piece()->type ?? null;

		$this->from->setPiece(null);
		$this->to->setPiece(Piece::make($this->pieceMoved, $this->movedBy));

		$this->algebraicNotation = ConvertMoveToAlgebraicNotation::convert($this->pieceMoved, $from, $to);
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
}