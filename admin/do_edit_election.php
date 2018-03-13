<?php

$no_header = 1;
require_once( '../header.inc' );
if( isset( $_SESSION[ 'admin' ] ) ) {
    $id = $db->real_escape_string( $_REQUEST[ 'id' ] );
    $title = $db->real_escape_string( $_REQUEST[ 'title' ] );
    $delegates = $db->real_escape_string( $_REQUEST[ 'delegates' ] );
    $alternates = $db->real_escape_string( $_REQUEST[ 'alternates' ] );
    $start = date( 'Y-m-d H:i:s', strtotime( $db->real_escape_string( $_REQUEST[ 'start' ] ) ) );
    $end = date( 'Y-m-d H:i:s', strtotime( $db->real_escape_string( $_REQUEST[ 'end' ] ) ) );
    $candidates_string = $db->real_escape_string( $_REQUEST[ 'candidates' ] );
    $candidates = explode(',', $candidates_string );

    $update_query = 'update election_elections '
        . "set title=\"$title\", delegates=\"$delegates\", alternates=\"$alternates\", "
        . "starttime=\"$start\", endtime=\"$end\" "
        . "where id=\"$id\"";
    print "$update_query;";
    $update_result = $db->query( $update_query );
}
?>
