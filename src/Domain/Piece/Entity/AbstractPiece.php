<?php

namespace Chess\Domain\Piece\Entity;
use Domain\Piece\Service\GetMoveFromArrayNotation;
use Domain\Piece\Service\GetMoveFromChessNotation;

abstract class AbstractPiece
{
	public string $name;
	public int $value;
	public string $icon = 'x';
	public string $color;

	protected array $moves;
	protected array $specialMoves = [];

	public function __construct(string $color)
	{
		$this->color = $color;
	}

	public function play(string|array $play)
	{
		$move = match(gettype($play)) {
			'string' => GetMoveFromChessNotation::convert($play),
			'array' => GetMoveFromArrayNotation::convert($play),
		};

		if(! $move->isValid()) {
			echo 'Move is invalid';
		}

		if($move->isPawnPromotion()) {

		}
	}
 }