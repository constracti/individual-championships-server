<?php

require_once 'common.php';

// TODO block malicious submissions
// TODO escape html characters

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	$organization = Organization::import( postStr( 'name' ), postStr( 'password' ), postStr( 'text' ) );
	$href = page_url( 'organization.php', [
		'organization' => $organization->name,
	] );
	page_redirect( $href );
}

$page = new Page();

$page->body_add( function(): void {
	echo '<form method="post" class="d-flex flex-column" autocomplete="off">' . "\n";
	echo '<h2 class="m-2">Εισαγωγή</h2>' . "\n";
	echo '<div class="m-2">' . "\n";
	echo '<label class="form-label" for="name">Όνομα</label>' . "\n";
	echo '<span class="text-danger">*</span>' . "\n";
	echo '<input class="form-control" id="name" name="name" required="required" maxlength="255" pattern="[\-a-z0-9]+">' . "\n";
	echo '<div class="form-text">Επιτρέπονται μόνο πεζοί λατινικοί χαρακτήρες, αριθμητικά ψηφία και παύλες ("-").</div>' . "\n";
	echo '</div>' . "\n";
	echo '<div class="m-2">' . "\n";
	echo '<label class="form-label" for="password">Συνθηματικό</label>' . "\n";
	echo '<input class="form-control" id="password" name="password" type="password">' . "\n";
	echo '<div class="form-text">Ένας κατακερματισμός (hash) του συνθηματικού θα αποθηκευθεί μαζί με το περιεχόμενο.</div>' . "\n";
	echo '</div>' . "\n";
	echo '<div class="m-2">' . "\n";
	echo '<label class="form-label" for="text">Περιεχόμενο</label>' . "\n";
	echo '<span class="text-danger">*</span>' . "\n";
	echo '<textarea class="form-control" id="text" name="text" required="required" style="resize: none;" rows="12"></textarea>' . "\n";
	echo '<div class="form-text">Το περιεχόμενο πρέπει να ακολουθεί <a href="https://github.com/constracti/individual-championships" target="_blank">συγκεκριμένη μορφή JSON</a>.</div>' . "\n";
	echo '</div>' . "\n";
	echo '<div class="d-flex flex-row justify-content-between">' . "\n";
	echo '<button class="m-2 btn btn-primary" type="submit">Υποβολή</button>' . "\n";
	echo '<a href="." class="m-2 btn btn-secondary">Άκυρο</a>' . "\n";
	echo '</div>' . "\n";
	echo '</form>' . "\n";
} );

$page->print();
