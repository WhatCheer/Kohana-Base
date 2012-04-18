<?php

	abstract class Kohana_SimpleTest_Test {

		public $type = 'test';

		public function __construct ( $message, $testList ) {
			$this->message = $message;
			$this->testList = $testList;
		}

	}

