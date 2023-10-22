<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class UpdateEmployee extends CI_Controller {

  var $TPL;

	public function __construct() {
		parent::__construct();		
	}

	public function index() {		
		$this->template->show('SupervisorPages/UpdateEmployee_view', $this->TPL);
	}
	
	
	//// Purpose: Loads the employeeList with all employee data in employee table.
	public function loadList() {
		/*Query database for names and id*/
		$CI =& get_instance(); 
		$query = $CI->db->query("SELECT Firstname, Lastname, employeeID, Username, Password, Instructor, Lifeguard, Headguard, Supervisor  FROM `Employees`   ");
		$list = $query->result_array();		//TODO: Add Extra Sequerity here..				
		echo json_encode($list );		
	}// End of function loadList


	//// Purpose: Loads the update form form after an employee gets selected, with the selected  employee's data.
	public function LoadForm( $ID  ) {
		$CI =& get_instance(); 
		$query = $CI->db->query("SELECT Username, Firstname, Lastname, Password, Lifeguard, Instructor, Headguard, Supervisor, employeeID FROM `Employees` WHERE employeeID =".$ID."" );
		$list = $query->result_array();
		$this->TPL['employeeList'] = $list[0];		
		$this->template->show('SupervisorPages/UpdateEmployee_view', $this->TPL);		
	}//End of function LoadForm


	//// Purpose: Updates the employee record in the table. It does this by taking the information in the post variables, safety validates them, logic validates them, determines if update can be done,if update is not allowable then return the update failure message. If update is allowable, then update the employees table. Once this process is done the update page is reloaded with the update or failure message.					
	public function UpdateEmployee() { 		
		$employeeID = $_REQUEST['ID']; $Firstname = $_REQUEST['firstname']; $Lastname = $_REQUEST['lastname'];	$Username = "".$Firstname.".".$Lastname.""; $password = $_REQUEST['password']; $Lifeguard = $_REQUEST['lifeguard'];  $Instructor = $_REQUEST['instructor']; $Headguard = $_REQUEST['headguard']; $Supervisor = $_REQUEST['supervisor'];							
		$MessageBack = []; $MessageBack["Instructions"] = "";  //// Response instructions.	
		//// Check data
		$TestEmployeeID = false; $TestFirstname = false; $TestLastname = false; $TestPassword = false; 
		if(  is_numeric( $employeeID )) {$TestEmployeeID = true;} else {	$TestEmployeeID = false;	}					
		$size = strlen($Firstname);		
		if ( $size <= 21 && $size > 0) {							
			$pattCapital = '/^[A-Z][a-z]+$/'; $firstletterCapital = false;			
			$ansFirstCapital = preg_match($pattCapital, $Firstname );	
			if ( $ansFirstCapital == true ) {	$TestFirstname = true;	} else {	$TestFirstname = false;}			
		} 
		else { $TestFirstname = false;	}		
		$size = strlen($Lastname);			
		if ( $size <= 21 && $size > 0) {													
			$pattCapital = '/^[A-Z][a-z]+$/'; $firstletterCapital = false;			
			$ansFirstCapital = preg_match( $pattCapital, $Lastname );	
			if ( $ansFirstCapital == true ) {	$TestLastname = true; } else {	$TestLastname = false;	}
		} 
		else { $TestLastname = false;	}
		$Size = strlen($password);				
		if ( $Size >= 6 && $Size <= 12) {				
			$pattPassword = '/^[a-zA-Z0-9]+$/'; $passAcceptableCharacters = false;			
			$passAcceptableCharacters  = preg_match($pattPassword, $password) ; 	
			if (  $passAcceptableCharacters == true ) 	{	$TestPassword = true;	} else {	$TestPassword = false;	}
		}
		else {	$TestPassword = false;	}
		/// Final pass test.
		if ( $TestEmployeeID == true && $TestFirstname == true && $TestLastname == true && $TestPassword  == true) {	 			 
				try {					
					//// Check business logic ~ cannot have two records with same Username 											
					$CI =& get_instance(); 					
					$query = $CI->db->query("SELECT * FROM `Employees` WHERE `Username` =  '".$Username."'  AND  `employeeID` != ".$employeeID." ;");										
					$list = $query->result_array();										
					if ( ! count($list) > 0 ) {																	
						//// $employeeID $Firstname $Lastname 
						//// $Username  $password  
						//// $lifeguard  $instructor $headguard $supervisor  
						$InputArr = [];				
						$InputArr['Firstname'] = $Firstname; $InputArr['Lastname']  = $Lastname; $InputArr['Password']  = $password; $InputArr['employeeID'] = $employeeID;		$InputArr['Username'] = $Username;
						$InputArr['Lifeguard'] = 1; $InputArr['Instructor'] = 0; $InputArr['Headguard'] = 0; $InputArr['Supervisor'] = 0;				
						//Makes Certification values simple true and false values so nothing is hid in true.										
						if( $Lifeguard == "true" ) {	 $InputArr['Lifeguard'] = 1;  } else {	$InputArr['Lifeguard'] = 0; }					
						if( $Instructor == "true" ) {      $InputArr['Instructor'] = 1;   } else { 	$InputArr['Instructor'] = 0;   }
						if( $Headguard == "true" ) {   $InputArr['Headguard'] = 1;   } else { $InputArr['Headguard'] = 0;  }
						if( $Supervisor == "true" ) {	 $InputArr['Supervisor'] = 1;     } else {	$InputArr['Supervisor'] = 0;	 }		
						$InputArr['Availability'] = 'Nothing yet'; 									
						try {					
							$CI =& get_instance(); 	
							$this->db->where('employeeID', $employeeID);							
							$UPDATESTATEMENT = $this->db->set($InputArr)->get_compiled_update('Employees');														
							$query = $CI->db->query($UPDATESTATEMENT);																	
							$MessageBack["Instructions"] =  4;  //Update was  allowed	
						} catch(Exception $e) {					
							$MessageBack["Instructions"] = 3; 		
							$MessageBack["Reasons"] =  "Database error during update." ;						
						}																		
					} else {	
						$MessageBack["Instructions"] = 1;
						$MessageBack["Reasons"] =  "Cannot have two employees with matching usernames.  " ;	
					} 				
				} catch(Exception $e) {					
					$MessageBack["Instructions"] = 3; 		
					$MessageBack["Reasons"] =  "Database error during check for matching usernames." ;						
				}										
		} else {
			$MessageBack["Instructions"] = 1;
			$MessageBack["Reasons"] = "Input data was incorrect format. " ;
		}	
		echo json_encode( $MessageBack );
	}

	
}//End of UPDATE Employee class.

?>


