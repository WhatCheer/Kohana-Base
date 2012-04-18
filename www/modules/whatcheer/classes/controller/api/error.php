<?php

	class Controller_API_Error extends Controller_API_Base {

		public $auth = array();

		public function before () {
		
			$this->body = array();
			$this->_data['error'] = true;

			// Internal request only!
			if( Request::initial() !== Request::current() ) {
				$message = json_decode( urldecode( Request::current()->param( 'message' ) ) );
				if( is_null( $message ) ) { $message = array( array( 'error' => urldecode( Request::current()->param( 'message', 'Unspecified Error' ) ) ) ); }

				$this->body['errors'] = $message;
			}
			else {
				$this->request->action( 404 );
			}

			$this->response->status( (int) $this->request->action() );
			
			parent::before();
		}

		public function action_400 () {}
		public function action_401 () {}
		public function action_404 () {}
		public function action_500 () {}

	}

