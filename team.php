<?php

require_once 'common.php';

$organization = Organization::load();

$team = $organization->requestTeamOrNull();

$page = new Page();

$page->body_add( function() use ( $organization, $team ): void {
	echo '<div class="d-flex flex-row justify-content-between align-items-center">' . "\n";
	echo '<h2 class="m-2">Ομάδα</h2>' . "\n";
	$href = page_url( 'organization.php', [
		'organization' => $organization->name,
	] );
	echo sprintf( '<a href="%s" class="m-2 btn btn-secondary bi bi-arrow-left"></a>', $href ) . "\n";
	echo '</div>' . "\n";
	echo sprintf( '<p class="m-2">%s</p>', $team?->name ?? '(όλες οι ομάδες)' ) . "\n";
	echo '<h2 class="m-2">Επιλογή Διαγωνιζόμενου</h2>' . "\n";
	echo '<div class="list-group m-2">' . "\n";
	$contestantList = array_filter( $organization->data->contestantList, function( object $contestant ) use ( $team ) : bool {
		return is_null( $team ) || $contestant->team === $team->index;
	} );
	usort( $contestantList, function( object $contestant1, object $contestant2 ): int {
		global $collator;
		return $collator->compare( $contestant1->name, $contestant2->name );
	} );
	foreach ( $contestantList as $contestant ) {
		if ( !is_null( $team ) && $contestant->team !== $team->index )
			continue;
		$href = page_url( 'contestant.php', [
			'organization' => $organization->name,
			'team' => $team?->index,
			'contestant' => $contestant->index,
		] );
		echo sprintf( '<a href="%s" class="list-group-item list-group-item-action">%s</a>', $href, contestantLabel( $contestant ) ) . "\n";
	}
	echo '</div>' . "\n";
} );

$page->print();
