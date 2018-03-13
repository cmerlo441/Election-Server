<?php

$no_header = 1;
require_once( './header.inc' );

$faculty_email = $db->real_escape_string( $_REQUEST[ 'faculty_email' ] );
$names = preg_split( "/[\.@]/", $faculty_email );
$faculty_query = 'select id from election_faculty '
    . "where first = \"{$names[ 0 ]}\" and last = \"{$names[ 1 ]}\"";
$faculty_result = $db->query( $faculty_query );
if( $faculty_result->num_rows == 1 ) {
    $faculty_row = $faculty_result->fetch_object();
    $id = $faculty_row->id;
    $num = sprintf( '%06d', rand( 0, 999999 ) );
    $temp_query = 'insert into election_forgot_pw( id, faculty, timestamp, temp ) '
        . "values( null, \"$id\", \"" . date( 'Y-m-d H:i:s' ) . "\", \"$num\" )";
    $db->query( $temp_query );
    print $db->affected_rows;

    $subject = 'Election Server Temporary Password';
    $message = '<p>Hi!  It looks like you recently requested a password change on '
        . 'the departmental election server.  If this is true, then go to the '
        . "<a href=\"{$_SERVER[ 'SERVER_NAME' ]}$docroot\">Election Server's Homepage</a> and use this code "
        . '<b>instead</b> of your password, and you will be prompted to enter a '
        . "new password.</p>\n\n"
        . '<p>If you did <i>not</i> request this, then you can safely do nothing.  '
        . "Your password has not been changed.</p>\n\n"
        . "<p>Your code is $num.  It will expire in ten minutes.</p>";
    $header = 'Content-Type: text/html; charset=UTF-8\r\n'
        . "From: MAT/CSC/ITE Election Server <christopher.merlo@ncc.edu>";
    mail( $faculty_email, $subject, $message, $header );
}

?>
