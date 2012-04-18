<?php

	class Route extends Kohana_Route {

		/**
		* Make routes case insensitive.
		*/
		public static function compile($uri, array $regex = NULL) {
			if ( ! is_string($uri))
				return;
			return parent::compile( $uri, $regex ) . 'i';
		}

	}

