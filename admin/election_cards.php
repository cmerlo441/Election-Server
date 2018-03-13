<?php

$no_header = 1;
require_once( '../header.inc' );

if( isset( $_SESSION[ 'admin' ] ) ) {

    $elections_query = 'select * from election_elections';
    $elections_result = $db->query( $elections_query );
    if( $elections_result->num_rows == 0 ) {
?>
    <div class="card mb-4 box-shadow">
        <div class="card-header">
            <h4 class="my-0 font-weight-normal">No Current Elections</h4>
        </div>
        <div class="card-body">
            <h1 class="card-title pricing-card-title">No Current Elections</h1>
            <p>There are no elections at this time.</p>
            <!--<button type="button" class="btn btn-lg btn-block btn-primary">Contact us</button>-->
        </div>
    </div>
<?php
    } else {
        while( $election = $elections_result->fetch_object() ) {
            $candidates_count_query = 'select count(id) as count '
                . 'from election_candidates '
                . "where election = $election->id";
            $candidates_count_result = $db->query( $candidates_count_query );
            $candidates_count = $candidates_count_result->fetch_object();
            $candidates_count_result->close();

            $votes_query = 'select count(id) as count from election_voted '
                . "where election = \"$election->id\"";
            $votes_result = $db->query( $votes_query );
            $votes = $votes_result->fetch_object();
            $votes_result->close();
?>
        <div class="card mb-4 box-shadow" id="<?php echo $election->id; ?>">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">
                    Ends <?php echo date('l', strtotime( $election->endtime ) ); ?>
                    <small class="text-muted">
                        <?php echo date( 'g:i a', strtotime( $election->endtime ) ); ?>
                    </small>
                </h4>
            </div>
            <div class="card-body">
                <h1 class="card-title pricing-card-title"><?php echo $election->title; ?>
                </h1>
                <ul class="list-unstyled mt-3 mb-4">
                    <li><?php echo $candidates_count->count; ?> Candidates</li>
                    <li>Choose <?php echo $election->delegates; ?> delegates</li>
<?php
                        if( $election->alternates > 0 ) {
                            print "          <li>Choose $election->alternates alternates</li>\n";
                        }
?>
                    <li><?php echo $votes->count; ?> people have voted</li>
                </ul>
                <a class="btn btn-lg btn-block btn-outline-danger edit"
                id="<?php echo $election->id; ?>" href="edit.php?election=<?php echo $election->id; ?>">
                Edit Settings
                </a>
                <a class="btn btn-lg btn-block btn-danger tabulate"
                id="<?php echo $election->id; ?>" href="tabulate.php?election=<?php echo $election->id; ?>">
                Tabulate Results
                </a>
            </div>
        </div>
<?php
        }
    }
}
?>
