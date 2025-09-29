<?php

require_once 'common.php';

$organization = Organization::load();

$page = new Page();

$page->body_add( function() use ( $organization ): void {
	echo '<h2 class="m-2">Πρωταθλήματα</h2>' . "\n";
	echo '<div class="m-2 list-group">' . "\n";
	foreach ( $organization->data->championshipList as $championship ) {
		$icon_class = championshipValid( $championship ) ? 'bi-unlock' : 'bi-lock';
		echo '<div class="list-group-item d-flex flex-column p-1">' . "\n";
		echo '<div class="d-flex flex-row align-items-center">' . "\n";
		echo sprintf( '<span class="m-1 bi %s"></span>', $icon_class ) . "\n";
		echo sprintf( '<span class="m-1 flex-grow-1">%s</span>', $championship->name ) . "\n";
		echo sprintf( '<div class="m-1 badge text-bg-secondary">&le;%d</div>', $championship->unitCap ) . "\n";
		echo '</div>' . "\n";
		if ( $championship->info !== '' )
			echo sprintf( '<small class="m-1 fst-italic">%s</small>', $championship->info ) . "\n";
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
	$count = count( $organization->data->contestantList );
	echo sprintf( '<a href="%s" class="list-group-item list-group-item-action d-flex flex-row p-1 align-items-center">', $href ) . "\n";
	echo '<span class="m-1 flex-grow-1">(όλες οι ομάδες)</span>' . "\n";
	echo sprintf( '<span class="m-1 badge text-bg-secondary">%d</span>', $count ) . "\n";
	echo '</a>' . "\n";
	foreach ( $organization->data->teamList as $team ) {
		$href = page_url( 'team.php', [
			'organization' => $organization->name,
			'team' => $team->index,
		] );
		$count = count( array_filter( $organization->data->contestantList, function( object $contestant ) use ( $team ): bool {
			return $contestant->team === $team->index;
		} ) );
		echo sprintf( '<a href="%s" class="list-group-item list-group-item-action d-flex flex-row p-1 align-items-center">', $href ) . "\n";
		echo sprintf( '<span class="m-1 flex-grow-1">%d. %s</span>', $team->index + 1, $team->name ) . "\n";
		echo sprintf( '<span class="m-1 badge text-bg-secondary">%d</span>', $count ) . "\n";
		echo '</a>' . "\n";
	}
	echo '</div>' . "\n";
} );

$page->body_add( function() use ( $organization ): void {
	echo '<h2 class="m-2">Εργαλεία</h2>' . "\n";
	echo '<div class="d-flex flex-row">' . "\n";
	$href = page_url( 'export.php', [
		'organization' => $organization->name,
	] );
	echo sprintf( '<a href="%s" class="m-2 btn btn-secondary">Εξαγωγή</a>', $href ) . "\n";
	echo '</div>' . "\n";
} );

$page->body_add( [ 'Page', 'credits' ] );

$page->print();
