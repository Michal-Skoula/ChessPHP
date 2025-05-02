<?php

namespace Chess\Domain\Move\Entity;

use Chess\Domain\Board\Entity\Square;
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

	public function __construct(Square $from, Square $to)
	{
		$this->from = $from;
		$this->to = $to;
		$this->movedBy = $from->piece()->color;

		$this->pieceMoved = $from->piece()->type;
		$this->pieceCaptured = $to->piece()->type;

		$this->from->setPiece(null);
		$this->to->setPiece(Piece::make($this->pieceMoved, $this->movedBy));

		$this->algebraicNotation = ConvertMoveToAlgebraicNotation::convert($this->pieceMoved, $from, $to);
	}
}