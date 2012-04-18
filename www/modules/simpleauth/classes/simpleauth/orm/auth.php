<?php defined('SYSPATH') or die('No direct script access.');

	abstract class SimpleAuth_ORM_Auth extends ORM {

		protected $auth = array( '*' => '*' );

		public function can ( $action, $user ) {
			$method = 'can_' . $action;

			if( method_exists( $this, $method ) ) { return $this->$method( $user ); }

			if( array_key_exists( $action, $this->auth ) ) {
				return $this->_check_auth( $action, $user );
			}
			else if ( array_key_exists( '*', $this->auth ) ) {
				return $this->_check_auth( '*', $user );
			}
			else {
				return true;
			}

		}

		/**
		 * DRY out some of our auth code with an extra method.
		 */
		protected function _check_auth ( $action, $user ) {
			$auth_result = true;
			if( is_array( $this->auth[$action] ) ) {
				foreach( $this->auth[$action] as $role ) {
					$auth_result = false;
					if( $user->has( 'role', ORM::factory( 'role' )->where( 'name', '=', $role )->find() ) ) {
						$auth_result = true;
						break;
					}
				}
			}
			else if ( '*' == $this->auth[$action] ) {
				$auth_result = true;
			}
			else if ( false !== $this->auth[$action] ) {
				$auth_result = $user->has( 'role', ORM::factory( 'role' )->where( 'name', '=', $this->auth[$action] )->find() );
			}
			return $auth_result;
		}

	}
