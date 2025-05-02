<?php

namespace Chess\Domain\Piece\ValueObject\Enums;

use Chess\Domain\Piece\Entity\Bishop;
use Chess\Domain\Piece\Entity\King;
use Chess\Domain\Piece\Entity\Knight;
use Chess\Domain\Piece\Entity\Pawn;
use Chess\Domain\Piece\Entity\Piece;
use Chess\Domain\Piece\Entity\Queen;
use Chess\Domain\Piece\Entity\Rook;
use Chess\Domain\Piece\Exception\InvalidPieceException;

enum PieceType: string
{
	case ROOK = 'R';
	case PAWN = 'P';
	case KNIGHT = 'N';
	case BISHOP = 'B';
	case QUEEN = 'Q';
	case KING = 'K';
	case EMPTY = '_';

	/**
	 * Get the equivalent PieceType enum from a Piece child class
	 *
	 * @param  Piece  $piece
	 * @return PieceType
	 *
	 * @throws InvalidPieceException
	 */
	public static function getTypeFromClass(Piece $piece): PieceType
	{
		foreach(self::cases() as $case) {
			$formatted_name = ucfirst(strtolower($case->name));

			$exploded_class = explode('\\', get_class($piece));
			$class_name = array_pop($exploded_class);

			if($formatted_name === $class_name) {
				return $case;
			}
		}
		throw new InvalidPieceException("Unknown piece: {$piece->name}. Register the piece in the PieceType enum.");
	}
	public function getClass(): string
	{
		return match ($this) {
			self::ROOK => Rook::class,
			self::PAWN => Pawn::class,
			self::KNIGHT => Knight::class,
			self::BISHOP => Bishop::class,
			self::QUEEN => Queen::class,
			self::KING => King::class,
			self::EMPTY => "Empty",
			default => throw new InvalidPieceException("Unknown piece type")
		};
	}
}

