<?php

$datepick = 1;
require_once( '../header.inc' );
if( isset( $_SESSION[ 'admin' ] ) ) {
    $faculty_query = 'select id, first, last '
        . 'from election_faculty '
        . 'order by last, first';
    $faculty_result = $db->query( $faculty_query );
?>

        <div class="container">
            <h1 class="mb-4">Create an Election</h1>
            <!-- <form> -->
                <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Election Title</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="title" aria-describedby="title" placeholder="Title">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="delegates" class="col-sm-2 col-form-label">Delegates</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="delegates">
<?php
    for( $i = 1; $i <= 10; ++$i )
        print "                        <option>$i</option>\n";
?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="alternates" class="col-sm-2 col-form-label">Alternates</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="alternates">
                <?php
                for( $i = 0; $i <= 10; ++$i )
                print "                        <option>$i</option>\n";
                ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <label for="start" class="col-sm-2 col-form-label">Start Time</label>
                    <div class="col-sm-4 input-group date" id="start" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#start" />
                        <div class="input-group-append" data-target="#start" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>

                    <label for="end" class="col-sm-2 col-form-label">End Time</label>
                    <div class="col-sm-4 input-group date" id="end" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#end" />
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
        print "                    <div class=\"col-sm-6 col-lg-4 col-xl-3\">\n";
        print "                        <input class=\"form-check-input\" type=\"checkbox\" "
            . "id=\"$fac->id\" value=\"$fac->id\">\n";
        print "                        <label class=\form-check-label\" for=\"$fac->id\">"
            . "$fac->first $fac->last</label>\n";
        print "                    </div>\n";
    }
?>
                        </div>
                    </div>
                </div>

                <div class="row">
                        <button type="submit" class="btn btn-primary col-sm-12" id="create">Create Election</button>
                </div>

            <!-- </form> -->
        </div>

        <script type="text/javascript">
        $(function(){
            $('#start').datetimepicker();
            $('#end').datetimepicker();
            $('button#create').on('click',function(){
                var title = $('input#title').val();
                var delegates = $('select#delegates').val();
                var alternates = $('select#alternates').val();
                var start = $('div#start > input').val();
                var end = $('div#end > input').val();
                var candidates = [];
                $('input:checked').each(function(){
                    candidates.push($(this).attr('id'));
                })
                $.post('do_create_election.php',
                    {
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
            })
        })
        </script>

<?php
}

require_once( '../footer.inc' );
?>
