<?php

namespace Chess\Domain\Piece\ValueObject\Enums;

use Chess\Domain\Piece\Entity\Bishop;
use Chess\Domain\Piece\Entity\King;
use Chess\Domain\Piece\Entity\Knight;
use Chess\Domain\Piece\Entity\Pawn;
use Chess\Domain\Piece\Entity\Queen;
use Chess\Domain\Piece\Entity\Rook;

enum Piece: string
{
	case ROOK = 'r';
	case PAWN = 'p';
	case KNIGHT = 'n';
	case BISHOP = 'b';
	case QUEEN = 'q';
	case KING = 'k';
	case EMPTY = '_';

	public function getClass(): string
	{
		return match ($this) {
			self::ROOK => Rook::class,
			self::PAWN => Pawn::class,
			self::KNIGHT => Knight::class,
			self::BISHOP => Bishop::class,
			self::QUEEN => Queen::class,
			self::KING => King::class,
			self::EMPTY => "Empty"
		};
	}
}

