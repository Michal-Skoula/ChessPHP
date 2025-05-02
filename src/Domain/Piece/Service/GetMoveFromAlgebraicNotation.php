<?php

namespace Chess\Domain\Piece\Service;

use Chess\Domain\Move\Entity\Move;

class GetMoveFromAlgebraicNotation
{
	public static function convert(string $algebraic): Move
	{
		return new Move();
	}
}