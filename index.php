<?php

require_once 'common.php';

$page = new Page();

$page->body_add( function(): void {
	echo '<h2 class="m-2">Επιλογή</h2>' . "\n";
	echo '<form action="organization.php" method="get" class="d-flex flex-row align-items-end" autocomplete="off">' . "\n";
	echo '<div class="m-2 flex-grow-1">' . "\n";
	echo '<label class="form-label" for="organization">Διοργάνωση</label>' . "\n";
	echo '<input class="form-control" id="organization" name="organization" required="required">' . "\n";
	echo '</div>' . "\n";
	echo '<button class="m-2 btn btn-primary" type="submit">Επιλογή</button>' . "\n";
	echo '</form>' . "\n";
} );

$page->body_add( function(): void {
	echo '<h2 class="m-2">Διαχείριση</h2>' . "\n";
	echo '<div class="d-flex flex-row">' . "\n";
	echo '<a href="create.php" class="m-2 btn btn-secondary">Δημιουργία</a>' . "\n";
	echo '</div>' . "\n";
} );

$page->body_add( [ 'Page', 'credits' ] );

$page->print();
