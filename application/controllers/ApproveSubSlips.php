<?php
	
	/**
		Approve Sub Slips Controller
	*/

	defined('BASEPATH') OR exit('No direct script access allowed');

	class ApproveSubSlips extends CI_Controller {

		/* This manages the Approve SubSlip page.  */

		var $TPL;

		public function __construct()
		{
			parent::__construct();
			
		}

		public function index()
		{											
			$this->template->show('SupervisorPages/ApproveSubSlips_view', $this->TPL);
		}
		
		
		
		
		
		
		
		
		
		/** Function DoSubSlipSwitch
				Purpose: switches the shifts when a subslip is confirmed on approved subslip page.
		
		*/
		public function DoSubSlipSwitch()
		{
			$subslipID = $_REQUEST['subslipID'];
			$ownerID = $_REQUEST['ownerID'];
			$TakerID = $_REQUEST['TakerID'];
			$ShiftID = $_REQUEST['ShiftID'];
			
			$subslipID = (int)$subslipID;
			$ownerID = (int)$ownerID;
			$TakerID = (int)$TakerID;
			$ShiftID = (int)$ShiftID;
			
			
			
			
			//Update shift where shift equal to shiftID, set current owner to TakerID
			//Update subslip Set subslip completed field to true.
			//Notify sublsip creator of shift their shift has been taken.
			//Notify taker employee that they are now responsible for shift.
			$instructions = 1;
			try {
				
					$SELECTSTATEMENT = "SELECT * FROM `Shifts` WHERE `ShiftID` = '".$ShiftID."';";
					
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
				
				
					$SELECTSTATEMENT = "SELECT * FROM `Employees` WHERE `employeeID` = ".$TakerID.";";
					$CI =& get_instance(); //TODO: FIX security.
					$query = $CI->db->query($SELECTSTATEMENT);
					$list = $query->result_array();			
					$TakerFirstname = $list[0]["Firstname"];
				
				
		
				/** Notificcation... 
				 * 2. you subslip was approved
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
				
				/** Notificcation... 
				 * 8. The subSlip you signed was approved. 
				 */				
				$DisplayMessage = "The subslip you signed  with ".$Firstname." for a ".$STRposition." shift on ".$shiftDate." from ".$startTime." to ".$endTime.", Has been APPROVED!";				
				$data = array ( 					
						'employeeID' => $TakerID,
						'type' => 8,
						'message' => $DisplayMessage,
						'readOrUnread' => 0,
						'CreatedDateAndTime' => date("Y-m-d H:i:s"),	
				);					
				$sql = $CI->db->insert( 'Notifications'  , $data );	
								
								 
				
				
				
				
				//Update Shift... 
				$CI =& get_instance();											
				$UPDATE_shift_STATEMENT = "UPDATE `Shifts` SET  `CurrentOwnerEmployeeID` =  '".$TakerID."' WHERE `ShiftID` = '".$ShiftID."' ;";
				
				//Update SubSlip
				$UPDATE_subslip_STATEMENT = "UPDATE `SubSlips` SET  `completed` =  '1' WHERE `subslipID` = '".$subslipID."';";
				
				$both = $UPDATE_shift_STATEMENT." ".$UPDATE_subslip_STATEMENT;
				
				
				$query = $CI->db->query($UPDATE_subslip_STATEMENT); 								
				$query = $CI->db->query($UPDATE_shift_STATEMENT); 
				
				
			}
			catch(Execption $e)
			{
				$instructions = 0;
			}
			
			if( $instructions == 1)
			{	//Notify employees...
				//TODO: when notification system is set up finsih this.				
			}
			echo $instructions;//Reply to ajax front end...
			
		}/* End of function DoSubSlipSwitch */
		
		
		
		
		
		
		
		
		
		
		function checkAutoApprove()
		{
			$onoff = $this->approvecontrol->getAutoAppove();
			echo $onoff;
		}
		
		function offAutoApprove()
		{
			$onoff = $this->approvecontrol->offAutoApprove();			
			echo $onoff;
		}
		
		function onAutoApprove()
		{
			$onoff = $this->approvecontrol->onAutoApprove();			
			echo $onoff;
		}
		
		
		
		
		
		
		
		
		
		
		/** function getAllSubSlips
			purpose run a select statement that retrives all the approviate information 
			about the sub slips that havent been completed and have been taken and are 
			in the future.
		*/
		public function GetALLSubSlips()
		{
			$CI =& get_instance();												
			$theDate = $this->theschedule->YYYMMDDTodayplz();					
			$SELECTSTATEMENT = "SELECT e.Firstname, e.Lastname, s.ShiftID, s.CreatorID, s.CreatorID, s.subslipID, s.TakerID, s.ShiftDate, s.startTime, s.endTime, s.Position, s.Reason, s.TakenTrueorFalse, s.TakenDateAndTime, s.CreatedDateAndTime  FROM `SubSlips` s JOIN `Employees` e ON s.CreatorID = e.employeeID WHERE `TakenTrueorFalse` = '1' AND `completed` = False AND `ShiftDate` > '".$theDate ."' ;";			
			$query = $CI->db->query($SELECTSTATEMENT);
			$list = $query->result_array();
			
			
			//Get the taker name
			
			foreach( $list as $key  => $SubSlip) 
			{
				$takerID = $SubSlip["TakerID"];

				$SELECTSTATEMENT = "SELECT `Firstname`, `Lastname` FROM `Employees` WHERE `employeeID` = ".$takerID.";";							
				$query = $CI->db->query($SELECTSTATEMENT);
				$TakerInfo = $query->result_array();
				$name = $TakerInfo[0]["Firstname"]." ".$TakerInfo[0]["Lastname"];
				$list[$key]["personTakingShift"] = $name;
			}
							
			echo json_encode($list);
		}
		
		
		
		
		
		
		
		
		
		
		/** function RejectSubSlip
			Purpose: deletes subslip and tells the owner that it was deleted and provides them the reason why.
			TODO: saftey check reason.
		*/
		public function RejectSubSlip()
		{
			$subslipID = $_REQUEST['subslipID'];
			$ownerID = $_REQUEST['ownerID'];
			$reason = $_REQUEST['Rejectreason'];
			
			$instructions = 0;
			$DELETESTATEMENT = "";
			//TODO: Tell owner of subslip their subslip got rejeted.
			if( is_numeric($subslipID) == true  && is_numeric($ownerID) == true )
			{		
				try
				{
					
					$SELECTSTATEMENT = "SELECT * FROM `Employees` WHERE `employeeID` = ".$ownerID.";";
					$CI =& get_instance(); //TODO: FIX security.
					$query = $CI->db->query($SELECTSTATEMENT);
					$list = $query->result_array();			
					$Firstname = $list[0]["Firstname"];
				
														
					$SELECTSTATEMENT = "SELECT `startTime`, `endTime`, `ShiftDate`, `Position`, `TakerID` FROM `SubSlips` WHERE `subslipID` = ".$subslipID.";";
					$CI =& get_instance(); //TODO: FIX security.
					$query = $CI->db->query($SELECTSTATEMENT);
					$list = $query->result_array();			
					$startTime = $list[0]["startTime"];
					$endTime = $list[0]["endTime"];
					$ShiftDate = $list[0]["ShiftDate"];
					$Position = $list[0]["Position"];
					$takerID  = $list[0]["TakerID"];
					$STRposition = "";				
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
				
					$SELECTSTATEMENT = "SELECT * FROM `Employees` WHERE `employeeID` = ".$takerID.";";
					$CI =& get_instance(); //TODO: FIX security.
					$query = $CI->db->query($SELECTSTATEMENT);
					$list = $query->result_array();			
					$TakerFirstname = $list[0]["Firstname"];
					
					
					
					
					
					
					/**	Notificcation...				
					 *	3. Your SubSlip was rejected.
					 */					
					$DisplayMessage = "Your subslip with ".$TakerFirstname." for ".$ShiftDate." from ".$startTime." to ".$endTime." as ".$STRposition.", Has been rejected by a supervisor. The reason why was ".$reason." ";				
					$data = array ( 					
							'employeeID' => $ownerID,
							'type' => 3,
							'message' => $DisplayMessage,
							'readOrUnread' => 0,
							'CreatedDateAndTime' => date("Y-m-d H:i:s"),				
					);					
					$sql = $CI->db->insert( 'Notifications'  , $data );	
					
					
					/**	Notificcation...				
					 *	9.. the subslip you took was rejected.
					 */					
					$DisplayMessage = "The subslip you signed  with ".$Firstname." for a ".$STRposition." shift on ".$ShiftDate." from ".$startTime." to ".$endTime.", Has been rejected by a supervisor. The reason why was ".$reason." ";				
					$data = array ( 					
							'employeeID' => $takerID,
							'type' => 9,
							'message' => $DisplayMessage,
							'readOrUnread' => 0,
							'CreatedDateAndTime' => date("Y-m-d H:i:s"),			
					);					
					$sql = $CI->db->insert( 'Notifications'  , $data );	
					
					
					
					
					
					
					
					$CI =& get_instance();												
					$DELETESTATEMENT = "DELETE FROM `SubSlips` WHERE `subslipID` = '".$subslipID."' ;";
					$query = $CI->db->query($DELETESTATEMENT);
					$instructions = 1;
					
					
				}
				catch(Exception $e)
				{ 
					//TODO: Write to error log.. Shouldnt happen.
					$instructions = 0;
				}				
			}
			else
			{
				//TODO: Write to error log.. Shouldnt happen.
				$instructions = 0;
			}
									
			echo json_encode($instructions);//echo json_encode($instructions);
		}		
	}
?>