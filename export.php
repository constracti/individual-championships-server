<?php

require_once 'common.php';

$organization = Organization::load();

$page = new Page();

$page->body_add( function() use ( $organization ): void {
	echo '<div class="d-flex flex-row justify-content-between align-items-center">' . "\n";
	echo '<h2 class="m-2">Εξαγωγή</h2>' . "\n";
	$href = page_url( 'organization.php', [
		'organization' => $organization->name,
	] );
	echo sprintf( '<a href="%s" class="m-2 btn btn-secondary bi bi-arrow-left"></a>', $href ) . "\n";
	echo '</div>' . "\n";
	echo '<div class="m-2">' . "\n";
	$text = json_encode( $organization->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
	$text = mb_ereg_replace( '    ', "\t", $text );
	echo sprintf( '<textarea class="form-control" style="resize: none;" rows="12" readonly="readonly">%s</textarea>', $text ) . "\n";
	echo '</div>' . "\n";
} );

$page->print();
