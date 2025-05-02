<?php

namespace Chess\Domain\Piece\Interface;

interface SpecialMoves
{
	/**
	 * Array of function names to be used for custom move logic.
	 * @return void
	 */
	function setSpecialMoves(): void;
}