<?php

	class Kohana_Controller_SimpleTest extends Controller_Template {
	
		public function before () {
			if( PHP_SAPI == 'cli' ) {
				$this->template = 'simpletest/template_text';
			}
			else {
				$this->template = 'simpletest/template_html';
			}
			parent::before();
		}

		public function action_index () {
			$suites = Kohana::config( 'simpletest.tests' );
			$this->template->title = "Choose Test";
			$this->template->content = View::factory( 'simpletest/index' )->set( 'suites', $suites );
		}

		public function action_all () {
			$this->template->title = "All Tests";

			require_once Kohana::find_file( 'vendor', 'simpletest/unit_tester' );

			$suites = Kohana::config( 'simpletest.tests' );

			if( PHP_SAPI == 'cli' ) {
				$reporter = new SimpleTest_Reporter( 'simpletest/text' );
			}
			else {
				$reporter = new SimpleTest_Reporter( 'simpletest/html' );
			}

			$test = new TestSuite( 'All Tests' );
			foreach( $suites as $name => $tests ) {
				$this->addSuite( $tests, $test );
			}
			$test->run( $reporter );

			$this->template->content = $reporter->render();
		}

		public function action_run ( $name ) {
			$this->template->title = $name;

			require_once Kohana::find_file( 'vendor', 'simpletest/unit_tester' );

			$suites = Kohana::config( 'simpletest.tests' );

			if( PHP_SAPI == 'cli' ) {
				$reporter = new SimpleTest_Reporter( 'simpletest/text' );
			}
			else {
				$reporter = new SimpleTest_Reporter( 'simpletest/html' );
			}

			$test = new TestSuite( $name );
			$this->addSuite( $suites[$name], $test );
			$test->run( $reporter );

			$this->template->content = $reporter->render();
		}

		protected function addSuite ( $tests, &$test ) {
			foreach( $tests as $file ) {
				$path = Kohana::find_file( 'tests', $file );
				if( empty( $path ) ) {
					throw new Kohana_Exception( 'Test file not found: ' . $file );
				}
				$test->addFile( $path );
			}
		}

	}

