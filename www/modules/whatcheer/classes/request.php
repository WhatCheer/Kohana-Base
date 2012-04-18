<?php

	class Request extends Kohana_Request {

		public function is_post () {
			return Request::POST == $this->method();
		}

		public function is_get () {
			return Request::GET == $this->method();
		}

		public function is_delete () {
			return Request::DELETE == $this->method();
		}

		public function is_put () {
			return Request::PUT == $this->method();
		}

		public function original_uri () {
			return $this->_uri;
		}

	}

