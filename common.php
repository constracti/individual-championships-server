<?php

require_once 'config.php';
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

function page_redirect( string $href ): void {
	header( 'location: ' . $href );
	exit;
}

function exit_ajax( array $array ): void {
	header( 'content-type: application/json' );
	exit( json_encode( $array ) );
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

function postStr( string $key ): string {
	if ( !isset( $_POST[$key] ) )
		exit( 'postStr: ' . $key );
	return $_POST[$key];
}

function postRecaptcha(): void {
	$response = postStr( 'recaptcha' );
	$ch = curl_init( 'https://www.google.com/recaptcha/api/siteverify' );
	if ( $ch === FALSE )
		exit( 'postRecaptcha::curl_init' );
	if ( !curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE ) )
		exit( 'postRecaptcha::curl_setopt: CURLOPT_RETURNTRANSFER' );
	$data = [
		'secret' => RECAPTCHA_SECRET_KEY,
		'response' => $response,
	];
	if ( !curl_setopt( $ch, CURLOPT_POSTFIELDS, $data ) )
		exit( 'postRecaptcha::curl_setopt: CURLOPT_POSTFIELDS' );
	$response = curl_exec( $ch );
	if ( $response === FALSE )
		exit( 'postRecaptcha::curl_exec' );
	$response = json_decode( $response );
	if ( is_null( $response ) )
		exit( 'postRecaptcha::json_decode' );
	if ( !$response->success )
		exit( 'postRecaptcha' );
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
	return NULL;
}
