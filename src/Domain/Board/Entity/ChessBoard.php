<?php

namespace Chess\Domain\Board\Entity;

use Chess\Domain\Board\Service\LayoutCharParser;



class ChessBoard
{
	public array $board = [];
	protected int $rows, $cols;
	public function __construct(array $layout, int $rows = 8, int $cols = 8)
	{
		$this->cols = $cols;
		$this->rows = $rows;

		$this->build($layout);

	}

	public function build(array $layout): void
	{
		for($r = 0; $r < $this->rows; $r++){
			for($c = 0; $c < $this->cols; $c++)
			{
				if(! $layout[$r][$c]) $this->board[$r][$c] = null;

				$char = $layout[$r][$c];
				$parser = new LayoutCharParser($char);

				$pieceType = $parser->getType();

				$this->board[$r][$c] = ($pieceType === 'Empty')
					? null
					: new $pieceType(color: $parser->getColor());
 			}
		}
	}
}