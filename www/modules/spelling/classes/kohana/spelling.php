<?php

	// TODO: Selectable backends & languages with a config file.
	class Kohana_Spelling {

		protected static $_instance = null;

		protected $backend = null;

		public static function instance () {
			if( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		protected function __construct () {
			if( function_exists( 'pspell_new' ) ) {
				$this->backend = 'pspell';
				$this->pspell = pspell_new( 'en' );
			}
		}

		public function check_word ( $word ) {
			if( $this->backend == 'pspell' ) {
				return pspell_check( $this->pspell, $word );
			}
			else {
				return true; // TODO: This is bad undefined behavior.
			}
		}

		public function suggest_word ( $word ) {
			if( ! $this->check_word( $word ) ) {
				$suggestions = pspell_suggest( $this->pspell, $word );
				if( 0 != count( $suggestions ) ) {
					return $suggestions[0];
				}
			}
			return false;
		}

		public function suggest_string ( $string ) {

			$split = array_filter( array_map( 'trim', explode( ' ', $string ) ) );

			$corrected = false;
			foreach( $split as $i => $word ) {
				if( false !== ( $correct = $this->suggest_word( $word ) ) ) {
					$corrected = true;
					$split[$i] = $correct;
				}
			}
			
			if( $corrected ) { return implode( ' ', $split ); }
			else { return false; }

		}

	}

