<?php

namespace Domain\Piece\Service;

use Domain\Piece\ValueObject\Move;

class GetMoveFromArrayNotation
{
	public static function convert($move): Move
	{
		return new Move();
	}
}