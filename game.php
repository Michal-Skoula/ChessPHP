<?php

require_once __DIR__ . '/src/Infrastructure/visualizer.php';

use Chess\Domain\Board\Entity\ChessBoard;
use Chess\Domain\Board\Entity\Coordinate;
use Chess\Domain\Board\Exception\MaxBoardSizeException;
use Chess\Domain\Piece\Entity\Rook;
use Chess\Domain\Piece\ValueObject\Enums\PieceType;
use Chess\Infrastructure\Logging\Logger;
use Chess\Infrastructure\Logging\LogLevel;
use Chess\Domain\Game\Entity\Game;


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

$game->playMoveFromAlgebraic('a1', 'a3');
$game->playMoveFromAlgebraic('a3', 'c6');
$game->playMoveFromAlgebraic('c6', 'd8');