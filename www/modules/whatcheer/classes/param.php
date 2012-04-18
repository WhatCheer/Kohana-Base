<?php

	class Param {

		public static function GET ( $name = null, $default = null ) {
			$v = Request::current()->query( $name );
			return ( is_null( $v ) ) ? $default : $v;
		}

		public static function POST ( $name = null, $default = null ) {
			$v = Request::current()->post( $name );
			return ( is_null( $v ) ) ? $default : $v;
		}

		public static function REQUEST ( $name = null, $default = null ) {
			if( is_null( $name ) ) {
				$v = array_merge( Param::GET(), Param::POST() );
			}
			else {
				$v = Param::POST( $name );
				if( is_null( $v ) ) {
					$v = Param::GET( $name, $default );
				}
			}
			return $v;
		}

	}

