<?php

use Chess\Domain\Board\Entity\ChessBoard;

if (! function_exists('visualize')) {
	function visualize(ChessBoard $board, bool $detailed = false): void
	{
		if($detailed) {
			echo "\n   ";
			for($i = 0; $i < $board->cols; $i++) echo " r";
			echo "\n    ";
			for($i = 0; $i < $board->cols; $i++) echo "--";

		}

		for ($r = 0; $r < $board->rows; $r++) {
			echo $detailed
				? "\n c |"
				: "\n "; // Left border

			for ($c = 0; $c < $board->rows; $c++) {
				$square = $board->board[$r][$c];
				echo ($square ? $square->icon : ' ') . ' '; // Piece icons
			}
		}
		echo "\n\n";
	}
}