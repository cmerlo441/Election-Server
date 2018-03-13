<?php

$no_header = 1;
require_once( '../header.inc' );

$q = 'update election_admin '
    . "set password = \"" . password_hash( 'ballz55', PASSWORD_DEFAULT ) . "\" "
    . "where id = 1";
print "<pre>$q;</pre>\n";
$r = $db->query( $q );

?>
