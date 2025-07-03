<?php

require_once 'class-page.php';
require_once 'class-organization.php';

$collator = new Collator( 'el_GR' );

function enumerated( array $valueList ): array {
	return array_map( function( int $index, $value ): object {
		return (object) [
			'index' => $index,
			'value' => $value,
		];
	}, array_keys( $valueList ), $valueList );
}

function page_url( string $path, array $param_list ): string {
	if ( empty( $param_list ) )
		return $path;
	return $path . '?' . http_build_query( $param_list );
}

function requestStrOrNull( string $key ): ?string {
	if ( !isset( $_GET[$key] ) )
		return NULL;
	return $_GET[$key];
}

function requestStr( string $key ): string {
	$var = requestStrOrNull( $key );
	if ( is_null( $var ) )
		exit( 'requestStr: ' . $key );
	return $var;
}

function requestIntOrNull( string $key ): ?int {
	$var = requestStrOrNull( $key );
	if ( is_null( $var ) )
		return NULL;
	$var = filter_var( $var, FILTER_VALIDATE_INT );
	if ( $var === FALSE )
		exit( 'requestIntOrNull: ' . $key );
	return $var;
}

function requestInt( string $key ): int {
	$var = requestIntOrNull( $key );
	if ( is_null( $var ) )
		exit( 'requestInt: ' . $key );
	return $var;
}

function contestantLabel( object $contestant ): string {
	if ( is_null( $contestant->team ) )
		return $contestant->name;
	return sprintf( '%s (%dη)', $contestant->name, $contestant->team + 1 );
}

function championshipValid( object $championship ): bool {
	if ( count( $championship->roundList ) !== 1 )
		return FALSE;
	$round = $championship->roundList[0];
	if ( !empty( $round->gameList ) )
		return FALSE;
	foreach ( $round->unitList as $unit ) {
		if ( $unit->pass !== FALSE )
			return FALSE;
		if ( !is_null( $unit->parent ) )
			return FALSE;
	}
	return TRUE;
}

function contestantSearch( object $contestant, array $unitList ): ?int {
	foreach ( $unitList as $uid => $unit ) {
		if ( in_array( $contestant->index, $unit->contestantList, TRUE ) )
			return $uid;
	}
	return FALSE;
}
