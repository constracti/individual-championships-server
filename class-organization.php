<?php


class Organization {

	const PATH = '../json';

	var $name;
	var $hash;
	var $time;
	var $data;

	private function __construct( string $name, string $hash, int $time, object $data ) {
		$this->name = $name;
		$this->hash = $hash;
		$this->time = $time;
		$this->data = $data;
	}

	private static function filterName( string $name ): void {
		$var = filter_var( $name, FILTER_VALIDATE_REGEXP, [
			'options' => [
				'default' => NULL,
				'regexp' => '/^[-a-z0-9]{1,255}$/',
			],
		] );
		if ( is_null( $var ) )
			exit( 'Organization::filterName: filter_var');
	}

	static function import( string $name, string $password, string $text ): Organization {
		Organization::filterName( $name );
		$hash = password_hash( $password, PASSWORD_DEFAULT );
		$data = json_decode( $text );
		if ( is_null( $data ) )
			exit( 'Organization::import: json_decode' );
		$time = time();
		$organization = new Organization( $name, $hash, $time, $data );
		$path = self::PATH . '/' . $name . '.json';
		if ( file_exists( $path ) ) {
			$text = file_get_contents( $path );
			if ( $text === FALSE )
				exit( 'Organization::import: file_get_contents' );
			$json = json_decode( $text );
			if ( is_null( $json ) )
				exit( 'Organization::import: json_decode' );
			if ( !password_verify( $password, $json->hash ) )
				exit( 'Organization::import: password_verify' );
		}
		$organization->save();
		return $organization;
	}

	static function delete( string $name, string $password ): void {
		Organization::filterName( $name );
		$path = self::PATH . '/' . $name . '.json';
		if ( !file_exists( $path ) )
			exit( 'Organization::delete: file_exists' );
		$text = file_get_contents( $path );
		if ( $text === FALSE )
			exit( 'Organization::delete: file_get_contents' );
		$json = json_decode( $text );
		if ( is_null( $json ) )
			exit( 'Organization::delete: json_decode' );
		if ( !password_verify( $password, $json->hash ) )
			exit( 'Organization::delete: password_verify' );
		if  ( unlink( $path ) === FALSE )
			exit( 'Organization::delete: unlink' );
	}

	static function load(): Organization {
		$name = requestStr( 'organization' );
		Organization::filterName( $name );
		$path = self::PATH . '/' . $name . '.json';
		if ( !file_exists( $path ) )
			exit( 'Organization::load: file_exists' );
		$text = file_get_contents( $path );
		if ( $text === FALSE )
			exit( 'Organization::load: file_get_contents' );
		$json = json_decode( $text );
		if ( is_null( $json ) )
			exit( 'Organization::load: json_decode' );
		return new Organization( $name, $json->hash, $json->time, $json->data );
	}

	function save(): void {
		$json = (object) [
			'hash' => $this->hash,
			'time' => $this->time,
			'data' => $this->data,
		];
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
		if ( $var < 0 || $var >= count( $this->data->teamList ) )
			exit( 'Organization::requestTeamOrNull' );
		return $this->data->teamList[$var];
	}

	function requestContestant(): object {
		$var = requestInt( 'contestant' );
		if ( $var < 0 || $var >= count( $this->data->contestantList ) )
			exit( 'Organization::requestContestant' );
		return $this->data->contestantList[$var];
	}

	function requestChampionship(): object {
		$var = requestInt( 'championship' );
		if ( $var < 0 || $var >= count( $this->data->championshipList ) )
			exit( 'Organization::requestChampionship' );
		$var = $this->data->championshipList[$var];
		if ( !championshipValid( $var ) )
			exit( 'Organization::requestChampionship' );
		return $var;
	}
}
