<?php

	class Kohana_SimpleTest_Test_Exception extends SimpleTest_Test {
		public $type = 'exception';

		public function __construct ( $exception, $testList ) {
			parent::__construct( $exception->getMessage(), $testList );
			$this->exception = $exception;
		}

	}

