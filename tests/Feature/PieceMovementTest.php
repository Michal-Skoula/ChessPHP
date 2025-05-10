<?php

use Chess\Domain\Board\Entity\ChessBoard;
use Chess\Domain\Board\Entity\Coordinate;
use Chess\Domain\Move\Service\MovesAsArrayService;

/**
 * @param  array{
 *     'moves': array<string>,
 *     'attacks': array<string>
 * }  $coordinates
 * @param  array<string>  $moves
 * @param  array<string>  $attacks
 * @return void
 */
function sortAndAssert(array $coordinates, array $moves, array $attacks): void
{
	sort($coordinates['moves']);
	sort($coordinates['attacks']);

	sort($moves);
	sort($attacks);

	expect($coordinates['moves'])->toBe($moves);
	expect($coordinates['attacks'])->toBe($attacks);
}


test('Pawns move as expected', function () {
	$layout = [
	//    a   b   c   d   e   f   g   h
		['_','_','_','_','_','_','_','_'], // 1
		['_','_','_','p','_','_','_','_'], // 2
		['_','_','P','_','P','_','_','_'], // 3
		['_','_','_','_','_','_','_','_'], // 4
		['_','_','_','_','_','_','_','_'], // 5
		['_','_','_','_','_','_','_','_'], // 6
		['_','_','_','_','_','_','_','_'], // 7
		['_','_','_','_','_','_','_','_'], // 8
	];

	$coordinates = new MovesAsArrayService(
		board: new ChessBoard($layout),
		pieceCoords: Coordinate::fromAlgebraic('d2')
	)->getAllMoves(true);


	$moves = ['d3'];
	$attacks = ['c3', 'e3'];

	sortAndAssert($coordinates, $moves, $attacks);
});

test('Knights move as expected', function () {
	$layout = [
	//    a   b   c   d   e   f   g   h
		['_','_','_','_','p','_','_','_'], // 1
		['_','_','_','_','_','p','_','_'], // 2
		['_','_','_','N','_','_','_','_'], // 3
		['_','_','_','_','_','_','_','_'], // 4
		['_','_','p','_','p','_','_','_'], // 5
		['_','_','_','_','_','_','_','_'], // 6
		['_','_','_','_','_','_','_','_'], // 7
		['_','_','_','_','_','_','_','_'], // 8
	];

	$coordinates = new MovesAsArrayService(
		board: new ChessBoard($layout),
		pieceCoords: Coordinate::fromAlgebraic('d3')
	)->getAllMoves(true);


	$moves = ['b2', 'b4', 'c1', 'f4'];
	$attacks = ['c5', 'e1', 'e5', 'f2'];

	sortAndAssert($coordinates, $moves, $attacks);
});

test('Bishops move as expected', function () {
	$layout = [
	//    a   b   c   d
		['p','_','_','_'], // 1
		['_','B','_','_'], // 2
		['p','_','p','_'], // 3
		['_','_','_','_'], // 4
	];

	$coordinates = new MovesAsArrayService(
		board: new ChessBoard($layout, 4,4),
		pieceCoords: Coordinate::fromAlgebraic('b2')
	)->getAllMoves(true);


	$moves = ['c1'];
	$attacks = ['a1','a3','c3'];

	var_dump($coordinates['moves']);


	sortAndAssert($coordinates, $moves, $attacks);
});

test('Rooks move as expected', function () {
	$layout = [
	//    a   b   c   d
		['_','_','_','_'], // 1
		['p','R','_','_'], // 2
		['_','p','_','_'], // 3
		['_','_','_','_'], // 4
	];

	$coordinates = new MovesAsArrayService(
		board: new ChessBoard($layout, 4,4),
		pieceCoords: Coordinate::fromAlgebraic('b2')
	)->getAllMoves(true);


	$moves = ['b1','c2','d2'];
	$attacks = ['a2','b3'];

	var_dump($coordinates['moves']);

	sortAndAssert($coordinates, $moves, $attacks);
});

test('Queen moves as expected', function () {
	$layout = [
	//    a   b   c   d
		['_','_','p','_'], // 1
		['p','Q','_','_'], // 2
		['_','p','_','_'], // 3
		['_','_','_','_'], // 4
	];

	$coordinates = new MovesAsArrayService(
		board: new ChessBoard($layout, 4,4),
		pieceCoords: Coordinate::fromAlgebraic('b2')
	)->getAllMoves(true);


	$moves = ['b1','c2','d2','c3','d4','a3','a1'];
	$attacks = ['a2','b3','c1'];

	sortAndAssert($coordinates, $moves, $attacks);
});

test('King moves as expected', function () {
	$layout = [
	//    a   b   c   d
		['_','p','_','p'], // 1
		['_','_','K','_'], // 2
		['_','_','p','_'], // 3
		['_','_','_','_'], // 4
	];

	$coordinates = new MovesAsArrayService(
		board: new ChessBoard($layout, 4,4),
		pieceCoords: Coordinate::fromAlgebraic('c2')
	)->getAllMoves(true);


	$moves = ['c1','d2','d3','b2','b3'];
	$attacks = ['b1','d1','c3'];

	sortAndAssert($coordinates, $moves, $attacks);
});
