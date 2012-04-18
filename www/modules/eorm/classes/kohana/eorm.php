<?php defined('SYSPATH') or die('No direct script access.');

	/**
	 * This class allows for the addition of get_[name] and set_[name]
	 * elements to be used automatically for non-extant attributes.
	 * 
	 * It also provides scope management, a built in error store,
	 * and improved as_array functionality.
	 */
	class Kohana_EORM extends Kohana_ORM {

		//! Store your errors in here! (i.e. ORM_Validation_Exception->errors() )
		public $errors = array();

		/*!
			Check if a field has an error on it.

			\param name The name of the column you want to check.
			\param set The name of the validation group you want to check (optional)

			\returns Boolean True if there is an error for this field.
		*/
		public function has_error( $name, $set = null ) {
			return ! is_null( $this->get_error( $name, $set ) );
		}

		/*!
			Get an error message for this object.

			\param name The name of the column you want to get an error for.
			\param set The name of the validation group you want to get an error for (optional)

			\returns String or Null Returns the error messge if one exists, or null otherwise.
		*/
		public function get_error( $name, $set = null ) {
			if( ! is_null( $set ) ) {
				$set = arr::get( $this->errors, $set, array() );
				return arr::get( $set, $name, null );
			}
			else {
				return arr::get( $this->errors, $name, null );
			}
		}

		/*!
			Have any of the columns on this model been changed?

			\returns Boolean True if any column has changed.
		*/
		public function has_changed () { return 0 < count( $this->changed() ); }

		/** Anything in an array here will be included in as_array responses. */
		protected $_as_array_include = null;

		/** Anthing in an array here will be excluded from as_array responses. */
		protected $_as_array_exclude = null;

		/** Anything in this array can not be mass assigned with ORM::values */
		protected $_protect_from_mass_assignment = array();

		/**
		 * An alias of ORM::find_all
		 */
		public function all() { return $this->find_all(); }

		/**
		 * An alias of ORM::find
		 */
		public function first() { return $this->find(); }

		public function __get ( $name ) {
			$method = "get_$name";

			if( method_exists( $this, $method ) ) { return $this->$method(); }

			return parent::__get( $name );
		}

		public function __set ( $name, $value ) {
			$method = "set_$name";

			if( method_exists( $this, $method ) ) { return $this->$method( $value ); }

			return parent::__set( $name, $value );
		}

		public function __isset ( $name ) {
			$method = "isset_$name";

			if( method_exists( $this, $method ) ) { return $this->$method(); }

			return parent::__isset( $name );
		}

		public function __unset ( $name ) {
			$method = "unset_$name";

			if( method_exists( $this, $method ) ) { return $this->$method(); }

			return parent::__unset( $name );
		}

		/*!
			This is the old as_array function, but using some custom options.

			\param $only If set to an array, only those keys will be returned. Overrides all other options.
			\param $include Set to an array to include specific data members (essentially only used for get_method members). Overrides _as_array_include.
			\param $exclude Set to an array of key values to strip from the result. Overrides _as_array_exclude.
		*/
		public function as_array ( $only = null, $include = null, $exclude = null ) { 
			$array = parent::as_array();

			if( is_array( $only ) ) {
				foreach( $array as $key => $value ) {
					if( in_array( $key, $only ) ) {
						unset( $only[$key] );
					}
					else {
						unset( $array[$key] );
					}
				}

				foreach( $only as $unfound_key ) {
					$array[$unfound_key] = $this->$unfound_key;
				}
			}
			else {
				if( is_null( $include ) and is_array( $this->_as_array_include ) ) {
					foreach( $this->_as_array_include as $key ) {
						$array[$key] = $this->$key;
					}
				}
				if( is_null( $exclude ) and is_array( $this->_as_array_exclude ) ) {
					foreach( $this->_as_array_exclude as $key ) {
						unset( $array[$key] );
					}
				}
				if( is_array( $include ) ) {
					foreach( $include as $key ) {
						$array[$key] = $this->$key;
					}
				}
				if( is_array( $exclude ) ) {
					foreach( $exclude as $key ) {
						unset( $array[$key] );
					}
				}
			}

			return $array;
		}

		/**
		 * Set values from an array with support for one-one relationships.  This method should be used
		 * for loading in post data, etc.
		 *
		 * @param  array $values   Array of column => val
		 * @param  array $expected Array of keys to take from $values
		 * @return ORM
		 */
		public function values ( array $values, array $expected = NULL ) {
			// Unless expected is specified, we want to protect the keys from mass assignment
			if( is_null( $expected ) ) {
				$_values = array();
				foreach( $values as $key => $value ) {
					if( ! in_array( $key, $this->_protect_from_mass_assignment ) ) {
						$_values[$key] = $value;
					}
				}
				$values = $_values;
				unset( $_values );
			}
			return parent::values( $values, $expected );
		}

		/////////////////////////////////////////
		// SCOPES!

		protected $_scopes_pending = array();

		public function scopes () {
			return array();
		}

		public function scope ( $name ) {
			$normalized = preg_replace( '/[^a-z_]/', '_', strtolower( $name ) );
			if( ! method_exists( $this, 'scope_' . $normalized ) ) { throw new Kohana_Exception( 'Scope "' . $normalized . '" does not exist in "' . get_class() . '"' ); }
			if( ! in_array( $normalized, $this->_scopes_pending ) ) { $this->_scopes_pending[] = $normalized; }
			return $this;
		}

		public function unscope ( $name = null ) {
			if( is_null( $name ) ) { $this->_scopes_pending = array(); }
			else {
				$normalized = preg_replace( '/[^a-z_]/', '_', strtolower( $name ) ); 
				$this->_scopes_pending[] = array_filter( $this->_scopes_pending, create_function( '$v', 'return $v == \'' . $normalized . '\';' ) );
			}
			return $this;
		}

		protected function _initialize() {
			$this->_scopes_pending = $this->scopes();
			return parent::_initialize();
		}

		protected function _build ( $type ) {
			foreach( $this->_scopes_pending as $scope ) {
				call_user_func( array( $this, 'scope_' . $scope ) );
			}
			$this->_scopes_pending = $this->scopes(); // Reset scopes
			return parent::_build( $type );
		}

	}
