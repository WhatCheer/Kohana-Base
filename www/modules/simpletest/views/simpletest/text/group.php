<?php $indent = '';
for( $i = 0; $i <= $group->depth; $i++ ){ $indent .= ' '; }
echo $indent; ?>=== <?php echo str_replace( DOCROOT, '', $group->name ); ?> ===

<?php foreach( $group->tests as $test ): ?>
<?php echo View::factory( 'simpletest/text/test/' . $test->type )->bind( 'test', $test )->set( 'indent', $indent ); ?>

<?php endforeach; ?>

<?php foreach( $group->groups as $group ): ?>
<?php echo View::factory( 'simpletest/text/group' )->bind( 'group', $group ); ?>
<?php endforeach; ?>
