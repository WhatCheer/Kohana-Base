<div class="test pass">
	<h3>Pass</h3>
	<div class="test-case"><?php echo html::chars( Arr::get( $test->testList, 0 ) ); ?></div>
	<div class="test-method"><?php echo html::chars( str_replace( '_', ' ', substr( Arr::get( $test->testList, 1 ), 4 ) ) ); ?></div>
	<div class="clear"></div>
	<div class="message"><?php echo html::chars( $test->message ); ?></div>
</div>
