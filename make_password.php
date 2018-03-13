<?php

$no_header = 1;
require_once( './header.inc' );

$q = 'update election_faculty '
    . "set password = \"" . password_hash( 'cgwttLm', PASSWORD_DEFAULT ) . "\" "
    . "where id = 6";
print "<pre>$q;</pre>\n";
$r = $db->query( $q );

?>
