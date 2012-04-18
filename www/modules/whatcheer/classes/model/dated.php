<?php
	/**
	 * Used as a base class for models that keep track of create and update timestamps.
	 */
	abstract class Model_Dated extends ORM {
		protected $_created_column = array( 'column' => 'created', 'format' => true );
		protected $_updated_column = array( 'column' => 'modified', 'format' => true );
	}

