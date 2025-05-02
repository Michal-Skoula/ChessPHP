<?php

namespace Chess\Domain\Piece\ValueObject;

final class Move
{
	public function isValid(): bool
	{

		// is not checked
		// does not cause discovered check
		// is in bounds of the chess board
		return true;
	}

	public function isPawnPromotion(): bool
	{
		return false;
	}
}