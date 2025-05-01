<?php

use Chess\Domain\Board\Entity\ChessBoard;

require_once __DIR__ . '/vendor/autoload.php';


/**
 * Layout definition from which to construct the chess board.
 *
 * The layout is constructed from chars as defined by LayoutCharParser.
 * For an empty square, an `_` is used.
 */
$layout = [
	['r','n','b','q','k','b','n','r'],
	['p','p','p','p','p','p','p','p'],
	['_','_','_','_','_','_','_','_'],
	['_','_','_','_','_','_','_','_'],
	['_','_','_','_','_','_','_','_'],
	['_','_','_','_','_','_','_','_'],
	['P','P','P','P','P','P','P','P'],
	['R','N','B','Q','K','B','N','R'],
];



$board = new ChessBoard($layout);

var_dump($board->board);
