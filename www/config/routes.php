<?php defined('SYSPATH') or die('No direct script access.');

	Route::set('error', '(<directory>/)error/<action>(/<message>)', array('action' => '[0-9]++', 'message' => '.+'))
	->defaults(array(
			'controller' => 'error',
			'directory' => '',
	));

	Route::set( 'default', '(<controller>(/<action>(/<id>)))' )
		->defaults(array(
			'controller' => 'content',
			'action'     => 'index',
		));

