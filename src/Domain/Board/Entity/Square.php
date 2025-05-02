<?php

namespace Chess\Domain\Board\Entity;

use Chess\Domain\Piece\Entity\AbstractPiece;

final class Square
{
	public int $id;
	public int
	public ?AbstractPiece $piece;

	public function __construct()
	{
		static $id = 1;

		$this->id = $id;
		$id++;
	}

}