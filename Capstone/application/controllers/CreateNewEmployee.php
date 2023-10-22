<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class CreateNewEmployee extends CI_Controller {

	var $TPL;

	public function __construct()
	{
		parent::__construct();
		
		
		$this->load->helper('form');	
	}

	public function index()
	{
		$this->TPL['msg'] = '';
		$this->template->show('SupervisorPages/createNewEmployee_view', $this->TPL);
	}
  
	/** function CreateEmployee()
		Purpose: recives post information from form and runs accountcreation's create function with them as parameters.
				 Then it displays the create new employee view again, with a message of success or failure.
	*/
	public function CreateEmployee()
	{
		// Use the form variables to create an account.
		$this->TPL['msg'] = $this->accountcreation->create( 	$this->input->post("Firstname"),
																$this->input->post("Lastname"),
																$this->input->post("password"),
																
																$this->input->post("Lifeguard"),
																$this->input->post("Instructor"),
																$this->input->post("Headguard"),
																$this->input->post("Supervisor")										
															);
			
		// After user was created display a success message.		
		$this->template->show('SupervisorPages/createNewEmployee_view', $this->TPL);
		
				
	} //End of CreateEmployee() function.
	
}

?>

