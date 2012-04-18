<?php defined('SYSPATH') or die('No direct script access.');

Route::set( 'simpletest', '/simpletest(/<action>(/<id>))' )
	->defaults( array(
		'controller' => 'simpletest',
		'action'     => 'index',
	) );

