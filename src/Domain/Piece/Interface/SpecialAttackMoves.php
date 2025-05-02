<?php

namespace Chess\Domain\Piece\Interface;

interface SpecialAttackMoves
{
	/**
	 * Array of function names to be used for custom attack move logic.
	 * @return void
	 */
	function setSpecialAttackMoves(): void;
}