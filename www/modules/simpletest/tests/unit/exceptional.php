<?php

	class TestExceptional extends UnitTestCase {

		function __construct () { parent::__construct( 'Throw Some Exceptions' ); }

		function test_Errors () {
			trigger_error( 'Oh Noes!', E_USER_ERROR );
			$this->assertTrue( ( 1 + 1 ) === 2 );
		}

		function test_Thrown_Exception () {
			throw new Exception ( 'ZOMG!' );
			$this->assertTrue( ( 1 + 1 ) === 2 );
		}

	}

