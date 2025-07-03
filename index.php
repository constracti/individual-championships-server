<?php

require_once 'common.php';

$page = new Page();

$page->body_add( function(): void {
	echo '<form action="organization.php" method="get" class="d-flex flex-row align-items-end">' . "\n";
	echo '<div class="m-2 flex-grow-1">' . "\n";
	echo '<label class="form-label" for="organization">Διοργάνωση</label>' . "\n";
	echo '<input class="form-control" id="organization" name="organization" required="required">' . "\n";
	echo '</div>' . "\n";
	echo '<button class="m-2 btn btn-primary" type="submit">Υποβολή</button>' . "\n";
	echo '</form>' . "\n";
} );

$page->body_add( [ 'Page', 'credits' ] );

$page->print();
