<?php

require_once 'common.php';

// TODO block malicious submissions
// TODO auto-delete old organizations

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	Organization::delete( postStr( 'name' ), postStr( 'password' ) );
	page_redirect( '.' );
}

$page = new Page();

$page->body_add( function(): void {
	echo '<form method="post" class="d-flex flex-column" autocomplete="off">' . "\n";
	echo '<h2 class="m-2">Διαγραφή</h2>' . "\n";
	echo '<div class="m-2">' . "\n";
	echo '<label class="form-label" for="name">Όνομα</label>' . "\n";
	echo '<span class="text-danger">*</span>' . "\n";
	echo '<input class="form-control" id="name" name="name" required="required" maxlength="255" pattern="[\-a-z0-9]+">' . "\n";
	echo '</div>' . "\n";
	echo '<div class="m-2">' . "\n";
	echo '<label class="form-label" for="password">Συνθηματικό</label>' . "\n";
	echo '<input class="form-control" id="password" name="password" type="password">' . "\n";
	echo '</div>' . "\n";
	echo '<div class="d-flex flex-row justify-content-between">' . "\n";
	echo '<button class="m-2 btn btn-danger" type="submit">Υποβολή</button>' . "\n";
	echo '<a href="." class="m-2 btn btn-secondary">Άκυρο</a>' . "\n";
	echo '</div>' . "\n";
	echo '</form>' . "\n";
} );

$page->print();
