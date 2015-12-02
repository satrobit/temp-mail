<?php
include 'tempmail.class.php';
$tempmail = new tempmail ();

$tempmail->setmail (); // Generates a random email address .
$tempmail->setmail ( '0iff0dhn6b@vkcode.ru' ); // Sets a predefined email address out of available domains .
echo $tempmail::$emailaddress; // Outputs current email address .
var_dump ( $tempmail->getmails () ); // Outputs inbox . Run a foreach to process each email at a time .
var_dump ( $tempmail->getsources () ); // Outputs the source of each email .
var_dump ( $tempmail->getmails ( '0iff0dhn6b@vkcode.ru' ) ); // Outputs inbox of an email without setting an email address .
var_dump ( $tempmail->getsources ( '0iff0dhn6b@vkcode.ru' ) ); // Outputs the source of each email without setting an email address .
$tempmail->delete ( '5556655' ); // Deletes an email . Output is boolean type .
var_dump ( $tempmail->domains () ); // Outputs available domains .

?>
