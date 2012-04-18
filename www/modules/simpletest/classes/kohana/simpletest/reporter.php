<?php

	require_once Kohana::find_file( 'vendor', 'simpletest/scorer' );

	class Kohana_SimpleTest_Reporter extends SimpleReporter {

		public function __construct ( $template ) {
			parent::__construct();

			$this->groups = array();
			$this->depth  = array();
			$this->group_index = null;
			
			$this->template = View::factory( $template ); 
			$this->template->bind( 'groups', $this->groups );
		}


    public function paintGroupStart ( $test_name, $size ) {
			array_push( $this->depth, $test_name );
			$this->group_index = $test_name;
			$this->groups[$this->group_index] = new SimpleTest_Group( $test_name, $size ); 
		}

		public function paintGroupEnd ( $test_name ) {
			$name = array_pop( $this->depth );
			$depth = count( $this->depth );
			$this->groups[$name]->close( $depth );
			if( $depth == 0 ) {
				$this->group_index = null;
			}
			else {
				$this->group_index = $this->depth[count($this->depth)-1];
				$this->groups[$this->group_index]->addGroup( $this->groups[$name] );
				unset( $this->groups[$name] );
			}
		}

		public function paintPass ( $message ) {
			parent::paintPass( $message );
			$this->groups[$this->group_index]->addTest( new SimpleTest_Test_Pass( $message, $this->getTestList() ) );
		}

		public function paintSkip ( $message ) {
			parent::paintSkip( $message );
			$this->groups[$this->group_index]->addTest( new SimpleTest_Test_Skip( $message, $this->getTestList() ) );
		}

		public function paintFail ( $message ) {
			parent::paintFail( $message );
			$this->groups[$this->group_index]->addTest( new SimpleTest_Test_Fail( $message, $this->getTestList() ) );		
		}

		public function paintError ( $message ) {
			parent::paintError( $message );
			$this->groups[$this->group_index]->addTest( new SimpleTest_Test_Error( $message, $this->getTestList() ) );
		}

		public function paintException ( $exception ) {
			parent::paintException( $exception );
			$this->groups[$this->group_index]->addTest( new SimpleTest_Test_Exception( $exception, $this->getTestList() ) );
		}

		public function render () {
			$this->template->set( 'test_case_progress', $this->getTestCaseProgress() );
			$this->template->set( 'test_case_count', $this->getTestCaseCount() );
			$this->template->set( 'pass_count', $this->getPassCount() );
   		$this->template->set( 'fail_count', $this->getFailCount() );
			$this->template->set( 'exception_count', $this->getExceptionCount() );
			return $this->template->render();
		}

	}

