<?php echo $indent; ?>     FAIL // <?php echo Arr:get( $test->testList, 0 ); ?> : <?php echo Arr::get( $test->testList, 1 ); ?>

<?php echo $indent . '             ' . str_replace( DOCROOT, '', $test->message ); ?>

