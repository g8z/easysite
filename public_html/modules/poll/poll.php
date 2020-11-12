<?php

$manage = false;
require 'config.php';

// get all of the polls by title

$pollsTable = POLLS_TABLE;
$formsTable = FORMS_TABLE;

// show the poll, active or not (since this might just be a test!

$id = intval( $id );

$form_id = $db->getOne( "select form_id from $pollsTable where site_key = '$site' and id = '$id'" );

// this will tell the getForm.php file and submitForm.php file to behave like a poll
$poll = $id;
$t->assign( 'poll', $id );

include_once( FULL_PATH . 'getForm.php' );

?>