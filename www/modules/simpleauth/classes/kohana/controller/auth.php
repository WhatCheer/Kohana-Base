<?php

	abstract class Kohana_Controller_Auth extends Controller {

		/*
			The $auth member controls high level access to pages.

			You can then block things off a bit at a time.
			'*' is a wildcard.

			Examples:

			$auth = array(
				// Only roles 'admin' can reach this
				'one' => 'admin',
				// Only roles 'manager' and 'admin' can reach this
				'two' => array( 'manager', 'admin'  ),
				// Anyone logged in can reach this
				'three' => '*',
				// Anyone can access this action
				'four' => false,
				// Any other controller can only be reached by those with the 'view' role
				'*' => 'view',
				// This rule is never reached, because of the wildcard rule directly above 
				'five' => 'admin',
			);

		*/
		public $auth = array( '*' => '*' );

		public function before () {
			parent::before();

			$this->session = Session::instance();

			# Check user authentication
			$auth_result = true;
			$action_name = Request::current()->action();
			if( array_key_exists( $action_name, $this->auth ) )
				$auth_result = $this->_check_auth( $action_name );
			else if ( array_key_exists( '*', $this->auth ) )
				$auth_result = $this->_check_auth( '*' );

			if( ! $auth_result ) {
				if( Auth::instance()->logged_in() ) {
					$this->_not_authorized();
				}
				else {
					$this->_not_logged_in();
				}
			}

		}

		/**
		* Called when a user is not authorized for a specific action.
		**/
		protected function _not_authorized () {
			throw new HTTP_Exception_403();
		}

		/**
		* Called when a user is not logged in.
		**/
		protected function _not_logged_in () {
			$this->session->set( 'return-to', Request::$current->url() );
			throw new HTTP_Exception_401();
		}

		/**
		 * DRY out some of our auth code with an extra method.
		 */
		protected function _check_auth ( $action ) {
			$auth_result = true;
			if( is_array( $this->auth[$action] ) ) {
				foreach( $this->auth[$action] as $role ) {
					$auth_result = false;
					if( Auth::instance()->logged_in( $role ) ) {
						$auth_result = true;
						break;
					}
				}
			}
			else if ( '*' == $this->auth[$action] ) {
				$auth_result = Auth::instance()->logged_in();
			}
			else if ( false !== $this->auth[$action] ) {
				$auth_result = Auth::instance()->logged_in( $this->auth[$action] );
			}
			return $auth_result;
		} 
	}

