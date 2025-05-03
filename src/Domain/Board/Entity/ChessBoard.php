<?php

namespace Chess\Domain\Board\Entity;

use Chess\Domain\Board\Exception\InvalidSquareException;
use Chess\Domain\Board\Exception\MaxBoardSizeException;
use Chess\Domain\Board\Service\LayoutCharParser;
use Chess\Domain\Piece\Entity\Piece;
use Chess\Domain\Piece\Exception\InvalidPieceException;
use Chess\Domain\Piece\ValueObject\Enums\PieceType;
use Chess\Infrastructure\Logging\Logger;
use Chess\Infrastructure\Logging\LogLevel;

class ChessBoard
{
	/**
	 * Max chessboard size for custom board. The limit is 25,
	 * as there are no more chars to use in the ASCII alphabet.
	 * @var array{'r': int, 'c': int}
	 */
	final array $maxArea = ['r' => 25, 'c' => 25];

	/**
	 * Stores the game state
	 * @var array<int, array<int, Square>>
	 */
	public array $playArea = [];
	public readonly int $rows;
	public readonly int $cols;

	/**
	 * @param  array<array<string>>  $layout
	 * @param  int  $rows
	 * @param  int  $cols
	 * @throws MaxBoardSizeException
	 */
	public function __construct(array $layout, int $rows = 8, int $cols = 8)
	{
		if ($rows > $this->maxArea['r'] || $cols > $this->maxArea['c']) {
			throw new MaxBoardSizeException(message: "Board size is too large: $rows x $cols. Maximum allowed size is {$this->maxArea['r']} x {$this->maxArea['c']}"
			);
		}

		$this->cols = $cols;
		$this->rows = $rows;

		if (count($layout) > $this->rows || count($layout[0]) > $this->cols) {
			throw new MaxBoardSizeException(message: "The defined layout is bigger than the defined chess board area: $this->rows x $this->cols."
			);
		}

		$this->build($layout);
	}

	/**
	 * Builds the chess board based on the provided layout.
	 * @param  array<array<string>>  $layout
	 */
	public function build(array $layout): void
	{
		for ($r = 0; $r < $this->rows; $r++) {
			for ($c = 0; $c < $this->cols; $c++) {
				$square = new Square($r + 1, $c + 1);
				$this->playArea[$r][$c] = $square;

				$char = $layout[$r][$c];

				if ($char) {
					$square->setPiece(self::getPieceFromChar($char));
				}
			}
		}
	}

	/**
	 * Returns a square from the chessboard.
	 *
	 * NOTE: indexes start from 1, -1 is subtracted! !!
	 *
	 * Example: `a1 => [row= 1, col= 1]` is translated into `[0,0]`
	 * @param  int  $row
	 * @param  int  $col
	 * @return Square
	 */
	public function getSquareFromArray(int $row, int $col): Square
	{
//		echo ("getSquareFromArray() == row: $row, col: $col \n" );
		return $this->playArea[$row - 1][$col - 1];
	}

	public function getSquare(Coordinate $coords): Square
	{
		return $this->getSquareFromArray($coords->row, $coords->col);
	}

	public function getPiece(Coordinate $coords): ?Piece
	{
		return $this->getSquareFromArray($coords->row, $coords->col)->piece();
	}

	/**
	 * @throws InvalidSquareException
	 */
	public function getSquareFromAlgebraic(string $notation): Square
	{
		foreach($this->getAllSquaresAsArray() as $square) {
			if($square->algebraic == $notation) {
				return $square;
			}
		}
		throw new InvalidSquareException("Square $notation not found on the board.");
	}

	public function setPiece(int $row, int $col, ?Piece $piece): void
	{
		$this->getSquareFromArray($row, $col)->setPiece($piece);
	}

	public function setPieceFromCoords(Coordinate $coords, ?Piece $piece): void
	{
		$this->setPiece($coords->row, $coords->col, $piece);
	}

	/**
	 * @return array<Square>
	 */
	protected function getAllSquaresAsArray(): array
	{
		$squares = [];

		for ($r = 1; $r <= $this->rows; $r++) {
			for ($c = 1; $c <= $this->cols; $c++) {
				$squares[] = $this->getSquareFromArray($r,$c);
			}
		}

		return $squares;
	}
	protected static function getPieceFromChar(string $char): ?Piece
	{
		$parser = new LayoutCharParser($char);

		try {
			return Piece::make($parser->getType(), $parser->getColor());
		}
		catch(InvalidPieceException $e) {
			Logger::log($e->getMessage(), LogLevel::WARNING);
			return null;
		}
	}

	public function visualize(): void
	{
		visualize($this, true);
	}
}