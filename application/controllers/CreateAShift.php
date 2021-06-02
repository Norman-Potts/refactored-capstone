<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CreateAShift extends CI_Controller {
	
	/* This manages the Create A Shift page.  */
	
	
	
	var $TPL;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function index()
	{
		
		/**
			PHP TPL variables
			$SelectArray an array object that holds the dates for the drop down select boxs.
			$TodaysScheduleArray an object that holds the information to build the schedule.
			$CurrentYear holds current year as YYYY. Used to set determine current year on the html front end.
			$CurrentDay holds current month as MM. Used to set determine current month on the html front end.
			$Date holds the current date as 10 August 2017 format. Used to display date in date box on first load.
		*/
		$this->TPL['Date'] =  $this->theschedule->GetTodaysDate(); //used in Display date varialbe on CreateAShift_view		
		$this->TPL['SelectArray'] =  $this->theschedule->CreateSelectArray(); //Creates the select array variables.		
		$this->TPL['CurrentYear'] = $this->theschedule->GetThisYear();
		$this->TPL['CurrentMonth'] = $this->theschedule->GetThisMonth();
		$this->TPL['CurrentDay'] = $this->theschedule->GetThisDay();		
		$this->todayDate = $this->theschedule->GetFormatTodayDate(); //Get todays date
		$this->TPL['TodaysScheduleArray'] = $this->theschedule->QueryByGivenDay( $this->todayDate ); //use todays date to run the query for the schedule array.
		$this->TPL['LISTofDATES'] =  $this->theschedule->CreateSelectArray(); //Creates the select array variables.
			
		//show html front end template with these tpl variables.
		$this->template->show('SupervisorPages/CreateAShift_view', $this->TPL);
	}	
	
	
	
	
	/** Function MakeDate()
		purpose: Produces the date i YYYY-MM-DD format.
	*/
	function MakeDate( $Year, $Month, $Day )
	{
		$date = "".$Year."-";	
		//Determine the month.
		switch ($Month) {
			case "January":
				$date .= "01-";
				break;
			case "February":
				$date .= "02-";
				break;
			case "March":
				$date .= "03-";
				break;
			case "April":
				$date .= "04-";
				break;
			case "May":
				$date .= "05-";
				break;
			case "June":
				$date .= "06-";
				break;
			case "July":
				$date .= "07-";
				break;
			case "August":
				$date .= "08-";
				break;
			case "September":
				$date .= "09-";
				break;
			case "October":
				$date .= "10-";
				break;
			case "November":
				$date .= "11-";
				break;
			case "December":
				$date .= "12-";
				break;
		}		
		//Prepare $day variable.
		if( $Day < 10 )
		{  $Day = "0".$Day; }			
		else
		{ $Day = "".$Day; }		
		$date .= $Day; //attach dayy to $date. $date is now ready.				
		
		return $date;
	}
	
	
	
	
	/** function reloadEmployeeSelectBox()	
			Purpose: reloads the employe select box on the create shift form.
			Gets called when a step input gets changed above step 4, as in 1 2 or 3.
			Gets called by an ajax call in function reloadEmployeeSelectBox() on create shift view.
			This means the data gets returned as an echo.				
		returns as echo json.encode($list);		
	*/
	public function reloadEmployeeSelectBox()
	{   $CI =& get_instance(); 				
		$Day = $_REQUEST['Day']; 				//recives an integer no zero in front
		$Month = $_REQUEST['Month'];			//recives a string month
		$Year = $_REQUEST['Year'];				//recives a integer for the year
		$StartTime = $_REQUEST['StartTime'];	//recives a number from 0 to 47, which is 48 time placements for a 24 hour clock split into half hours
		$EndTime = $_REQUEST['EndTime'];		//recives a number from 0 to 47, which is 48 time placements for a 24 hour clock split into half hours
		$ShiftType = $_REQUEST['ShiftType'];		//Position will hold the chosen shift type. 1234 or 5! if it is 5 it will be any shift type.
		$date = $this->MakeDate($Year, $Month, $Day );
				
		/* Start building date variable for a query as YYYY-MM-DD */
		
		/* Prepare Start time. */
		$STime; //query variable $Stime holds startTime in format hh:MM:SS
		$ETime; //query variable $ETime holds endTime hh:MM:SS				
		$StartTime = $StartTime +1; //removes zero based 47 becomes 48 and 0 becomes 1
		$Minutes;
		$Hours;
		$mod24hour = $StartTime%2;
		if ($mod24hour == 1)
		{ $Hours = ($StartTime/2) - 0.5; $Minutes = "30"; }
		else
		{ $Hours = ( $StartTime/2 ); $Minutes = "00"; }
		if( $Hours < 10 )
		{ $Hours = "0".$Hours.""; }
		$STime = "".$Hours.":".$Minutes.":00";
		//startTime variable $Stime is now read.
		
		
		/* Prepare End Time */
		$EndTime = $EndTime;
		$mod24hour = $EndTime % 2;
		if ($mod24hour == 1)
		{ $Hours = ($EndTime/2) - 0.5; $Minutes = "30"; }
		else
		{ $Hours = ( $EndTime/2 ); $Minutes = "00"; }
		if( $Hours < 10 )
		{ $Hours = "0".$Hours.""; }
		$ETime = "".$Hours.":".$Minutes.":00";
		//endTime variable $Etime is now read.

		/* Process the $ShiftType variable.
			determine which type of shift to query for.			
			$ShiftType == 1 Lifeguard
			$ShiftType == 2 Instructor
			$ShiftType == 3 HeadGuard
			$ShiftType == 4 Supervisor
			$ShiftType == 5 ANY				
		*/
		
		$queryForShiftType = ""; // attaches to the end of $SELECTSTATEMENT to query for the correct kind of shift.
		switch( $ShiftType ) {
			case 1:
				$queryForShiftType = "AND `Lifeguard` IS true";
				break;
			case 2:
				$queryForShiftType = "AND `Instructor` IS true";
				break;
			case 3:
				$queryForShiftType = "AND `HeadGuard` IS true";
				break;
			case 4:
				$queryForShiftType = "AND `Supervisor` IS true";
				break;
			case 5:
				$queryForShiftType = " ";
				break;
			default:
				$queryForShiftType = " ";
			
		}//$queryForShiftType is ready.			

		
		/* Build The Query, Then run it. */
		$SELECTSTATEMENT = "SELECT Firstname, Lastname, employeeID, Lifeguard, Instructor, Headguard, Supervisor, Availability FROM `Employees`
		WHERE employeeID NOT IN(
		SELECT CurrentOwnerEmployeeID FROM Shifts 
		WHERE `date` = '".$date."' 
			AND  `startTime` Between '".$STime."' AND '".$ETime."'
		OR `date` = '".$date."' 
			AND	`endTime` Between '".$STime."' AND '".$ETime."'
		OR `date` = '".$date."' 
			AND	'".$STime."' Between `startTime`  AND `endTime`) 
		".$queryForShiftType.";";
		
		
		$query = $CI->db->query(	$SELECTSTATEMENT	);
		$list = $query->result_array();
		
		/* Parse out the unAvailable employees based on their availability.*/
		foreach($list as $em  => $Employee )
		{
			$Av = $list[$em]["Availability"];
			$Availability = json_decode($Av);			
			$list[$em]["Availability"] = $Availability;								
		}
		
		
		
		/* Return the list of employees who are available. */
		 echo  json_encode($list);		
	}//End of reloadEmployeeSelectBox
	
	
	
	
	
	
	
	
	
	
	/** function reloadTheScheduleArraybyGivenDate()
		purpose: reloads the schedule by a date given in js function reloadTheScheduleArray()		
		retuns echo $data			
	*/
	public function reloadTheScheduleArraybyGivenDate()
	{		
		$GivenDate = $_REQUEST['GivenDate']; 
		$data = $this->theschedule->QueryByGivenDay( $GivenDate );		
		echo  $data;
	}//End of reloadTheScheduleArraybyGivenDate function
  
  	
	
	
	
	
	
	
	
	
	/** Function CreateTheShift		
			Purpose: To recive the post variables from the create shift form, valadete them,
			then prepare them, then inserts the shift in to the db.
			
	*/
	public function CreateTheShift( )
	{   $SELECTSTATEMENT = "";
		$FeedBack = 0; 							//Feedback for the return message.
		$CI =& get_instance(); 
		try
		{
			/* get data from post */
			$Day = $_REQUEST['Day']; 					//Receives an integer no zero in front
			$Month = $_REQUEST['Month'];				//Receives a string month
			$Year = $_REQUEST['Year'];					//Receives a integer for the year
			$StartTime = $_REQUEST['StartTime'];		//Receives a number from 0 to 47, which is 48 time placements for a 24 hour clock split into half hours
			$EndTime = $_REQUEST['EndTime'];			//Receives a number from 0 to 47, which is 48 time placements for a 24 hour clock split into half hours
			$ShiftType = $_REQUEST['ShiftType'];		//Receives a int 1,2,3, or 4.
			$EmployeeID = $_REQUEST['ID'];				//Receives a integer for employees id.
			/* Declare input variables  */
			
			$ShiftID = null; 							//ShiftID specifies a shift.
			$ID;										//ID an int
			$STime; 									//StartTime hh:MM:SS
			$ETime; 									//EndTime hh:MM:SS
			$date=$this->MakeDate($Year,$Month,$Day );	//date yyyy-mm-dd
			$Position = (int)$ShiftType; 				//ShiftType, 1,2,3,4		
			$ID = $EmployeeID;				
			
					
			/* Prepare Start time. */
			$StartTime = $StartTime; 
			$Minutes; $Hours;		
			$mod24hour = $StartTime%2;
			if ($mod24hour == 1)
			{ $Hours = ($StartTime/2) - 0.5; $Minutes = "30"; }
			else
			{ $Hours = ( $StartTime/2 ); $Minutes = "00"; }
			if( $Hours < 10 )
			{ $Hours = "0".$Hours.""; }
			$STime = "".$Hours.":".$Minutes.":00";
			/* tart time is prepared for insertion. */
			
					
			/* Prepare End Time	*/
			$EndTime = $EndTime ; 
			$mod24hour = $EndTime%2;
			if ($mod24hour == 1)
			{ $Hours = ($EndTime/2) - 0.5; $Minutes = "30";	}
			else
			{ $Hours = ( $EndTime/2 ); $Minutes = "00";	}
			if( $Hours < 10 )
			{ 	$Hours = "0".$Hours."";		}
			$ETime = "".$Hours.":".$Minutes.":00";
			/* ETime is prepared for insertion*/
			
			
			/*Confirm Employee is not already scheduled for this time.*/				
			$SELECTSTATEMENT = "SELECT count(*) FROM Shifts 
			WHERE `CurrentOwnerEmployeeID` = '".$ID."' AND (
			`date` = '".$date."' 
			AND  `startTime` Between '".$STime."' AND '".$ETime."'	
			OR `date` = '".$date."' 
			AND	`endTime` Between '".$STime."' AND '".$ETime."' 	
			OR `date` = '".$date."' 
			AND	'".$STime."' Between `startTime`  AND `endTime`) ;";
			$query = $CI->db->query(	$SELECTSTATEMENT	);
			$ShiftsAtSameTime = $query->result_array();	
			
			/*If the count from the query above is zero there is no shift time conflict other wise
			there is a shift at this time for this employee. */
			if ( $ShiftsAtSameTime[0]["count(*)"]  == '0')
			{										
				$pos = "";
				/**
					Confirm the employee with this shift has the certification for the shift.		
				*/			
				switch( $Position ) {
					case 1:
						$pos = "Lifeguard";
						break;
					case 2:
						$pos = "Instructor";
						break;
					case 3:
						$pos = "Headguard";
						break;
					case 4:
						$pos = "Supervisor";
						break;
					default:
						$pos = "Crash!!!-- ";							
				}						
				$SELECTSTATEMENT = "SELECT ".$pos."  FROM `Employees`	WHERE employeeID = ".$ID.";";
				$query = $CI->db->query(	$SELECTSTATEMENT	);
				$poslist = $query->result_array();		
				
				
				if (  $poslist[0][$pos] == '1')
				{   //If the employee has the certification for this shift type insert a shift and notification.
					/* Create the data array to be inserted. */
					$data = array ( 					
							'DefaultOwnerEmployeeID' => $ID,
							'CurrentOwnerEmployeeID' => $ID,
							'ShiftID' => $ShiftID,
							'startTime' => $STime,
							'endTime' => $ETime,
							'date' =>  $date,						
							'Position' => $Position				
					);										
					$sql = $CI->db->insert( 'Shifts'  , $data );	//Insert the shift into the database./*TODO: error Catch here probably*/		
					/**
					 * Make Notification for creating a shift...
					 */
					switch( $Position ) {
						case 1:
							$STRposition = "Lifeguarding";
							break;
						case 2:
							$STRposition = "Instructoring";
							break;
						case 3:
							$STRposition = "HeadGuarding";
							break;
						case 4:
							$STRposition = "Supervisoring";
							break;
						default:
							$STRposition = " ";							
					}
					$DisplayMessage = "You were assigned a Shift for ".$date." from ".$STime." to ".$ETime." as ".$STRposition.".";				
					$data = array ( 					
							'employeeID' => $ID,
							'type' => 5,
							'message' => $DisplayMessage,
							'readOrUnread' => 0,
							'CreatedDateAndTime' => date("Y-m-d H:i:s"),
					);					
					$sql = $CI->db->insert( 'Notifications'  , $data );	//Insert the shift into the database.
					$FeedBack = 1;
				}
				else
				{ //... or else send feedback of that they don't have the shift.
					$FeedBack = 2;
				}
			}
			else
			{	//Employee already has a shift at that time.
				$FeedBack = 3;
			}
		}
		catch(Exception $e)
		{
			$FeedBack = 0;
		}
		
		echo json_encode( $FeedBack );		
		
	}/* end of CreateTheShift function */ 

}
?>
