<?php

namespace Chess\Domain\Move\Service;

use Chess\Domain\Board\Entity\Square;
use Chess\Domain\Piece\Entity\Piece;
use Chess\Domain\Piece\ValueObject\Enums\PieceType;

/**
 * Converts a move from one square to another to algebraic notation, e.g. `Nxb4` or `cxe4`.
 */
class ConvertMoveToAlgebraicNotation
{
	//TODO: When done implementing logic for what pieces are threatening another piece, implement it here
	public static function convert(PieceType $pieceType, Square $from, Square $to): string
	{
		if ($pieceType == PieceType::PAWN) {
			return $from->column() != $to->column()        	// If the column changes, it must be a capture
				? "{$to->column()}x$to->algebraic"    	// Capture or en passant
				: "$to->algebraic";                      	// Move
		}
		else {
			if ($to->isOccupied()) {
				$multiplePiecesOfTheSameTypeAreAttacking = false;

				if ($multiplePiecesOfTheSameTypeAreAttacking) {
					$multiplePiecesOnTheSameFile = false;

					if($multiplePiecesOnTheSameFile) {
						return "$pieceType->value{$to->row()}x{$to->algebraic}"; // Bc4xe6
					}
					else return "$pieceType->value{$to->column()}x{$to->algebraic}";

				} else return "{$pieceType->value}x$to->algebraic";

			} else return "$pieceType->value$to->algebraic";
		}
	}
}