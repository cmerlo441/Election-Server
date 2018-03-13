<?php

$no_header = 1;
require_once( './header.inc' );
if( isset( $_SESSION[ 'user' ] ) ) {
    $election = $db->real_escape_string( $_REQUEST[ 'election_id' ] );
    $ballot = $db->real_escape_string( $_REQUEST[ 'ballot' ] );

    $voted_query = 'select timestamp '
        . 'from election_voted '
        . "where election = \"$election\" "
        . "and faculty = \"{$_SESSION[ 'user' ]}\"";
    $voted_result = $db->query( $voted_query );
    if( $voted_result->num_rows == 1 ) {
        $voted = $voted_result->fetch_object();
        print "You already voted in this election on "
            . date( 'l n/j \a\t g:i a', strtotime( $voted->timestamp ) )
            . ".";
    } else {
        $vote_query = 'insert into election_ballots ( id, election, ballot ) '
            . "values( null, \"$election\", \"$ballot\" )";
        $vote_result = $db->query( $vote_query );
        print "Your ballot has been recorded!";
        $voted_query = 'insert into election_voted ( id, election, faculty, timestamp ) '
            . "values( null, \"$election\", \"{$_SESSION[ 'user' ]}\", \""
            . date( 'Y-m-d H:i:s' ) . "\" )";
        $voted_result = $db->query( $voted_query );
    }
}

?>
