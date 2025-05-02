<?php

use Chess\Domain\Board\BoardException;
use Chess\Domain\Board\Entity\ChessBoard;
use Chess\Infrastructure\Logging\Logger;
use Chess\Infrastructure\LogLevel;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Infrastructure/helpers.php';


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

try {
	$board = new ChessBoard($layout);

	visualize($board, false);
}
catch (BoardException $e) {
	Logger::log($e->getMessage(), LogLevel::ERROR);
}