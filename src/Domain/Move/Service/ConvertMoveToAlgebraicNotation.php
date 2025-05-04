<?php

namespace Chess\Domain\Move\Service;

use Chess\Domain\Board\Entity\Coordinate;
use Chess\Domain\Board\Entity\Square;
use Chess\Domain\Piece\Entity\Piece;
use Chess\Domain\Piece\ValueObject\Enums\PieceType;

/**
 * Converts a move from one square to another to algebraic notation, e.g. `Nxb4` or `cxe4`.
 */
class ConvertMoveToAlgebraicNotation
{
	//TODO: When done implementing logic for what pieces are threatening another piece, implement it here
	public static function convert(PieceType $pieceType, Coordinate $from, Coordinate $to): string
	{
		if ($pieceType == PieceType::PAWN) {
			return $from->col != $to->col        	// If the column changes, it must be a capture
				? "{$to->algebraicCol()}x{$to->algebraic()}"    		// Capture or en passant
				: "{$to->algebraic()}";                      	// Move
		}
		else {
			return "WiP";
//			if ($to->hasPiece()) {
//				$multiplePiecesOfTheSameTypeAreAttacking = false;
//
//				if ($multiplePiecesOfTheSameTypeAreAttacking) {
//					$multiplePiecesOnTheSameFile = false;
//
//					if($multiplePiecesOnTheSameFile) {
//						return "$pieceType->value{$to->row()}x{$to->algebraic}"; // B4xe6
//					}
//					else return "$pieceType->value{$to->column()}x{$to->algebraic}";
//
//				} else return "{$pieceType->value}x$to->algebraic";
//
//			} else return "$pieceType->value$to->algebraic";
		}
	}
}