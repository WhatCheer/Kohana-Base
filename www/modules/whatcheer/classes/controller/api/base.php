<?php

	abstract class Controller_API_Base extends Controller {

			protected $_data = array( 'error' => false );
			protected $body = array();

			protected $validation = array();

			protected $store = null;

			public function before () {
			
				// Limit request methods plz.
				if( ! in_array( $_SERVER['REQUEST_METHOD'], array( 'GET', 'POST' ) ) ) { throw new HTTP_Exception_405( 'Method Not Allowed' ); } 	

				// In development, return plain text for easier debugging
				if( Kohana::$environment == Kohana::DEVELOPMENT ) {
					$this->response->headers( 'Content-Type', 'text/plain' );
				}
				else {
					$this->response->headers( 'Content-Type', 'application/json' );
				}

				// Check if there are validation rules, execute as needed.
				if( isset( $this->validation[Request::current()->action()] ) ) {
					$validation = Validation::factory( Param::REQUEST() );
					foreach( $this->validation[Request::current()->action()] as $field => $rules ) {
						foreach( $rules as $callback => $params ) {
							if( ! is_array( $params ) or 0 == count( $params ) ) {
								$params = null;
							}
							$validation->rule( $field, $callback, $params );
						}
					}
					
					// Check and throw a 400 on error.
					if( ! $validation->check() ) {
						$messages_path = 'api/' . 
						                 strtolower( Request::current()->controller() ) .
							               '/' . 
						                 strtolower( Request::current()->action() ); 

						// Errors are supposed to be an array of key/value's, so we mangle a bit
						$errors = array();
						foreach( $validation->errors( $messages_path ) as $key => $message ) {
							$errors[] = array( $key => $message );
						}

						throw new HTTP_Exception_400( json_encode( $errors ) ); 
					}
				}

				return parent::before();
			}

			public function after () {
				$this->_data['body'] = $this->body;
				$this->response->body( json_encode( $this->_data ) );
				return parent::after();
			}

			protected function error ( $message ) {
				throw new HTTP_Exception_400( $message );
			}

			protected function internal_error ( $message ) {
				throw new HTTP_Exception_500( $message );
			}

	}
