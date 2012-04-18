<?php

	/**
	 * Used as a base class for models that soft-delete.
	 *
	 * Assumes the presence of EOM in the ORM inheritance chain.
	 */
	abstract class Model_SoftDelete extends Model_Dated {

		public function is_deleted () {
			return ( $this->deleted > 0 );
		}

		public function delete () {
			$this->deleted = time();
			$this->save();
		}

		public function scopes () {
			return array_merge(
				parent::scopes(),
				array(
					'not_deleted'
				)
			);
		}

		public function scope_deleted () {
			return $this->where_open()->where( 'deleted', '<>', 0 )->where_close(); 
		}

		public function scope_not_deleted () {
			return $this->where_open()->where( 'deleted', '=', 0 )->where_close(); 
		}
	
	}

