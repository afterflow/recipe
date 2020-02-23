<?php

namespace Afterflow\Recipe;

function q( $what ) {
	return "'" . $what . "'";
}

function qq( $what ) {
	return '"' . $what . '"';
}

function eol( $times = 1 ) {
	$str = '';
	for ( $i = 0; $i < $times; $i ++ ) {
		$str .= PHP_EOL;
	}

	return $str;
}

function arr( $what, $d = [ '[', ']' ] ) {
	return Recipe::array( $what, $d );
}
