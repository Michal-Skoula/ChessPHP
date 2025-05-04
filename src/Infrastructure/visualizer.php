<?php

use Chess\Domain\Board\Entity\ChessBoard;
use Chess\Domain\Board\Entity\Coordinate;

if (! function_exists('visualize')) {
	function visualize(ChessBoard $board, bool $detailed = false): void
	{
		// First row labels
		if($detailed) {
			echo "\n   ";
			for($c = 0; $c < $board->cols; $c++) {
				echo " {$board->getSquare(Coordinate::fromCoords(0, $c))->column()}";
			}
			echo "\n    ";
			for($c = 0; $c < $board->cols; $c++) {
				echo "——";
			}
		}

		for ($r = 0; $r < $board->rows; $r++) {
			// Left border
			echo $detailed
				? "\n {$board->getSquare(Coordinate::fromCoords($r, 0))->row()} |"
				: "\n ";

			for ($c = 0; $c < $board->rows; $c++) {
				$square = $board->getSquare(Coordinate::fromCoords($r, $c));
				$piece = $square->piece();

				echo ($piece ? $piece->icon[$piece->color] : ' ') . ' '; // Pieces
			}
		}
		echo "\n\n";
	}
}