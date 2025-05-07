<?php

use Chess\Domain\Board\Entity\ChessBoard;
use Chess\Domain\Board\Entity\Coordinate;

if (! function_exists('visualize')) {
	function visualize(ChessBoard $board, bool $detailed = false): void
	{
		// First row labels
		if($detailed) {
//			$spacingRow = $board->cols >= 9 ? "    " : "    ";
			$spacingRow = "    ";
			echo "\n$spacingRow";

			for($c = 0; $c < $board->cols; $c++) {
				echo " {$board->getSquare(Coordinate::fromNums(0, $c))->column()}";
			}
			echo "\n    ";
			for($c = 0; $c < $board->cols; $c++) {
				echo "——";
			}
		}

		for ($r = 0; $r < $board->rows; $r++) {
			// Left border
			if($detailed) {
				$spacingCol =  $r >= 9 ? " " : "  ";

				echo "\n {$board->getSquare(Coordinate::fromNums($r, 0))->row()}$spacingCol|";
			}
			else {
				echo "\n ";
			}

			for ($c = 0; $c < $board->rows; $c++) {
				$square = $board->getSquare(Coordinate::fromNums($r, $c));
				$piece = $square->piece();

				echo ($piece ? $piece->icon[$piece->color] : ' ') . ' '; // Pieces
			}
		}
		echo "\n\n";
	}
}