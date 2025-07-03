<?php


class Page {

	private $title;
	private $body_list;

	function __construct( string $title ) {
		$this->title = $title;
		$this->body_list = [];
	}

	function body_add( callable $function ): void {
		$this->body_list[] = $function;
	}

	function print(): void {
		echo '<!doctype html>' . "\n";
		echo '<html>' . "\n";
		echo '<head>' . "\n";
		echo '<meta charset="utf-8">' . "\n";
		echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
		echo '<title>Ατομικά Πρωταθλήματα</title>' . "\n";
		echo '<link rel="shortcut icon" href="strategy.png">' . "\n";
		echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">' . "\n";
		echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">' . "\n";
		echo '</head>' . "\n";
		echo '<body class="container d-flex flex-column p-2">' . "\n";
		echo sprintf( '<h1 class="m-2">%s</h1>', $this->title ) . "\n";
		foreach ( $this->body_list as $body ) {
			$body();
		}
		echo '</body>' . "\n";
		echo '</html>' . "\n";
	}
}


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
