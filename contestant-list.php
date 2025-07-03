<?php

require_once 'common.php';

$organization = new Organization();

$team = $organization->requestTeamOrNull();

$page = new Page( 'Επιλογή Διαγωνιζόμενου' );

$page->body_add( function() use ( $organization, $team ): void {
	echo '<div class="list-group m-2">' . "\n";
	$contestantList = array_filter( $organization->json->contestantList, function( object $contestant ) use ( $team ) : bool {
		return is_null( $team ) || $contestant->team === $team->index;
	} );
	usort( $contestantList, function( object $contestant1, object $contestant2 ): int {
		global $collator;
		return $collator->compare( $contestant1->name, $contestant2->name );
	} );
	foreach ( $contestantList as $contestant ) {
		if ( !is_null( $team ) && $contestant->team !== $team->index )
			continue;
		$href = page_url( 'championship-list.php', [
			'organization' => $organization->name,
			'team' => $team?->index,
			'contestant' => $contestant->index,
		] );
		echo sprintf( '<a href="%s" class="list-group-item list-group-item-action">%s</a>', $href, contestantLabel( $contestant ) ) . "\n";
	}
	echo '</div>' . "\n";
} );

$page->print();
