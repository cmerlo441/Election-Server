<?php

require_once( './header.inc' );
if( isset( $_SESSION[ 'user' ] ) ) {
    $logins_query = 'select login_time, ip from election_logins '
        . 'order by login_time desc';
    $logins_result = $db->query( $logins_query );
?>
        <div class="container">
            <h1><?php echo $_SESSION[ 'name' ]; ?>'s Profile</h1>
            <h2 class="mt-4">Login History</h2>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Client</th>
                    </tr>
                </thead>
                <tbody>
<?php
    while( $l = $logins_result->fetch_object() ) {
?>
                    <tr>
                        <td><?php echo date( 'n/j/y', strtotime( $l->login_time ) ); ?></td>
                        <td><?php echo date( 'g:i a', strtotime( $l->login_time ) ); ?></td>
                        <td><?php echo $l->ip; ?></td>
                    </tr>
<?php
    }
?>
                </tbody>
            </table>
        </div>
<?php
} else {
    print 'You are not logged in.';
}

require_once( './footer.inc' );

?>
