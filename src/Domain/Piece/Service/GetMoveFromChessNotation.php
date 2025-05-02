<?php

namespace Chess\Domain\Piece\Service;

use Chess\Domain\Piece\ValueObject\Move;

class GetMoveFromChessNotation
{
	public static function convert(string $chessNotation): Move
	{
		return new Move();
	}
}