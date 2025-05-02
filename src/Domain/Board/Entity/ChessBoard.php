<?php

namespace Chess\Domain\Board\Entity;

use Chess\Domain\Board\BoardException;
use Chess\Domain\Board\Service\LayoutCharParser;
use Chess\Infrastructure\Logging\Logger;
use Chess\Infrastructure\LogLevel;

class ChessBoard
{
	protected array $maxArea = ['r' => 16, 'c' => 16];

	public array $board = [];
	public readonly int $rows, $cols;

	/**
	 * @throws BoardException
	 */
	public function __construct(array $layout, int $rows = 8, int $cols = 8)
	{
		if($rows > $this->maxArea['r'] || $cols > $this->maxArea['c']) {
			throw new BoardException(message:
				"Board size is too large: $rows x $cols. 
				Maximum allowed size is {$this->maxArea['r']} x {$this->maxArea['c']}"
			);
		}

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

				try {
					$pieceType = $parser->getType();
				}
				catch(BoardException $e) {
					Logger::log($e->getMessage(), LogLevel::WARNING);
					$pieceType = 'Empty';
				}

				$this->board[$r][$c] = ($pieceType === 'Empty')
					? null
					: new $pieceType(color: $parser->getColor());
 			}
		}
	}
}