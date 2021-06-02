<?php	if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
	
	class ApproveControl  { 
		function __construct() 
		{
			error_reporting(E_ALL & ~E_NOTICE);		
		}			 
		public function ApproveControl( ) 
		{}	
		
		
		
		public function getAutoAppove()
		{
			$CI =& get_instance();											
			$SELECT_approve_switch = "SELECT `ApproveSwitch` FROM `Approve`;";	
			$query = $CI->db->query($SELECT_approve_switch); 			
			$list = $query->result_array();
			return $list[0]["ApproveSwitch"];
		}
		
		public function offAutoApprove()
		{
			$CI =& get_instance();											
			$UPDATE_approve_switch = "UPDATE `Approve` SET  `ApproveSwitch` =  False ;";	
			$query = $CI->db->query($UPDATE_approve_switch); 
		}
		
		public function onAutoApprove()
		{
			$CI =& get_instance();											
			$UPDATE_approve_switch = "UPDATE `Approve` SET  `ApproveSwitch` =  True ;";	
			$query = $CI->db->query($UPDATE_approve_switch); 
		}

	}
?>