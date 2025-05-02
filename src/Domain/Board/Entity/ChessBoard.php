<?php

namespace Chess\Domain\Board\Entity;

use Chess\Domain\Board\Exception\MaxBoardSizeException;
use Chess\Domain\Board\Service\LayoutCharParser;
use Chess\Domain\Piece\Entity\Piece;
use Chess\Domain\Piece\Exception\InvalidPieceException;
use Chess\Infrastructure\Logging\Logger;
use Chess\Infrastructure\Logging\LogLevel;

class ChessBoard
{
	/**
	 * Max chessboard size for custom board. The limit is 25,
	 * as there are no more chars to use in the ASCII alphabet.
	 *
	 * @var array{'r': int, 'c': int}
	 */
	final array $maxArea = ['r' => 25, 'c' => 25];

	/**
	 * Stores the game state
	 *
	 * @var array<int, array<int, Square>>
	 */
	public array $playArea = [];
	public readonly int $rows;
	public readonly int $cols;

	/**
	 * @throws MaxBoardSizeException
	 */
	public function __construct(array $layout, int $rows = 8, int $cols = 8)
	{
		if($rows > $this->maxArea['r'] || $cols > $this->maxArea['c']) {
			throw new MaxBoardSizeException(message:
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
					$square->setPiece(self::getPieceFromChar($char));
				}
 			}
		}
	}

	/**
	 * Returns a square from the chessboard.
	 *
	 * NOTE: indexes start from 1 !!
	 *
	 * Example: `a1 => [row= 1, col= 1]`
	 *
	 * @param  int  $row
	 * @param  int  $col
	 * @return Square
	 */
	public function getSquare(int $row = 1, int $col = 1): Square
	{
		return $this->playArea[$row - 1][$col - 1];
	}

	protected static function getPieceFromChar(string $char): ?Piece
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