<?php

require_once __DIR__ . '/src/Infrastructure/visualizer.php';

use Chess\Domain\Board\Entity\Coordinate;
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

$game = new Game($layout, 12, 12);

$game->playMove('a1', 'a3');
$game->playMove('a3', 'c6');
$game->playMove('c6', 'd8');

$first_move = $game->firstMove();
var_dump($first_move->visualise());
var_dump($first_move->next()->visualise());
var_dump($first_move->next()->previous()->visualise());