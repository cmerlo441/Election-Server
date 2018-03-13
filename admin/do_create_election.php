<?php

$no_header = 1;
require_once( '../header.inc' );
if( isset( $_SESSION[ 'admin' ] ) ) {
    $title = $db->real_escape_string( $_REQUEST[ 'title' ] );
    $delegates = $db->real_escape_string( $_REQUEST[ 'delegates' ] );
    $alternates = $db->real_escape_string( $_REQUEST[ 'alternates' ] );
    $start = date( 'Y-m-d H:i:s', strtotime( $db->real_escape_string( $_REQUEST[ 'start' ] ) ) );
    $end = date( 'Y-m-d H:i:s', strtotime( $db->real_escape_string( $_REQUEST[ 'end' ] ) ) );
    $candidates_string = $db->real_escape_string( $_REQUEST[ 'candidates' ] );
    $candidates = explode(',', $candidates_string );

    $insert_query = 'insert into election_elections '
        . '( id, title, delegates, alternates, starttime, endtime ) '
        . "values( null, \"$title\", \"$delegates\", \"$alternates\", "
        . "\"$start\", \"$end\" )";
    $insert_result = $db->query( $insert_query );
    $id = $db->insert_id;
    foreach( $candidates as $key => $value ) {
        $can_query = 'insert into election_candidates ( id, election, faculty ) '
            . "values( null, \"$id\", \"$value\" )";
        $can_result = $db->query( $can_query );
    }
}
?>
