<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

  var $TPL;

  public function __construct()
  {
    parent::__construct();
    
	
	$this->Vert = $this->userauth->validSessionExists();		
		if ( $this->Vert == true)
		{
			$page = base_url() . "index.php?/Home";
			$this->userauth->redirect($page);
		}
	
  }

  public function index()
  {
	 $this->TPL['msg'] =  '';
    $this->load->view('loginViews/logout_view', $this->TPL);
  }

}
?>