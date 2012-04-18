<?php

	class Kohana_Exception extends Kohana_Kohana_Exception {

		protected static $custom_handler = array();

		public static function handler ( Exception $e ) {
			$directory = '';
			if( ! is_null( Request::initial() ) ) {
				$directory = Request::initial()->directory();
			}
			else if ( ! is_null( Request::current() ) ) {
				$directory = Request::current()->directory();
			}

			// Determine if we should use the built in handler
			if(
				defined( 'BUILTIN_EXCEPTION_HANDLER' ) and
				BUILTIN_EXCEPTION_HANDLER
			) { return parent::handler( $e ); }

			try {
				$attributes = array (
					'action'  => 500,
					'message' => rawurlencode( $e->getMessage() ),
					'directory' => $directory,
				);

				if ( $e instanceof HTTP_Exception ) {
					$attributes['action'] = $e->getCode();
				}

				// Do we need to trigger a custom handler?
				if( array_key_exists( $directory, self::$custom_handler ) ) {
					$attributes = array_merge( $attributes, self::$custom_handler[$directory] );
				}

				if( $attributes['action'] >= 500 ) {
					// Non-HTTP errors, or 500+ errors are a _big deal_
					Kohana::$log->add( 
						Log::ERROR, 
						parent::text( $e ) . "\n" . $e->getTraceAsString()
					);
				}
				else if( $attributes['action'] == 404 ) {
					// 404's are notable, but not a big deal
					$uri = ( is_null( Request::$current ) ) ? Request::detect_uri() : Request::$current->uri();
					Kohana::$log->add( 
						Log::NOTICE,
						"404: $uri"
					);
				}

				// Error sub-request.
				echo Request::factory( Route::get( 'error' )->uri( $attributes ) ) // DO NOT SWITCH TO ROUTE::URL
					->execute()
					->send_headers()
					->body();

				exit( 1 );
			}
			catch ( Exception $e ) {
				Kohana::$log->add( Log::CRITICAL, $e->getMessage() );

				// Clean the output buffer if one exists
				ob_get_level() and ob_clean();

				if( Kohana::DEVELOPMENT == Kohana::$environment ) { return parent::handler( $e ); }
				else {
					// Display the exception text
					echo parent::text( $e );
					// Exit with an error status
					exit( 1 );
				}
			}
		}

	}

