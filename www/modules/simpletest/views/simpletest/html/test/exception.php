<div class="test exception">
	<h3>Exception</h3>
	<div class="test-case"><?php echo html::chars( Arr::get( $test->testList, 0 ) ); ?></div>
	<div class="test-method"><?php echo html::chars( Arr::get( $test->testList, 1 ) ); ?></div>
	<div class="clear"></div>
	<div class="message"><?php echo html::chars( $test->message ); ?></div>
	<div class="trace"><pre><?php echo html::chars( $test->exception->getTraceAsString() ); ?></pre></div>
</div>
