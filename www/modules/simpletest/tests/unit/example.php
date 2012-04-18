<?php

	class TestExample extends UnitTestCase {

		function __construct () { parent::__construct( 'Example Test' ); }

		function test_Addition () {
			$this->assertFalse( ( 1 + 1 ) === 3 );
			$this->assertTrue( ( 1 + 1 ) === 2 );
		}

		function test_Bad_Addition () {
			$this->assertTrue( ( 1 + 1 ) === 5 );
		}

	}

