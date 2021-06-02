<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Availability { 
	  

    function __construct() 
    {
		error_reporting(E_ALL & ~E_NOTICE);

    }
	
 
	
	
	/** Function GetNewNotifications 
			Purpose: Gets any new notifications and counts them. 
			Used for displaying count of notifications on nav bar.
	*/
	public function GetNewNotifications()
	{	session_start();
		$EID = $_SESSION["EmployeeID"];
		$CI =& get_instance();			
		$query = $CI->db->query("Select Count(employeeID) FROM `Notifications` WHERE `employeeID` = '".$EID."' AND `readOrUnread` = '0';");
		$list = $query->result_array();
		return $list[0]["Count(employeeID)"];		
		//return $EID;
	}
	

}
?>