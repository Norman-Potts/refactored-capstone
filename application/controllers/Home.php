<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Home extends CI_Controller {

		var $TPL;

		public function __construct()
		{
			parent::__construct();
		}

		public function index()
		{
			$this->TPL['LISTofDATES'] =  $this->theschedule->CreateSelectArray(); //Creates the select array variables.
			$this->TPL['CurrentYear'] = $this->theschedule->GetThisYear();
			$this->TPL['CurrentMonth'] = $this->theschedule->GetThisMonth();
			$this->TPL['CurrentDay'] = $this->theschedule->GetThisDay();								
			$this->template->show('home_view', $this->TPL);
		}









		
		/** Function takeSubSlip
				Purpose: Submit the subslips
		
		*/
		public function TakeSubSlip()
		{
			$takerID = $_REQUEST['takerID'];
			$ownerID = $_REQUEST['ownerID'];			
			$subslipID = $_REQUEST['subslipID'];
			$ShiftID = $_REQUEST['ShiftID'];
			$instructions = 0;			
			if( is_numeric($takerID ) == true && is_numeric( $subslipID) == true && is_numeric($ownerID ) == true )
			{
				$AutoApprove = false;
				//Check status of approve subslips.
				try
				{
					$AutoApprove = $this->approvecontrol->getAutoAppove();
				}
				catch(Exception $e)
				{	$AutoApprove = false;	}//TODO: write to error log...
				
				$TakenDateAndTime = date("Y-m-d H:i:s");
				try 
				{			

					$SELECTSTATEMENT = "SELECT * FROM `Shifts` WHERE `ShiftID` = ".$ShiftID.";";
					$CI =& get_instance(); //TODO: FIX security.
					$query = $CI->db->query($SELECTSTATEMENT);
					$list = $query->result_array();			
					
					$shiftDate = $list[0]["date"];
					$startTime = $list[0]["startTime"];
					$endTime = $list[0]["endTime"];
					$Position = $list[0]["Position"];
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
				
					$SELECTSTATEMENT = "SELECT * FROM `Employees` WHERE `employeeID` = ".$ownerID.";";
					$CI =& get_instance(); //TODO: FIX security.
					$query = $CI->db->query($SELECTSTATEMENT);
					$list = $query->result_array();			
					$Firstname = $list[0]["Firstname"];
				
					$SELECTSTATEMENT = "SELECT * FROM `Employees` WHERE `employeeID` = ".$takerID.";";
					$CI =& get_instance(); //TODO: FIX security.
					$query = $CI->db->query($SELECTSTATEMENT);
					$list = $query->result_array();			
					$TakerFirstname = $list[0]["Firstname"];
				
				
				
				
					
					
					//Write notifications
					$CI =& get_instance();												
					$UPDATESTATEMENT = "";

					/** If auto-approve is false then...
					 *   	Just up date the subslip to taken.
					 * 	Otherwise
					 * 		Write notifications for a completed shift exchange.
					 *  After the if statement...
					 *	 	Write the notifications that don't apply to auto-approve. Writing them 
					 * 		second because they should appear second in the notifications box on the
					 * 		my profile page.
					 */					
					if( $AutoApprove == false )
					{
						$UPDATESTATEMENT = "UPDATE `SubSlips` SET `TakenDateAndTime` = '".$TakenDateAndTime."' , `TakenTrueorFalse` = '1' , `TakerID` = '".$takerID."' WHERE `subslipID` = '".$subslipID."' ;";						
						$query = $CI->db->query($UPDATESTATEMENT);	
					}
					else
					{
						$UPDATESTATEMENT = "UPDATE `SubSlips` SET `TakenDateAndTime` = '".$TakenDateAndTime."', `TakenTrueorFalse` = '1' , `TakerID` = '".$takerID."', `completed` = '1' WHERE `subslipID` = '".$subslipID."' ;";
						$query = $CI->db->query($UPDATESTATEMENT);		
						$UPDATE_shift_STATEMENT = "UPDATE `Shifts` SET  `CurrentOwnerEmployeeID` = '".$takerID."' WHERE `ShiftID` = '".$ShiftID."' ;";																
						$query = $CI->db->query($UPDATE_shift_STATEMENT);
						
						/** Notification...
						 * 2. Your subslip was approved...
						 */
						$DisplayMessage = "Your subslip with ".$TakerFirstname." for ".$shiftDate." from ".$startTime." to ".$endTime." as ".$STRposition.", Has been APPROVED!";				
						$data = array ( 					
								'employeeID' => $ownerID,
								'type' => 2,
								'message' => $DisplayMessage,
								'readOrUnread' => 0,
								'CreatedDateAndTime' => date("Y-m-d H:i:s"),
						);					
						$sql = $CI->db->insert( 'Notifications'  , $data );	
						
						/** Notification...
						 * 8. The Shift you took was approved...
						 */
						$DisplayMessage = "The subslip you signed  with ".$Firstname." for a ".$STRposition." shift on ".$shiftDate." from ".$startTime." to ".$endTime.", Has been APPROVED!";				
						$data = array ( 					
								'employeeID' => $takerID,
								'type' => 8,
								'message' => $DisplayMessage,
								'readOrUnread' => 0,
								'CreatedDateAndTime' => date("Y-m-d H:i:s"),			
						);					
						$sql = $CI->db->insert( 'Notifications'  , $data );	
						
						
						/** Notification... 
						 * 5. Make Notification for creating a shift...
						 */
						$DisplayMessageB = "You were assigned a Shift for ".$shiftDate." from ".$startTime." to ".$endTime." as ".$STRposition.".";				
						$data = array ( 					
								'employeeID' => $takerID,
								'type' => 5,
								'message' => $DisplayMessageB,
								'readOrUnread' => 0,
								'CreatedDateAndTime' => date("Y-m-d H:i:s"),			
						);					
						$sql = $CI->db->insert( 'Notifications'  , $data );	
										
					}//End of else for if auto-approve false.
					
					/**	Notificcation...				
					 *	1. Your SubSlip was taken.	
					 */					
					$DisplayMessage = "Your subslip was taken for ".$shiftDate." from ".$startTime." to ".$endTime." as ".$STRposition.". The Subslip was taken by ".$TakerFirstname.".";				
					$data = array ( 					
							'employeeID' => $ownerID,
							'type' => 1,
							'message' => $DisplayMessage,
							'readOrUnread' => 0,
							'CreatedDateAndTime' => date("Y-m-d H:i:s"),
										
					);					
					$sql = $CI->db->insert( 'Notifications'  , $data );	//Insert the shift into the database.
					
					/** Notificcation...					
					 *  7. You took someones SubSlip.
					 */					
					$DisplayMessage =  "You signed ".$Firstname."s subslip for a shift on ".$shiftDate." from ".$startTime." to ".$endTime." as ".$STRposition."." ;									
					$data = array ( 					
							'employeeID' => $takerID,
							'type' => 7,
							'message' => $DisplayMessage,
							'readOrUnread' => 0,
							'CreatedDateAndTime' => date("Y-m-d H:i:s"),			
					);					
					$sql = $CI->db->insert( 'Notifications'  , $data );	//Insert the shift into the database.
										
					$instructions = 1;
				}
				catch(Exception $e)
				{
					//TODO: write to error log.
					$instructions = 0;
				}
			}
			
				
			echo $instructions;
		}/*End of function submitSubSlip*/
		
		
		
		
		
		
		
		/**  Function GetThisEmployeesShifts			
				Purpose: Query the shift's db and returns all the
					     shifts this user has.					 
				Returns an object with booleans for ShiftHasASubSlip DateOfShiftHasAlreadyPast			
				Parameters: $ID The id of the employee being queryed.
		*/
		public function GetThisEmployeesShifts(  )
		{
			$CI =& get_instance();			
			$ID = $_REQUEST['ID']; //parameter.
			$SELECTSTATEMENT = "SELECT * FROM `Shifts` WHERE `CurrentOwnerEmployeeID` = '".$ID."'; ";
			$query = $CI->db->query(	$SELECTSTATEMENT	);
			$list = $query->result_array();			
			$size = count($list);
			$AllShiftsThisUserHas = array($size);			
			for( $key = 0; $key < $size; $key++ )
			{
				
				$AllShiftsThisUserHas[$key] = [];//This line fixed the scalar problem beause it makes each item an array.				
				$AllShiftsThisUserHas[$key]["CurrentOwnerEmployeeID"] = $list[$key]["CurrentOwnerEmployeeID"];
				$AllShiftsThisUserHas[$key]["DefaultOwnerEmployeeID"] = $list[$key]["DefaultOwnerEmployeeID"];
				$AllShiftsThisUserHas[$key]["date"] = $list[$key]["date"];
				$AllShiftsThisUserHas[$key]["startTime"]= $list[$key]["startTime"];
				$AllShiftsThisUserHas[$key]["endTime"]= $list[$key]["endTime"];
				$AllShiftsThisUserHas[$key]["Position"]= $list[$key]["Position"];
				$AllShiftsThisUserHas[$key]["ShiftID"] = $list[$key]["ShiftID"];
				
				$EmployeeID = $list[$key]["CurrentOwnerEmployeeID"];
				$ShiftDate =  $list[$key]["date"];
				$Position = $list[$key]["Position"];
				$startTime = $list[$key]["startTime"];
				$endTime = $list[$key]["endTime"];				
				$ShiftHasASubSlip = false;				

				/* Query for subslips of this employee and shift date is this shift date and time is this time and taken is false.*/
				$SELECTSTATEMENT = "SELECT * FROM `SubSlips` WHERE `CreatorID` = '".$EmployeeID."' AND `ShiftDate` = '".$ShiftDate."' AND `Position` = '".$Position."' AND `startTime` = '".$startTime."' AND `endTime` = '".$endTime."' AND `TakenTrueorFalse` = '0';";				
				$query = $CI->db->query(	$SELECTSTATEMENT	);
				$SubSliplist = $query->result_array();			
				/*
					Count Subslips for this shift. If there are more than zero then there are 
					subslips for this shift, otherwise there are no subslips for this shift.
				*/
				$countofSubList = count($SubSliplist);				
				if ( $countofSubList > 0 	)
				{ 	//Already have a sub slip
					$ShiftHasASubSlip = true;
				}
				else
				{	//Does not have a sub slip
					$ShiftHasASubSlip = false;
				}
				$AllShiftsThisUserHas[$key]["ShiftHasASubSlip"] = $ShiftHasASubSlip;								
				$Todaysdate = $this->theschedule->YYYMMDDTodayplz();				
				/**
				 *	If today's date is greater than or equal to shift date than
				 *		DateOfShiftHasAlreadyPast equals true.
				 *	otherwise
				 *		DateOfShiftHasAlreadyPast equals false.
				 */				
				if ( $Todaysdate > $AllShiftsThisUserHas[$key]["date"] 	)
				{	$AllShiftsThisUserHas[$key]["DateOfShiftHasAlreadyPast"] = true;	}
				else
				{	$AllShiftsThisUserHas[$key]["DateOfShiftHasAlreadyPast"] = false;	}
				
			
			}//End of for loop
			
			echo json_encode($AllShiftsThisUserHas);
			
		}//End of function GetThisEmployeesShifts
		
		
		
		
		
		
		
		
		
		/** Function MakeThisSubSlip			
				Purpose: Checks SubSlip data, inserts subslips and returns a success, fail or 
				         error message.
			
			echo MessageBack;
		*/
		public function MakeThisSubSlip()
		{
			$MessageBack = []; // Will hold an approriate message and instructions for JS.			
			$MessageBack["Instructions"] = "";	// JS instructions.
					
			//Get input array.
			$EmployeeID = $_REQUEST['EmployeeID'];
			$ShiftDate = $_REQUEST['Date'];
			$Position = $_REQUEST['Position'];
			$startTime = $_REQUEST['startTime'];
			$endTime = $_REQUEST['endTime'];			
			$Reason = $_REQUEST['Reason'];
			$ShiftID = $_REQUEST['ShiftID'];
			/*	TODO:
				Should check variables here.
			*/
			$inncorectDataFlag =  false; //False data is not incorrect. True means data is inncorect and cancel making this subslip.						
			
			//Check $Reason for acceptable data.
			if(	$Reason == null || $Reason == "" )
			{	//Write to error log that reason was not filled out meaning JavaScript vaildation failed. TODO				
				$inncorectDataFlag = true;
			}							
			
			if ( $inncorectDataFlag == false )
			{
				//Record the date and time this sub slip was created.
				$CreatedDateAndTime = date("Y-m-d H:i:s");			
				//Check and make sure subslip hasnt already been submitted.
				$CI =& get_instance();			
				try
				{
					$SELECTSTATEMENT = "SELECT * FROM `SubSlips` WHERE `CreatorID` = '".$EmployeeID."' AND `ShiftDate` = '".$ShiftDate."' AND `Position` = '".$Position."' AND `startTime` = '".$startTime."' AND `endTime` = '".$endTime."' AND `TakenTrueorFalse` = '0';";
					$query = $CI->db->query(	$SELECTSTATEMENT	);
					$list = $query->result_array();
				}
				catch(Exception $e) 
				{
					
					$MessageBack["Instructions"] = 0; //Display message.
					/*	TODO:
						Need to Write to Error log here.
					*/
				}
				
				if ( count($list) > 0	)
				{
					//Return msg saying subslip has already been submitted for this shift.
					
					$MessageBack["Instructions"] = 2; //Display message.
					/*
						TODO:
						Need to write to error log because this should have been handled by JS.
					*/
				}
				else
				{
					try 
					{
						
						/*
							Changed my insert method. 
							Now using the Codigiter method get_compiled_insert because It it escapes automatically creating safer queries.
							get_compiled_insert returns an string of the sql statement.
							This string can now be reviewed before its run.							
							Not sure how much extra should be done to make this safer. 
							Not sure how get_compiled_insert makes it safer other than it escapes it...						
						*/
						$myInsertArr = array(
							'CreatorID' => $EmployeeID,
							'TakerID' => NULL,
							'ShiftDate' => $ShiftDate,
							'startTime' => $startTime,
							'endTime'=> $endTime,
							'Position' =>$Position,
							'Reason' => $Reason,
							'TakenTrueorFalse' => 0,
							'TakenDateAndTime' => NULL,
							'CreatedDateAndTime'=> $CreatedDateAndTime,
							'ShiftID' => $ShiftID,
							'completed' => 0
						);
						
						
						$InsertStatement = $this->db->set($myInsertArr)->get_compiled_insert('SubSlips');
						/*
							Could analyse string more here for better sequerity.
							TODO: have a log of insert statements.
						*/
						$query = $this->db->query(	$InsertStatement	);
					

						/** 
						 * Create a new notification for this employeeID.
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
						$DisplayMessage = "You have submitted a subslip for ".$ShiftDate." from ".$startTime." to ".$endTime." doing ".$STRposition.".";
						try{
							//Put Shift ID into Message and build the display message later.		
							
							$NotificaionInsertArr = array(
								'employeeID' => $EmployeeID,
								'type' => 6,
								'message' => $DisplayMessage,
								'readOrUnread' => 0,
								'CreatedDateAndTime' => date("Y-m-d H:i:s"),
							);																									
							$InsertNotificationStatement = $this->db->set($NotificaionInsertArr)->get_compiled_insert('Notifications');
							$query = $this->db->query(	$InsertNotificationStatement	);	
							//CountNotificationsAndUpdateNumber.
							
							
							$MessageBack["Instructions"] = 1; //Display message. Reload SubSlip form Step 2.
						}
						catch(Exception $e) 
						{
							$MessageBack["Instructions"] = 5; //Notifications failed.
						}
						
						
					}
					catch(Exception $e) 
					{	
						$MessageBack["Instructions"] = 0; //Display Message.
						/*	TODO:
							Need to Write to Error log here.
						*/
					
					}//End of TryCatch on insert.			
					
				}//End of if list count is greater than zero.
				
			}
			else
			{
				//TODO Write to error log that someone tried to insert incorrectData that wasnt cleared by JavaScript
				$MessageBack["Instructions"] = 3;
			}
						
			echo json_encode(  $MessageBack	); //Return message with instructions.
		}/* End of function makethissubslip*/
		
		
		
		
		
		
		
		
		
		/** Function GetNewNotifications
			Purpose: 
		*/
		public function GetNewNotifications( )
		{		
			$newNotifications = $this->availability->GetNewNotifications();
			echo  $newNotifications;			
		}/*End of Function GetNewNotifications*/
	
		
		
		
		
		
		
		
		
		
		
		
		/** Function GetALLSubSlips
				Purpose: Gets all subslips from table that haven't been taken and has the employee's
						 certifications and dont conflict with other shifts. Returns a formatted 
						 list of necessary information to develop shifts up for grabs div.
			
			return list
		*/
		public function GetAvailableSubSlips() {
			$EmployeeID = $_REQUEST['ID'];  $Lifeguard = $_REQUEST['Lifeguard'];  $Instructor = $_REQUEST['Instructor'];  $Headguard = $_REQUEST['Headguard'];  $Supervisor = $_REQUEST['Supervisor'];		
			$CI =& get_instance();  $TodayDate = $this->theschedule->YYYMMDDTodayplz();						
			///Make a SQL Select Statement that gets the necessary information from SubSlip table and  Employee table. The current Logic structure below adds "position equals digit" depending  on the certifications passed by the $_REQUEST array.						
			$strlife ="";
			if( $Lifeguard == true	) {
				$strlife = "Position = 1 OR ";	
			} else { $strlife = ""; }
			$strIns = "";
			if($Instructor == true) {
				$strIns = "Position = 2 OR ";
			} else { $strIns = ""; }
			$strHe = "";
			if($Headguard == true) {
				$strHe = "Position = 3 OR ";
			} else { $strHe = ""; }
			$strSup = "";
			if($Supervisor == true) {
				$strSup = "Position = 4 OR ";
			} else { $strSup = ""; }		
			$posStr = " ".$strlife." ".$strIns." ".$strHe." ".$strSup." Position = 10";						
			$SELECTSTATEMENT = "SELECT S.CreatorID, S.ShiftID, S.subslipID, S.ShiftDate,
			S.startTime, S.endTime, S.Position, S.Reason, S.CreatedDateAndTime, E.Firstname, 
			E.Lastname, E.Instructor, E.Lifeguard, E.Headguard, E.Supervisor
			FROM `SubSlips` S 
			JOIN `Employees` E ON S.CreatorID = E.employeeID 
			WHERE `TakenTrueorFalse` = '0' AND ShiftDate >  '".$TodayDate."' AND (".$posStr.");";
			$query = $CI->db->query(	$SELECTSTATEMENT	);
			$ALL_SubSlipsthisEmployeeHasCertsFor = $query->result_array();			
			//// Need a way to remove subslips that this employee cannot take based on the shifts the employee already has.						
			foreach( $ALL_SubSlipsthisEmployeeHasCertsFor as $key => $SubSlip ) {						
				$STime = $ALL_SubSlipsthisEmployeeHasCertsFor[$key]["startTime"];
				$ETime = $ALL_SubSlipsthisEmployeeHasCertsFor[$key]["endTime"];	
				$date =  $ALL_SubSlipsthisEmployeeHasCertsFor[$key]["ShiftDate"];									
				$SELECTSTATEMENT = "SELECT count(*) FROM Shifts 
				WHERE `CurrentOwnerEmployeeID` = '".$EmployeeID."' AND (
				`date` = '".$date."' 
				AND  `startTime` Between '".$STime."' AND '".$ETime."'	
				OR `date` = '".$date."' 
				AND	`endTime` Between '".$STime."' AND '".$ETime."' 	
				OR `date` = '".$date."' 
				AND	'".$STime."' Between `startTime`  AND `endTime`) ;";
				$query = $CI->db->query(	$SELECTSTATEMENT	);
				$ConflictCount = $query->result_array();				
				if ( $ConflictCount[0]["count(*)"]  == '0') {															
					$ShiftTime = "";
					$ST = $ALL_SubSlipsthisEmployeeHasCertsFor[$key]["startTime"];
					$ET = $ALL_SubSlipsthisEmployeeHasCertsFor[$key]["endTime"];			
					$CST = $this->theschedule->convertTimeToDisplayTime($ST);
					$EST = $this->theschedule->convertTimeToDisplayTime($ET);				
					$ShiftTime .= "".$CST." - ".$EST."";						
					$ALL_SubSlipsthisEmployeeHasCertsFor[$key]["ShiftTime"] = $ShiftTime;//<-- Making a New Key for ShiftTime...
					$ALL_SubSlipsthisEmployeeHasCertsFor[$key]["Conflict"] = false;
				} else {	
					$ALL_SubSlipsthisEmployeeHasCertsFor[$key]["Conflict"] = true;
				}
			}/// End of foreach			
			echo json_encode($ALL_SubSlipsthisEmployeeHasCertsFor);		
		}/*End of function GetAvailableSubSlips*/
	
		
		
		
		/** Function PostToChatBox
			Purpose: Submits post to chat box table.
		*/
		public function PostToChatBox( ) {
			$instructions = 0;
			try {
				$CI =& get_instance(); //TODO: FIX security.
				$ChatMessage = $_REQUEST['ChatMessage'];
				$EmployeeID = $_REQUEST['employeeID'];
				$DateAndTime = date("Y-m-d H:i:s");					
				$data = array ( 					
						'employeeID' => $EmployeeID,
						'Message' => $ChatMessage,
						'CreatedDateAndTime' => $DateAndTime ,			
				);					
				$sql = $CI->db->insert( 'ChatBoxMessages'  , $data );	
				$instructions = 1;
			} catch(Exception $e) {
				$instructions = 0;
				return 'fail:'.$e;
			}			
			return $instructions;
			
		}/*End of Function PostToChatBox*/
		
		
		
		
		
		
		
		
		
		
		
		/** Function ReloadChatBox
			Purpose: Gets all the chat messages and sorts them in order.
		*/
		public function ReloadChatBox( )
		{			
			$CI =& get_instance();			
			$SELECTSTATEMENT = "SELECT * FROM `ChatBoxMessages`;";
			$query = $CI->db->query(	$SELECTSTATEMENT	);
			$list = $query->result_array();		
			foreach( $list as $key => $item) {
				$ID = $list[$key]["employeeID"];							
				$SELECTSTATEMENT = "SELECT * FROM `Employees` WHERE `employeeID` = '".$ID."';";				
				$query = $CI->db->query($SELECTSTATEMENT);
				$el = $query->result_array();			
				$Firstname = $el[0]["Firstname"];
				$Lastname = $el[0]["Lastname"];
				$DisplayName = $Firstname." ".$Lastname;			
				$list[$key]["Name"] = $DisplayName;		
			}			 
			/* Sort by date and time, Newest first.*/
			function date_compare($a, $b) {
				$time1 = strtotime($a['CreatedDateAndTime']);
				$time2 = strtotime($b['CreatedDateAndTime']);
				return $time2 - $time1;
			}   
			usort($list, 'date_compare');			
			echo json_encode($list);						
		}/*End of Function ReloadChatBox*/
		
		
		public function logout()
		{
			$this->userauth->logout();
		}
	
	}
	
	
	
?>