<?php

namespace Domain\Piece\Service;

use Domain\Piece\ValueObject\Move;

class GetMoveFromChessNotation
{
	public static function convert(string $chessNotation): Move
	{
		return new Move();
	}
}