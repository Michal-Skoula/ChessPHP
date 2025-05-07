<?php

use Chess\Domain\Board\Entity\Coordinate;
use Chess\Domain\Move\Entity\Move;

if (!function_exists('visualizeValidMoves')) {
	/**
	 * @param  Move  $move
	 * @param  array<int, int>  $cardinal
	 * @return void
	 */
	function visualizeValidMoves(Move $move, array $cardinal): void
	{
		echo "\n\n   == Moves ==\n\n";

//		$coords = $move->allMovesInDirection($cardinal,);

		while (!empty($coords)) {
			$key = array_key_first($coords); // e.g. 'capture' or 'slide'
			$coordGroup = array_shift($coords); // get and remove that group of coords

			foreach ($coordGroup as $type) {
				echo "Move type: $key, square: {$type->algebraic()} \n";
			}
		}
	}
}

