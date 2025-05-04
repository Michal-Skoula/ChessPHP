<?php

namespace Chess\Domain\Move\Service;

use Chess\Domain\Move\Entity\Move;
use Chess\Domain\Move\Exception\PromotionException;
use Chess\Domain\Piece\Entity\Piece;
use Chess\Domain\Piece\ValueObject\Enums\PieceType;

class Promotion
{
//	public function __construct(protected Move $move) {}
//
//	/**
//	 * Promotes a pawn that has reached the end of the board
//	 * @param  PieceType  $promoteTo Piece type to promote to
//	 *
//	 * @return Piece Returns the instance of the piece that was promoted
//	 * @throws PromotionException
//	 */
//	public function promote(PieceType $promoteTo): Piece
//	{
//		if($this->canPromote()) {
//			$pieceClass = $promoteTo->getClassString();
//			$piece = Piece::make($promoteTo, $this->move->movedBy, true);
//
//			$this->move->to->setPiece($piece);
//			return $piece;
//		}
//		throw new PromotionException("Can't promote piece, conditions are not met.");
//	}
//
//	public function canPromote(int $boardCols = 8, ): bool
//	{
//		$lastRank = $this->move->movedBy === 'white' ? $boardCols : 1;
//
//		return $this->move->pieceMoved == PieceType::PAWN && $this->move->to->row() === $lastRank;
//	}
}