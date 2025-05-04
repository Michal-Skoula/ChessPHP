<?php

require_once __DIR__ . '/src/Infrastructure/visualizer.php';

use Chess\Domain\Game\Entity\Game;
use Chess\Infrastructure\Logging\Logger;

/**
 * Layout definition from which to construct the chess board.
 *
 * The layout is constructed from chars as defined by LayoutCharParser.
 * For an empty square, an `_` is used.
 */
$layout = [
//	  a   b   c   d   e   f   g   h
	['r','n','b','q','k','b','n','r'], // 1
	['p','p','p','p','p','p','p','p'], // 2
	['_','_','_','_','_','_','_','_'], // 3
	['_','_','_','_','_','_','_','_'], // 4
	['_','_','_','_','_','_','_','_'], // 5
	['_','_','_','_','_','_','_','_'], // 6
	['P','P','P','P','P','P','P','P'], // 7
	['R','N','B','Q','K','B','N','R'], // 8
];

$game = new Game($layout);

$game->playMove('a1', 'a3');
$game->playMove('a3', 'c6');
$game->playMove('c6', 'd8');

$game->getMove(0)->state->visualize();
$game->getMove(1)->state->visualize();
$game->getMove(2)->state->visualize();

//var_dump($game->moves[0]->state === $game->moves[1]->state);
//Logger::log("Showing move history");
//foreach ($game->moves as $move) {
//	$move->state->visualize();
//	echo "\n";
//
//	var_dump($move->isCapture());
//	var_dump($move->from->getAlgebraic());
////	var_dump($move->lastMove->to->getAlgebraic());
//
//}