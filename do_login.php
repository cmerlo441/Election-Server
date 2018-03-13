<?php

$no_header = 1;
require_once( './header.inc' );

if( isset( $_REQUEST[ 'u' ] ) and isset( $_REQUEST[ 'p' ] ) ) {
    $email = $db->real_escape_string( $_REQUEST[ 'u' ] );
    $password = $db->real_escape_string( $_REQUEST[ 'p' ] );
    $names = preg_split( "/[\.@]/", $email );

    // check for temp code
    $forgot_pw_query = 'select fp.faculty, fp.temp '
        . 'from election_forgot_pw as fp, election_faculty as f '
        . 'where fp.faculty = f.id '
        . "and f.first = \"{$names[ 0 ]}\" and f.last = \"{$names[ 1 ]}\" "
        . "and fp.temp = \"$password\"";
    $forgot_pw_result = $db->query( $forgot_pw_query );
    if( $forgot_pw_result->num_rows == 1 ) {
        $forgot_row = $forgot_pw_result->fetch_object();
        $fac_id = $forgot_row->faculty;
        $code = $forgot_row->temp;
        print "$fac_id:$code";
    } else {
        $login_query = 'select id, first, last, banner, password '
            . 'from election_faculty '
            . "where first = \"{$names[ 0 ]}\" and last = \"{$names[ 1 ]}\"";
        $login_request = $db->query( $login_query );
        if( $login_request->num_rows == 1 ) {
            $login_info = $login_request->fetch_object();
            if( password_verify( $password, $login_info->password ) ) {
                $_SESSION[ 'user' ] = $login_info->id;
                $faculty = new Faculty( $login_info->id, $login_info->first, $login_info->last,    $login_info->banner );
                $_SESSION[ 'name' ] = $faculty->__toString();
                $log_query = 'insert into election_logins( id, faculty, login_time, ip ) '
                    . "values( null, \"$login_info->id\", \""
                    . date( 'Y-m-d H:i:s' ) . "\", "
                    . "\"{$_SERVER[ 'REMOTE_ADDR' ]}\" )";
                $log_result = $db->query( $log_query );
            }
        }
    }
}
?>
