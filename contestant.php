<?php

require_once 'common.php';

$organization = Organization::load();

$team = $organization->requestTeamOrNull();

$contestant = $organization->requestContestant();

$action = requestStrOrNull( 'action' );

switch ( $action ) {
case 'insert':
	$championship = $organization->requestChampionship();
	$unitList =& $championship->roundList[0]->unitList;
	$uid = requestIntOrNull( 'unit' );
	if ( !contestantSearch( $contestant, $unitList ) ) {
		if ( !is_null( $uid ) ) {
			$contestantList =& $unitList[$uid]->contestantList;
			if ( count( $contestantList ) < $championship->unitCap ) {
				$contestantList[] = $contestant->index;
			}
		} else {
			$unitList[] = (object) [
				'contestantList' => [ $contestant->index ],
				'pass' => FALSE,
				'parent' => NULL,
			];
		}
		$organization->save();
	}
	break;
case 'delete':
	$championship = $organization->requestChampionship();
	$unitList =& $championship->roundList[0]->unitList;
	$uid = contestantSearch( $contestant, $unitList );
	if ( !is_null( $uid ) ) {
		$contestantList =& $unitList[$uid]->contestantList;
		$contestantList = array_filter( $contestantList, function( int $cid ) use ( $contestant ): bool {
			return $cid !== $contestant->index;
		} );
		$contestantList = array_values( $contestantList );
		if ( empty( $contestantList ) ) {
			unset( $unitList[$uid] );
			$unitList = array_values( $unitList );
		}
		$organization->save();
	}
	break;
}

if ( !is_null( $action ) ) {
	$href = page_url( 'contestant.php', [
		'organization' => $organization->name,
		'team' => $team?->index,
		'contestant' => $contestant->index,
	] );
	page_redirect( $href );
}

$page = new Page();

$page->body_add( function() use ( $organization, $team, $contestant ): void {
	echo '<div class="d-flex flex-row justify-content-between align-items-center">' . "\n";
	echo '<h2 class="m-2">Διαγωνιζόμενος</h2>' . "\n";
	$href = page_url( 'team.php', [
		'organization' => $organization->name,
		'team' => $team?->index,
	] );
	echo sprintf( '<a href="%s" class="m-2 btn btn-secondary bi bi-arrow-left"></a>', $href ) . "\n";
	echo '</div>' . "\n";
	echo sprintf( '<div class="m-2">%s</div>', contestantLabel( $contestant ) ) . "\n";
	$championshipList = enumerated( $organization->data->championshipList );
	foreach ( $championshipList as $championship ) {
		if ( !championshipValid( $championship->value ) )
			continue;
		$round = $championship->value->roundList[0];
		echo sprintf( '<h2 class="m-2">%s</h2>', $championship->value->name ) . "\n";
		echo '<div class="d-flex flex-row flex-wrap">' . "\n";
		$unit = array_filter( $round->unitList, function( object $unit ) use ( $contestant ): bool {
			return in_array( $contestant->index, $unit->contestantList, TRUE );
		} );
		$unit = array_values( $unit );
		$unit = count( $unit ) === 1 ? $unit[0] : NULL;
		if ( !is_null( $unit ) ) {
			echo '<div class="m-2 border rounded d-flex flex-row p-1">' . "\n";
			$href = page_url( 'contestant.php', [
				'organization' => $organization->name,
				'team' => $team?->index,
				'contestant' => $contestant->index,
				'action' => 'delete',
				'championship' => $championship->index,
			] );
			echo sprintf( '<a class="bi bi-dash-lg link-danger m-1" href="%s"></a>', $href ) . "\n";
			foreach ( $unit->contestantList as $cid ) {
				$c = $organization->data->contestantList[$cid];
				echo '<div class="m-1 border-start"></div>' . "\n";
				echo sprintf( '<div class="m-1">%s</div>', contestantLabel( $c ) ) . "\n";
			}
			echo '</div>' . "\n";
		} else {
			foreach ( $round->unitList as $uid => $unit ) {
				if ( count( $unit->contestantList ) >= $championship->value->unitCap )
					continue;
				echo '<div class="m-2 border rounded d-flex flex-row p-1">' . "\n";
				$href = page_url( 'contestant.php', [
					'organization' => $organization->name,
					'team' => $team?->index,
					'contestant' => $contestant->index,
					'action' => 'insert',
					'championship' => $championship->index,
					'unit' => $uid,
				] );
				echo sprintf( '<a class="bi bi-plus-lg link-success m-1" href="%s"></a>', $href ) . "\n";
				foreach ( $unit->contestantList as $cid ) {
					$c = $organization->data->contestantList[$cid];
					echo '<div class="m-1 border-start"></div>' . "\n";
					echo sprintf( '<div class="m-1">%s</div>', contestantLabel( $c ) ) . "\n";
				}
				echo '</div>' . "\n";
			}
			echo '<div class="m-2 border rounded d-flex flex-row p-1">' . "\n";
			$href = page_url( 'contestant.php', [
				'organization' => $organization->name,
				'team' => $team?->index,
				'contestant' => $contestant->index,
				'action' => 'insert',
				'championship' => $championship->index,
			] );
			echo sprintf( '<a class="bi bi-plus-lg link-success m-1" href="%s"></a>', $href ) . "\n";
			echo '</div>' . "\n";
		}
		echo '</div>' . "\n";
	}
} );

$page->print();
