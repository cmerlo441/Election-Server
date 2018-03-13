<?php

$no_header = 1;
require_once( './header.inc' );
$fac_id = $db->real_escape_string( $_REQUEST[ 'fac_id' ] );
$code = $db->real_escape_string( $_REQUEST[ 'code' ] );
$pw = $db->real_escape_string( $_REQUEST[ 'pw' ] );

$code_query = 'select id '
    . 'from election_forgot_pw '
    . "where faculty = \"$fac_id\" "
    . "and temp = \"$code\"";
$code_result = $db->query( $code_query );
if( $code_result->num_rows == 1 ) {

    $password_query = 'update election_faculty '
        . "set password = \"" . password_hash( $pw, PASSWORD_DEFAULT ) . "\" "
        . "where id = \"$fac_id\"";
    $db->query( $password_query );

    $del_code_query = 'delete from election_forgot_pw '
        . "where faculty = \"$fac_id\"";
    $db->query( $del_code_query );

    print 1;
}
?>
