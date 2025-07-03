<?php

require_once 'common.php';

$organization = new Organization();

$page = new Page( 'Επιλογή Ομάδας' );

$page->body_add( function() use ( $organization ): void {
	echo '<div class="list-group m-2">' . "\n";
	$href = page_url( 'contestant-list.php', [
		'organization' => $organization->name,
		'team' => NULL,
	] );
	echo sprintf( '<a href="%s" class="list-group-item list-group-item-action">(όλες οι ομάδες)</a>', $href ) . "\n";
	foreach ( $organization->json->teamList as $team ) {
		$href = page_url( 'contestant-list.php', [
			'organization' => $organization->name,
			'team' => $team->index,
		] );
		echo sprintf( '<a href="%s" class="list-group-item list-group-item-action">%d. %s</a>', $href, $team->index + 1, $team->name ) . "\n";
	}
	echo '</div>' . "\n";
} );

$page->print();
