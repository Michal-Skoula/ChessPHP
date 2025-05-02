<?php

namespace Chess\Domain\Board\Entity;

use Chess\Domain\Board\BoardException;
use Chess\Domain\Board\Service\LayoutCharParser;
use Chess\Domain\Piece\Entity\AbstractPiece;
use Chess\Domain\Piece\InvalidPieceException;
use Chess\Infrastructure\Logging\Logger;
use Chess\Infrastructure\LogLevel;

class ChessBoard
{
	/**
	 * @var array{'r': int, 'c': int}
	 */
	protected array $maxArea = ['r' => 16, 'c' => 16];

	/**
	 * @var array<int, array<int, Square>>
	 */
	public array $playArea = [];
	public readonly int $rows, $cols;

	/**
	 * @throws BoardException
	 */
	public function __construct(array $layout, int $rows = 8, int $cols = 8)
	{
		if($rows > $this->maxArea['r'] || $cols > $this->maxArea['c']) {
			throw new BoardException(message:
				"Board size is too large: $rows x $cols. Maximum allowed size is {$this->maxArea['r']} x {$this->maxArea['c']}"
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
				$square = new Square($r, $c);
				$this->playArea[$r][$c] = $square;

				$char = $layout[$r][$c];

				if($char) {
					$square->piece = self::getPieceFromChar($char);
				}
 			}
		}
	}

	protected static function getPieceFromChar(string $char): ?AbstractPiece
	{
		$parser = new LayoutCharParser($char);

		try {
			$pieceType = $parser->getType();

			return $pieceType !== 'Empty'
				? new $pieceType(color: $parser->getColor())
				: null;

		}
		catch(InvalidPieceException $e) {
			Logger::log($e->getMessage(), LogLevel::WARNING);
			return null;
		}
	}
}