<?php

	/*!
		System wide error handling.
	*/
	class Controller_Error extends Controller_Application {

		public function before() {
			parent::before();

			if( ! isset( $this->content ) ) { throw new HTTP_Exception_500( 'Missing View For ' . $this->request->action() ); }

			if( $message = rawurldecode( $this->request->param( 'message' ) ) ) {
				$this->content->message = $message;
			}
			else { 
				$this->content->message = '';
			}

			$status = (int) $this->request->action();
			$this->response->status( $status );
		}

		public function action_404 () {
			$this->response->status( 200 );
			$this->title = 'Not Found';
		}

		public function action_401 () {
			$this->request->redirect( 'login' );
		}

		public function action_403 () {
			$this->title = 'Access Forbidden';
		}

		public function action_503 () {
			$this->title = 'Site Under Maintenance';
			$this->template = View::factory( 'error/503' );
		}

		public function action_500 () {
			$this->title = 'Internal Server Error';
		}

	}
