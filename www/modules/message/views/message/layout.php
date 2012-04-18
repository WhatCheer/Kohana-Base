<?php
	foreach( $messages as $type => $set ):
		foreach( $set as $headline => $msgs ):
?>
<div class="message <?php echo html::chars( $type ); ?>">
		<?php if( $headline !== Message::ANONYMOUS_MARKER ): ?>
		<h2><?php echo html::chars( $headline ); ?></h2>
		<?php else: ?>
		<h2><?php echo html::chars( ucwords( $type ) ); ?></h2>
		<?php endif; ?>
		<?php if( 1 == count( $msgs ) ): ?>
		<p><?php echo html::chars( array_pop( $msgs ) ); ?></p>
		<?php else: ?>
		<ul>
			<?php foreach( $msgs as $msg ): ?>
			<li><a href="#"><?php echo html::chars( $msg ); ?></a></li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
</div>
<?php
		endforeach;
	endforeach;
?>
