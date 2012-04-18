<?php

	class Kohana_SimpleTest_Group {

		public function __construct ( $name, $size ) {
			$this->depth = 0;
			$this->tests = array();
			$this->groups = array();
			$this->name = $name;
			$this->size = $size;
			$this->start = microtime( true );
		}

		public function addTest ( $test ) {
			$this->tests[] = $test;
		}

		public function addGroup ( $group ) {
			$this->groups[] = $group;
		}

		public function close ( $depth = 0 ) {
			$this->depth = $depth;
			$this->end = microtime( true );
			$this->duration = $this->end - $this->start;
		}

	}

