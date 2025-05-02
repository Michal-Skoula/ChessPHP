<?php

namespace Chess\Domain\Move\Service;

use Chess\Domain\Board\Entity\Square;
use Chess\Domain\Piece\Entity\Piece;

class ConvertMoveToCoordsNotation
{
	/**
	 * @param  Square  $from
	 * @param  Square  $to
	 * @param  Piece  $pieceMoved
	 * @param  Piece|null  $pieceCaptured
	 * @return array<array{
	 *   from: array{r: int, c: int},
	 *   to: array{r: int, c: int},
	 *   pieceMoved: Piece,
	 *   pieceCaptured: ?Piece
	 * }>
	 */
	public static function convert(Square $from, Square $to, Piece $pieceMoved, ?Piece $pieceCaptured = null): array
	{
		return [
			'from' 			=> $from,
			'to' 			=> $to,
			'pieceMoved' 	=> $pieceMoved,
			'pieceCaptured' => $pieceCaptured
		];
	}
}