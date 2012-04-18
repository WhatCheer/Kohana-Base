<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Message is a class that lets you easily send messages
 * in your application (aka Flash Messages)
 *
 * @package	Message
 * @author	John Hobbs, Dave Widmer
 * @see	http://github.com/jmhobbs/message
 * @copyright	2010 © Dave Widmer, 2011 © John Hobbs
 */

	class Kohana_Message {

		/**
		 * Constants to use for the types of messages that can be set.
		 */
		const ERROR = 'error';
		const NOTICE = 'notice';
		const SUCCESS = 'success';
		const WARN = 'warn';

		/**
		 * This is a marker for items with no headline.
		 */
		const ANONYMOUS_MARKER = '------[NO HEADLINE]------';

		/**
		 * This is what we use for message storage.
		 *
		 * Our end structure is like this:
		 *
		 *	$this->messages = array(
		 *		'error' => array(
		 *			'Headline' => array(
		 *				'Message',
		 *				'Another Message',
		 *			),
		 *			'Another Headline' => array(
		 *				'Separate Message',
		 *			),
		 *		),
		 *	);
		 *
		 */
		public $messages;

		protected function __construct () {
			$this->messages = array();
		}

		public function attach ( $type, $headline, $message ) {

			if( ! isset( $this->messages[$type] ) ) {
				$this->messages[$type] = array();
			}

			// Yuck...
			if( false === $headline ) {
				$headline = self::ANONYMOUS_MARKER;
			}

			if( ! isset( $this->messages[$type][$headline] ) ) {
				$this->messages[$type][$headline] = array();
			}

			if( is_array( $message ) ) {
				$this->messages[$type][$headline] = array_merge(
					$this->messages[$type][$headline],
					$message
				);
			}
			else {
				$this->messages[$type][$headline][] = $message;
			}

		}

		/**
		 * Clears the message from the session
		 *
		 * @return	void
		 */
		public static function clear() {
			Session::instance()->delete( 'flash_message' );
		}


		/**
		 * Displays the message
		 *
		 * @return	string	Message to string
		 */
		public static function display() {

			$msg = self::get();

			if( $msg ) {
				self::clear();
				return View::factory( 'message/layout' )->set( 'messages', $msg->messages )->render();
			}
			else	{
				return '';
			}
		}

		/**
		 * The same as display - used to mold to Kohana standards
		 *
		 * @return	string	HTML for message
		 */
		public static function render() {
			return self::display();
		}

		/**
		 * Gets the current message.
		 *
		 * @return	mixed	The message or FALSE
		 */
		public static function get() {
			return Session::instance()->get( 'flash_message', FALSE );
		}


		/**
		 * Sets a message.
		 *
		 * @param	string	Type of message
		 * @param	mixed	Array/String for the message
		 * @param string Optional headline.
		 *
		 * @return	void
		 */
		public static function set( $type, $message, $headline = false ) {
			$msg = self::get();
			if( FALSE === $msg ) {
				$msg = new Message();
			}
			$msg->attach( $type, $headline, $message );
			Session::instance()->set( 'flash_message', $msg );
		}

		/**
		 * Sets an error message.
		 *
		 * @param	mixed	String/Array for the message(s)
		 * @param	string	Optional headline.
		 *
		 * @return	void
		 */
		public static function error ( $message, $headline = false) {
			self::set( Message::ERROR, $message, $headline );
		}

		/**
		 * Sets an notice message.
		 *
		 * @param	mixed	String/Array for the message(s)
		 * @param	string	Optional headline.
		 *
		 * @return	void
		 */
		public static function notice( $message, $headline = false ) {
			self::set( Message::NOTICE, $message, $headline );
		}

		/**
		 * Sets an success message.
		 *
		 * @param	mixed	String/Array for the message(s)
		 * @param	string	Optional headline.
		 *
		 * @return	void
		 */
		public static function success ( $message, $headline = false ) {
			self::set( Message::SUCCESS, $message, $headline );
		}

		/**
		 * Sets a warning message.
		 *
		 * @param	mixed	String/Array for the message(s)
		 * @param	string	Optional headline.
		 *
		 * @return	void
		 */
		public static function warn ( $message, $headline = false ) {
			self::set( Message::WARN, $message, $headline );
		}

	}

