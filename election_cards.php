<?php

$no_header = 1;
require_once( './header.inc' );

$elections_query = 'select * from election_elections '
    . "where starttime < \"" . date( 'Y-m-d H:i:s' )
    . "\" and endtime > \"" . date( 'Y-m-d H:i:s' ) . "\" "
    . 'order by endtime, title';
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
        $voted_query = 'select timestamp '
            . 'from election_voted '
            . "where faculty = {$_SESSION[ 'user' ]} "
            . "and election = $election->id";
        $voted_result = $db->query( $voted_query );
        $voted = $voted_result->num_rows == 1;

        $candidates_count_query = 'select count(id) as count '
            . 'from election_candidates '
            . "where election = $election->id";
        $candidates_count_result = $db->query( $candidates_count_query );
        $candidates_count = $candidates_count_result->fetch_object();
?>
        <div class="card mb-4 box-shadow border-<?php echo $voted ? 'warning bg-light' : 'primary'; ?>" id="<?php echo $election->id; ?>">
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
                    } else {
                        print "          <li>(No alternates)</li>\n";
		    }
?>
                </ul>
<?php
        if( $voted_result->num_rows == 1 ) {
?>
                <button type="button" class="btn btn-lg btn-block btn-warning"
                id="<?php echo $election->id; ?>">
                You voted already.
                </button>
<?php
        } else {
?>
                <button type="button" class="btn btn-lg btn-block btn-primary vote"
                id="<?php echo $election->id; ?>">
                Vote Now!
                </button>
<?php
        }
?>
            </div>
        </div>
<?php
    }
}
?>

<script type="text/javascript">
$(function(data){
    $('button.vote').on('click',function(){
        var id = $(this).attr('id');
        var form = document.createElement('form');
        document.body.appendChild(form);
        form.method = 'post';
        form.action = 'vote.php';
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'election_id';
        input.value = id;
        form.appendChild(input);
        form.submit();
    })
})
</script>
