<?php echo $indent; ?>EXCEPTION // <?php echo Arr::get( $test->testList, 0 ); ?> : <?php echo Arr::get( $test->testList, 1 ); ?>

<?php echo $indent . '             Unexpected exception of type [' . get_class( $test->exception ) 
. '] with message [' . $test->exception->getMessage() 
. '] in [' . str_replace( DOCROOT, '', $test->exception->getFile() ) 
. ' line ' . $test->exception->getLine() . ']'; ?>

