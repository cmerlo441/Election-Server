<?php

require_once( '../header.inc' );

if( isset( $_SESSION[ 'admin' ] ) ) {
    $logins_query = 'select f.first, f.last, f.banner, '
        . 'l.login_time, l.ip '
        . 'from election_faculty as f, election_logins as l '
        . 'where l.faculty = f.id '
        . 'order by l.login_time desc';
    $logins_result = $db->query( $logins_query );
?>
        <div class="container">
            <div class="row">
                <table class="table col-sm-12">
                    <thead class="thead-dark">
                        <tr>
                            <th>Faculty</th>
                            <th>Login Time</th>
                            <th>Client</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
    while( $login = $logins_result->fetch_object() ) {
        print "                        <tr>\n";
        print "                            <td>$login->first $login->last</td>\n";
        print "                            <td>"
            . date( 'n/j/Y g:i a', strtotime( $login->login_time ) )
            . "</td>\n";
        print "                            <td>$login->ip</td>\n";
        print "                        </tr>\n";
    }
?>
                    </tbody>
<?php
}
?>
