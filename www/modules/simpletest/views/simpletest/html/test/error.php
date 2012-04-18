<div class="test error">
	<h3>Error</h3>
	<div class="test-case"><?php echo html::chars( Arr::get( $test->testList, 0 ) ); ?></div>
	<div class="test-method"><?php echo html::chars( Arr::get( $test->testList, 1 ) ); ?></div>
	<div class="clear"></div>
	<div class="message"><?php echo html::chars( $test->message ); ?></div>
</div>
