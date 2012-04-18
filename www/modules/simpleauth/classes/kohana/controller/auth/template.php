<?php defined('SYSPATH') or die('No direct script access.');
	/**
	 * Abstract controller class for automatic templating with auth support.
	 *
	 * @package    K3-SimpleAuth
	 * @category   Controller
	 * @author     Kohana Team
	 * @copyright  (c) 2008-2011 Kohana Team
	 * @license    http://kohanaframework.org/license
	 */
	abstract class Kohana_Controller_Auth_Template extends Controller_Auth {

		/**
		 * @var  View  page template
		 */
		public $template = 'template';

		/**
		 * @var  boolean  auto render template
		 **/
		public $auto_render = TRUE;

		/**
		 * Loads the template [View] object.
		 */
		public function before()
		{
			if ($this->auto_render === TRUE)
			{
				// Load the template
				$this->template = View::factory($this->template);
			}

			return parent::before();
		}

		/**
		 * Assigns the template [View] as the request response.
		 */
		public function after()
		{
			if ($this->auto_render === TRUE)
			{
				$this->response->body($this->template->render());
			}

			return parent::after();
		}

	} // End Controller_Template
