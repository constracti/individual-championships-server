<?php


class Organization {

	const PATH = '../json';

	var $name;
	var $time;
	var $json;

	function __construct() {
		$name = requestStr( 'organization' );
		$file_list = scandir( self::PATH );
		if ( $file_list === FALSE )
			exit( 'Organization::__construct: scandir' );
		$file_list = array_filter( $file_list, function( string $file ) use ( $name ): bool {
			return str_ends_with( $file, '.json' ) && ( $file === $name . '.json' );
		} );
		$file_list = array_values( $file_list );
		if ( count( $file_list ) !== 1 )
			exit( 'Organization::__construct: count' );
		$path = self::PATH . '/' . $file_list[0];
		$time = filemtime( $path );
		if ( $time === FALSE )
			exit( 'Organizaton::__construct: filemtime' );
		$text = file_get_contents( $path );
		if ( $text === FALSE )
			exit( 'Organization::__construct: file_get_contents' );
		$json = json_decode( $text );
		if ( is_null( $json ) )
			exit( 'Organization::__construct: json_decode' );
		$this->name = $name;
		$this->time = $time;
		$this->json = $json;
	}

	function save(): void {
		$json = $this->json;
		$text = json_encode( $json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
		if ( $text === FALSE )
			exit( 'Organization::save: json_encode' );
		$text = mb_ereg_replace( '    ', "\t", $text );
		if ( $text === FALSE )
			exit( 'Organization::save: mb_ereg_replace' );
		$path = self::PATH . '/' . $this->name . '.json';
		if ( file_put_contents( $path, $text ) === FALSE )
			exit( 'Organization::save: file_put_contents' );
	}

	function requestTeamOrNull(): ?object {
		$var = requestIntOrNull( 'team' );
		if ( is_null( $var ) )
			return NULL;
		if ( $var < 0 || $var >= count( $this->json->teamList ) )
			exit( 'Organization::requestTeamOrNull' );
		return $this->json->teamList[$var];
	}

	function requestContestant(): object {
		$var = requestInt( 'contestant' );
		if ( $var < 0 || $var >= count( $this->json->contestantList ) )
			exit( 'Organization::requestContestant' );
		return $this->json->contestantList[$var];
	}

	function requestChampionship(): object {
		$var = requestInt( 'championship' );
		if ( $var < 0 || $var >= count( $this->json->championshipList ) )
			exit( 'Organization::requestChampionship' );
		$var = $this->json->championshipList[$var];
		if ( !championshipValid( $var ) )
			exit( 'Organization::requestChampionship' );
		return $var;
	}
}
