<?php

$no_header = 1;
require_once( '../header.inc' );

if( isset( $_REQUEST[ 'u' ] ) and isset( $_REQUEST[ 'p' ] ) ) {
    $username = $db->real_escape_string( $_REQUEST[ 'u' ] );
    $password = $db->real_escape_string( $_REQUEST[ 'p' ] );

    $login_query = 'select id, first, last, password '
        . 'from election_admin '
        . "where username = \"$username\"";
    // print $login_query . "\n";
    $login_request = $db->query( $login_query );
    if( $login_request->num_rows == 1 ) {
        $login_info = $login_request->fetch_object();
        if( password_verify( $password, $login_info->password ) ) {
            print $login_info->id;
            $_SESSION[ 'admin' ] = $login_info->id;
            $_SESSION[ 'name' ] = "$login_info->first $login_info->last";
            $log_query = 'insert into election_admin_logins( id, admin, login_time, ip ) '
                . "values( null, \"$login_info->id\", \""
                . date( 'Y-m-d H:i:s' ) . "\", "
                . "\"{$_SERVER[ 'REMOTE_ADDR' ]}\" )";
            $log_result = $db->query( $log_query );
        }
    }
}
?>
