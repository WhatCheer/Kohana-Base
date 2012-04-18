<?php

	abstract class Controller_Application extends Controller_Template {

		public $template = 'template';
		public $content  = null;
		public $script   = null;
		
		public $title   = null;

		protected $session = null;

		public function before () {
			parent::before();

			// Bind our views and variables
			$this->template->bind( 'content', $this->content );
			$this->template->bind( 'script', $this->script );
			$this->template->bind( 'title', $this->title );

			// Attempt to auto-load a view for this page
			try {
				$this->content = view::factory( implode( 
					'/',
					array_filter( array(
						$this->request->directory(),
						$this->request->controller(),
						$this->request->action()
					) )
				) );
			}
			catch( View_Exception $e ) { $this->content = null; }
		}

		public function after () {
		
			if( is_null( $this->script ) ) { 
				// Attempt to auto-load a script view for this page
				try {
					$this->script = view::factory( implode( 
						'/',
						array_filter( array(
							'script',
							$this->request->directory(),
							$this->request->controller(),
							$this->request->action()
						) )
					) );
				}
				catch( View_Exception $e ) { $this->script = null; }
			}

			parent::after();
		}

	}

