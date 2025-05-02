<?php

namespace Chess\Domain\Piece\Service;

use Chess\Domain\Piece\ValueObject\Move;

class GetMoveFromArrayNotation
{
	public static function convert($move): Move
	{
		return new Move();
	}
}