<?php 
	/** 	DeleteAShift
			Created By Norman Potts
	*/
	
defined('BASEPATH') OR exit('No direct script access allowed');

class DeleteAShift extends CI_Controller {	
	/* This manages the Delete A Shift page.  */
	
	
	
  var $TPL;

  public function __construct()
  {
    parent::__construct();
    
  }

  
  
  
  
  public function index()
  {
	$this->TPL['Date'] =  $this->theschedule->GetTodaysDate(); //used in Display date varialbe on CreateAShift_view		
	$this->todayDate = $this->theschedule->GetFormatTodayDate(); //Get todays date
	$this->TPL['TodaysScheduleArray'] = $this->theschedule->QueryByGivenDay( $this->todayDate ); //use todays date to run the query for the schedule array.
	$this->TPL['SelectArray'] =  $this->theschedule->CreateSelectArray(); //Creates the select array variables.		
	$this->TPL['CurrentYear'] = $this->theschedule->GetThisYear();
	$this->TPL['CurrentMonth'] = $this->theschedule->GetThisMonth();
	$this->TPL['CurrentDay'] = $this->theschedule->GetThisDay();		
	$this->TPL['LISTofDATES'] = $this->theschedule->CreateSelectArray();
	$this->template->show('SupervisorPages/DeleteAShift_view', $this->TPL);
  }

  
  
  
  	/** Function reloadTheScheduleArraybyGivenDate	
			Purpose: reloads the schedule by a date given in js function reloadTheScheduleArray()
			retuns echo $data			
	*/
	public function reloadTheScheduleArraybyGivenDate()
	{		
		$GivenDate = $_REQUEST['GivenDate']; 
		$data = $this->theschedule->QueryByGivenDay( $GivenDate );	
		echo  $data;
	}/* End of function reloadTheScheduleArraybyGivenDate  */
  
  	

	
	/** Function DeleteTheShift
			Purpose: Deletes a shift.
	*/
	public function DeleteTheShift( )
	{	
		//Delete shift where CurrentOwnerID startTime endTime date
		$CI =& get_instance(); 	
		/* Get post variables needed for this function. */
		$shiftid = $_REQUEST['shiftid'];
		$CurrentOwnerID = $_REQUEST['EmployeeID'];
		$startTime = $_REQUEST['startTime'];
		$endTime = $_REQUEST['endTime'];
		$TheDate = $_REQUEST['TheDate'];
	
		$writeDelete = "DELETE FROM `Shifts` WHERE `ShiftId` = '".$shiftid."';"; //TODO: Fix security? 				
		$query = $CI->db->query($writeDelete);
				
		/** Notificcation...
		 *	4. You were removed from a shift by a supervisor
		 */		 
		$NotificationString = "You were removed from the shift on ".$TheDate.", from ".$startTime." to ".$endTime.";";
		$CreatedDateAndTime = date("Y-m-d H:i:s");
		$InsertNotification = "INSERT INTO `Notifications` ( `employeeID`,`type`,`message`, `readOrUnread`, `CreatedDateAndTime` ) VALUES (  '".$CurrentOwnerID."', '4', '".$NotificationString."', '0', '".$CreatedDateAndTime."' )";				
		$query = $CI->db->query($InsertNotification);
			
	}/* End of Function DeleteTheShift */
  
}/* End of DeleteAShift controller */
?>