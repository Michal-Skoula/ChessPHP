<?php

namespace Chess\Domain\Board\Entity;

use Chess\Domain\Board\Exception\InvalidSquareException;
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
	 * @var array{'r': int, 'c': int}
	 */
	protected final array $maxArea = ['r' => 25, 'c' => 25];

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

	public function __clone(): void
	{
		$newPlayArea = [];

		foreach ($this->playArea as $r => $row) {
			foreach ($row as $c => $square) {
				$newPlayArea[$r][$c] = clone $square;
			}
		}

		$this->playArea = $newPlayArea;
	}


	/**
	 * Builds the chess board based on the provided layout.
	 * @param  array<array<string>>  $layout
	 */
	public function build(array $layout): void
	{
		for ($r = 0; $r < $this->rows; $r++) {
			for ($c = 0; $c < $this->cols; $c++) {
				$square = new Square($r, $c);
				$this->playArea[$r][$c] = $square;

				$char = $layout[$r][$c] ?? null;

				if ($char) {
					$square->setPiece(self::getPieceFromChar($char));
				}
			}
		}
	}

	/**
	 * Returns a square from the chessboard.
	 *
	 * Example: `a1 => [row= 1, col= 0]` is translated into `[1,0]`
	 *
	 * @param  int  $row
	 * @param  int  $col
	 * @return Square
	 */
	protected function getSquareDirectlyFromBoard(int $row, int $col): Square
	{
		return $this->playArea[$row][$col];
	}

	protected function setPieceFromArray(int $row, int $col, ?Piece $piece): void
	{
		$this->getSquareDirectlyFromBoard($row, $col)->setPiece($piece);
	}

	public function getSquare(Coordinate $coords): Square
	{
		return $this->getSquareDirectlyFromBoard($coords->row, $coords->col);
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

	public function getPiece(Coordinate $coords): ?Piece
	{
		return $this->getSquare($coords)->piece();
	}

	public function setPiece(Coordinate $coords, ?Piece $piece): void
	{
		$this->setPieceFromArray($coords->row, $coords->col, $piece);
	}

	/**
	 * @return array<Square>
	 */
	protected function getAllSquaresAsArray(): array
	{
		$squares = [];

		for ($r = 0; $r < $this->rows; $r++) {
			for ($c = 0; $c < $this->cols; $c++) {
				$squares[] = $this->getSquareDirectlyFromBoard($r,$c);
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
		if(function_exists('visualize')) {
			visualize($this, true);
		}
		else {
			Logger::log("The visualizer is probably not set as `required`, cannot render", LogLevel::ERROR);
		}
	}

	public function isSquareInBoard(Coordinate $coords): bool
	{
		return 	$coords->row < $this->rows && $coords->row >= 0 &&
				$coords->col < $this->cols && $coords->col >= 0;

	}
}