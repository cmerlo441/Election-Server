<?php

$datepick = 1;
require_once( '../header.inc' );

if( isset( $_SESSION[ 'admin' ] ) ) {
    $faculty_query = 'select id, first, last '
        . 'from election_faculty '
        . 'order by last, first';
    $faculty_result = $db->query( $faculty_query );

    $election_id = $db->real_escape_string( $_REQUEST[ 'election' ] );
    $election_query = 'select * from election_elections '
        . "where id = $election_id";
    $election_result = $db->query( $election_query );
    if( $election_result->num_rows == 1 ) {
        $e = $election_result->fetch_object();
?>
        <div class="container">
            <h1 class="mb-4">Edit Election</h1>
            <!-- <form> -->
                <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Election Title</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="title" aria-describedby="title" placeholder="Title"
                        value="<?php echo $e->title; ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="delegates" class="col-sm-2 col-form-label">Delegates</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="delegates">
<?php
    for( $i = 1; $i <= 10; ++$i ) {
        print "                        <option";
        if( $e->delegates == $i )
            print ' selected';
        print ">$i</option>\n";
    }
?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="alternates" class="col-sm-2 col-form-label">Alternates</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="alternates">
                <?php
    for( $i = 1; $i <= 10; ++$i ) {
        print "                        <option";
        if( $e->alternates == $i )
            print ' selected';
        print ">$i</option>\n";
    }
                ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <label for="start" class="col-sm-2 col-form-label">Start Time</label>
                    <div class="col-sm-4 input-group date" id="start" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#start" value="<?php
    echo date( 'm/d/Y g:i a', strtotime( $e->starttime ) );
                        ?>" />
                        <div class="input-group-append" data-target="#start" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>

                    <label for="end" class="col-sm-2 col-form-label">End Time</label>
                    <div class="col-sm-4 input-group date" id="end" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#end" value="<?php
    echo date( 'm/d/Y g:i a', strtotime( $e->endtime ) );
                        ?>" />
                        <div class="input-group-append" data-target="#end" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>

                <div class="card text-center mt-4 mb-4">
                    <div class="card-header">Candidates</div>
                    <div class="card-body">
                        <div class="row">
<?php
    while( $fac = $faculty_result->fetch_object() ) {
        $candidate_query = 'select id '
            . 'from election_candidates '
            . "where election = \"$election_id\" "
            . "and faculty = \"$fac->id\"";
        $candidate_result = $db->query( $candidate_query );
        print "                    <div class=\"col-sm-6 col-lg-4 col-xl-3\">\n";
        print "                        <input class=\"form-check-input\" type=\"checkbox\" "
            . "id=\"$fac->id\" value=\"$fac->id\"";
        if( $candidate_result->num_rows == 1 )
            print ' checked';
        $candidate_result->close();
        print ">\n";
        print "                        <label class=\form-check-label\" for=\"$fac->id\">"
            . "$fac->first $fac->last</label>\n";
        print "                    </div>\n";
    }
?>
                        </div>
                    </div>
                </div>

                <div class="row">
                        <button type="submit" class="btn btn-primary col-sm-12 disabled" id="edit">Update Election</button>
                </div>

            <!-- </form> -->
        </div>

        <script type="text/javascript">
        $(function(){
            var candidates = [];
            $('input:checked').each(function(){
                candidates.push($(this).attr('id'));
            })
            var orig_c = candidates.join(',');

            function changed(){
                var t = ( $('input#title').val() == "<?php echo $e->title; ?>" );
                var d = ( $('select#delegates').val() == "<?php echo $e->delegates; ?>" );
                var a = ( $('select#alternates').val() == "<?php echo $e->alternates; ?>" );
                var s = ( $('div#start > input').val() ==
                    "<?php echo date( 'm/d/Y g:i A', strtotime( $e->starttime ) ); ?>");
                var e = ( $('div#end > input').val() ==
                    "<?php echo date( 'm/d/Y g:i A', strtotime( $e->endtime ) ); ?>");
                var candidates = [];
                $('input:checked').each(function(){
                    candidates.push($(this).attr('id'));
                })
                var c = candidates.join(',') == orig_c;
                return !(t && d && a && s && e && c );
            }

            $('#start').datetimepicker();
            $('#end').datetimepicker();

            $('input').on('change',function(){
                if( changed() )
                    $('button#edit').removeClass('disabled');
                else
                    $('button#edit').addClass('disabled');
            })
            $('select').on('change',function(){
                if( changed() )
                    $('button#edit').removeClass('disabled');
                else
                    $('button#edit').addClass('disabled');
            })

            $('button#edit').on('click',function(){
                if(changed()){
                    var id = "<?php echo $election_id; ?>";
                    var title = $('input#title').val();
                    var delegates = $('select#delegates').val();
                    var alternates = $('select#alternates').val();
                    var start = $('div#start > input').val();
                    var end = $('div#end > input').val();
                    var candidates = [];
                    $('input:checked').each(function(){
                        candidates.push($(this).attr('id'));
                    })
                    $.post('do_edit_election.php',
                        {
                            id: id,
                            title: title,
                            delegates: delegates,
                            alternates: alternates,
                            start: start,
                            end: end,
                            candidates: candidates.join(",")
                        },
                        function(data){
                            // do something clever
                        }
                    )
                }
            })
        })
        </script>
<?php
    }
}
require_once( '../footer.inc' );
?>
