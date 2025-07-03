<?php

require_once 'common.php';

$organization = new Organization();

$page = new Page();

$page->body_add( function() use ( $organization ): void {
	echo '<h2 class="m-2">Πρωταθλήματα</h2>' . "\n";
	echo '<div class="m-2 list-group">' . "\n";
	foreach ( $organization->json->championshipList as $championship ) {
		$icon_class = championshipValid( $championship ) ? 'bi-unlock' : 'bi-lock';
		echo '<div class="list-group-item d-flex flex-row p-1 align-items-center">' . "\n";
		echo sprintf( '<span class="m-1 bi %s"></span>', $icon_class ) . "\n";
		echo sprintf( '<span class="m-1 flex-grow-1">%s</span>', $championship->name ) . "\n";
		echo sprintf( '<div class="m-1 badge text-bg-secondary">&le;%d</div>', $championship->unitCap ) . "\n";
		echo '</div>' . "\n";
	}
	echo '</div>' . "\n";
} );

$page->body_add( function() use ( $organization ): void {
	echo '<h2 class="m-2">Επιλογή Ομάδας</h2>' . "\n";
	echo '<div class="list-group m-2">' . "\n";
	$href = page_url( 'team.php', [
		'organization' => $organization->name,
		'team' => NULL,
	] );
	echo sprintf( '<a href="%s" class="list-group-item list-group-item-action">(όλες οι ομάδες)</a>', $href ) . "\n";
	foreach ( $organization->json->teamList as $team ) {
		$href = page_url( 'team.php', [
			'organization' => $organization->name,
			'team' => $team->index,
		] );
		echo sprintf( '<a href="%s" class="list-group-item list-group-item-action">%d. %s</a>', $href, $team->index + 1, $team->name ) . "\n";
	}
	echo '</div>' . "\n";
} );

$page->body_add( [ 'Page', 'credits' ] );

$page->print();
