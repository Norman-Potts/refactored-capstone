<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Documentation extends CI_Controller {

		var $TPL;

		public function __construct()
		{
			parent::__construct();
			
		}

		public function index()
		{
			$this->load->view('documentation_view');
			
		}

	}


