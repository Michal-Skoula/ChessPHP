<?php

use Chess\Domain\Board\Entity\ChessBoard;

if (! function_exists('visualize')) {
	function visualize(ChessBoard $board, bool $detailed = false): void
	{
		// First row labels
		if($detailed) {
			echo "\n   ";
			for($c = 1; $c <= $board->cols; $c++) {
				echo " {$board->getSquare(col: $c)->getAlgebraicColumn()}";
			}
			echo "\n    ";
			for($c = 1; $c <= $board->cols; $c++) {
				echo "——";
			}
		}

		for ($r = 1; $r <= $board->rows; $r++) {
			// Left border
			echo $detailed
				? "\n {$board->getSquare(row: $r)->getAlgebraicRow()} |"
				: "\n ";

			for ($c = 1; $c <= $board->rows; $c++) {
				$square = $board->getSquare($r, $c);
				$piece = $square->piece;

				echo ($piece ? $piece->icon[$piece->color] : ' ') . ' '; // Pieces
			}
		}
		echo "\n\n";
	}
}