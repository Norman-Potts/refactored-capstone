<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DeleteEmployee extends CI_Controller {

  var $TPL;

	public function __construct() {
		parent::__construct();		
	    $this->TPL['EmList']= $this->loadList();
		
	}

	public function index() {				
		$this->TPL['EmList'] = $this->loadList();		
		$this->template->show('SupervisorPages/DeleteEmployee_view', $this->TPL);
	}
	
	
	
	
	
	
	
	
	
	

	/** Function loadList
		Purpose: select all employees from db
				 removes session current user.
				 returns array with firstname lastname and employeeID
	*/
	public function loadList()
	{
		//Query database for names and id
		$CI =& get_instance(); 
		$query = $CI->db->query("SELECT Firstname, Lastname, employeeID FROM `Employees`   ");
		$list = $query->result_array();
		session_start();		
		
		$id = $_SESSION['EmployeeID'];
		
		//Remove the current session user from the list.
		$key = array_search($id, array_column($list, 'employeeID'));
		unset($list[$key]);
		
		sort($list);
		return $list;		
	}/* End of Function loadList */
	
	
	
	
	
	
	
	
	
	
	/** Function LoadForm
		Purpose: Loads the delete form after an employee gets selected.
	*/
	public function LoadForm( $ID )			
	{						
		//Query db for ID
		$CI =& get_instance(); 
		$query = $CI->db->query("SELECT Username, Firstname, Lastname, Lifeguard, Instructor, Headguard, Supervisor, employeeID FROM `Employees` WHERE employeeID =".$ID."" );
		$list = $query->result_array();
		
		//Get shifts of employee
		$ShiftsQuery = $CI->db->query("SELECT DATE, startTime, endTime, Position  FROM `Shifts` s JOIN  `Employees` e ON e.employeeID = s.CurrentOwnerEmployeeID WHERE s.CurrentOwnerEmployeeID = '".$ID."';" );
		$ShiftsList = $ShiftsQuery->result_array();
				
		if ( $query->num_rows() > 1 )
		{
			//throw error there is two accounts with the same employeeID
			//$this->load->view('ErrorPage');
			//TODO: Write to error log.		
		}else{
			$this->TPL['ShiftsThisEmployeeHas'] = $ShiftsList;
			$this->TPL['EmForm'] = $list[0];		
			$this->template->show('SupervisorPages/DeleteEmployee_view', $this->TPL);					
		}				
	}/* End of Function LoadForm */
	
	
	
	
	
	
	
	
	
	
	
	/* DeleteThisEmployee
		
		This function deletes the employee given a employeeID.
		
	*/
	public function DeleteThisEmployee( $ID )
	{
		
		
		$CI =& get_instance(); 
		$query = $CI->db->query("SELECT Firstname, Lastname FROM `Employees` WHERE employeeID =".$ID."" );
		$list = $query->result_array();
		
		$Username = "";
		$Username .= $list['0']['Firstname'];
		$Username .= " ".$list['0']['Lastname'];
		
		//Re-assign thier shifts.
		$this->reassignShiftsToNobody($ID);
		
		//Delete them from the database.
		$this->db->delete('Employees', array('employeeID' => $ID));
		
		//Delete their userfiles such as their userprofilepicture folder.
		$filename = "application/assets/img/UserProfilePics/".$ID."/profilepic.jpg";
		if ( file_exists ($filename))
			unlink($filename);
		
		//Delete their userfiles such as their userprofilepicture folder.
		$filename = "application/assets/img/UserProfilePics/".$ID."/".$ID."_profilepic.jpg";
		if ( file_exists ($filename))
			unlink($filename);
		
		//Delete their userfiles such as their userprofilepicture folder.
		$filename = "application/assets/img/UserProfilePics/".$ID."/".$ID."_profilepic_thumb.jpg";
		if ( file_exists ($filename))
			unlink($filename);
		
				
		//Delete their folder in userProfilePics
		$dir = "application/assets/img/UserProfilePics/".$ID;
		if ( file_exists ($dir))
			rmdir($dir);
		
		
		
		//Return a vertic of wheter the delete was successful or not. 
		
		
		//Reload the delete page.		
		$this->TPL['EmList'] = $this->loadList();
		$this->TPL['msg'] = "Employee ".$Username." was deleted.";
		$this->template->show('SupervisorPages/DeleteEmployee_view', $this->TPL);
		
	}
	
	
	
	
	/** function reassignShiftsToNobody()
		
		Purpose: To re-assign the Shifts of the employee being deleted to nobody.
				 This keeps the shifts in the table, so the shifts can get reassigned to a different employee later.
				 
		How does this do this? It uses the EmployeeID of the employee being deleted, to find shifts currently Owned by this employee, 
		and shifts having the default owner ship and re-assgins the id to 0. 0 is the id of a employee record called nobody. Any shifts
		with this id is a shift with out a owner.

		Parameters: EmployeeID
	*/
	public function reassignShiftsToNobody( $ID )
	{
		
		$CI =& get_instance(); //get Code igniter instance.
		
		//Set all shifts that have CurrentOwnerEmployeeID == to $ID to 0.
		$query = $CI->db->query("UPDATE Shifts SET CurrentOwnerEmployeeID = 0 	WHERE CurrentOwnerEmployeeID = ".$ID."" );
		
		
		//Do same with DefaultOwnerID.
		//Set all shifts that have DefaultOwnerID == to $ID to 0.
		$query = $CI->db->query("UPDATE Shifts SET DefaultOwnerEmployeeID = 0 	WHERE DefaultOwnerEmployeeID = ".$ID."" );
		
		
	}// End of reassignShiftsToNobody function
	
}


?>