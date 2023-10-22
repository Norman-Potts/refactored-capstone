<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {


	 var $TPL;

	public function __construct() {
		parent::__construct();			
		// If they are already logged in redirect them to the homepage.	
		$this->Vert = $this->userauth->validSessionExists();		
		if ( $this->Vert == true) {
			$page = base_url() . "index.php?/Home";
			$this->userauth->redirect($page);
		}
		$this->load->helper('form');
	}
	
	
	
	public function index() {
		$this->TPL['msg'] =  '';
	    $this->load->view('loginViews/login_view', $this->TPL);			
		// $this->load->view('loginViews/login_view', $this->TPL);
		// $this->load->view('loginViews/login_view');		
	}
	
	public function loginuser()
	{
		$this->TPL['msg'] =  $this->userauth->login( $this->input->post("Username"), $this->input->post("password"));								
	    $this->load->view('loginViews/login_view', $this->TPL);				
	}
	
	public function logout()
	{
		$this->userauth->logout();
	}
}
