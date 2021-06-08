<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class MyProfile extends CI_Controller {

	var $TPL;

	public function __construct() {
		parent::__construct();
		$this->load->helper('form');
	}

	public function index() {
		$this->TPL['ProfilePicUploadmsg'] ='';
		$this->TPL['Notifications'] = $this->GetNotifications();
		$this->TPL['Availability'] = $this->GetAvailability();
		$this->template->show('myprofile_view', $this->TPL);
	}

  
	/** function ProfilePicUpload()		
		purpose: This function handles the upload of a profile picture.
				 This function will validate the file and place the file in the correct directory.
				 Prints a success or fail message to the ProfileErrorBox.		
		variables:
			$file, The actual file that was give to the user.
			$ID, the ID of the user who is logged in, and uploading the file.					
	*/
	public function ProfilePicUpload( ) {
		$this->TPL['ProfilePicUploadmsg'] ='';
		$this->TPL['Notifications'] = $this->GetNotifications();
		$this->TPL['Availability'] = $this->GetAvailability();
		// Get form data. 
		$ID = $this->input->post("ID"); //The ID given by the form
		$file = $this->input->post("img"); //The img file given by the form
		$target_directory = "application/assets/img/UserProfilePics/";	  
		//Validate the form data.
		$ValationPassOrFail = false;  //Assume data is inncorect. 
		//Confirm ID's match
		$IDmatch = false; //assume id do not match untill confirmed			
		//Test 1: Compare ID with Session ID. 
		$Session_ID = $this->userauth->passMeID();			
		if ( $Session_ID == $ID   ) {
			//Place the $ID on the target_directory path
			$target_directory .= "".$ID."";
			$IDmatch = true;
		} else {
			$IDmatch = false;
			//TODO: Write to error log.
			$ErrorMsg = "ID does not match. ";
		}			
		//Test 2: confirm file is a jpg img.
		/*
			Using code igniter because it appears to be the best way to do this...
			Not sure how it works tho...
		*/
		//Set prefrences for upload config
		$config['upload_path'] = $target_directory; 	//uploads the profile pic to the user's profile pic.
		$config['allowed_types'] = 'gif|jpg|png'; 		//Accepted img types
		$config['max_size'] = 2048;					//Maxium size in kilobytes kb. Currently Set to 2MB
		$config['max_width'] = 0;				  	//Maxium width a image can have. 0 is no limit.
		$config['max_height'] = 0;					//Maxium Height a image can have. 0 is no limit.
		$filename = $ID.'_profilepic.jpg';						// changed profilepic name to id_profile pic to have all profile pic identify with key.
		$config['file_name'] = $filename ;			//names the img 1_profilepic.jpg 
		$config['overwrite'] = true;					//Overwrites the old profilepic.jpg		
		$this->load->library('upload', $config); //Load library				
		// If the upload of the file in the img field returns false do this... else do this...
		if ( ! $this->upload->do_upload('img')) {
			//The image upload was unsuccessful, display the errors again.
			$arrr = array('error' => $this->upload->display_errors());
			$this->TPL['ProfilePicUploadmsg'] = $arrr['error'];
			$this->template->show('myprofile_view', $this->TPL);	
		} else {
			//The image upload was appently successful, 
			//Need to resize image.
			$upload_data = $this->upload->data();
			//resize:
			$config['image_library'] = 'gd2'; 						//default
			$config['source_image'] = $upload_data['full_path'];	//path of the img just uploaded.
			$config['maintain_ratio'] = TRUE;						//maintains ratio of image
			$config['create_thumb'] = TRUE;
			$config['width']     = 170;			
			$config['height']   = 170;
			$this->load->library('image_lib', $config);	
			if ( ! $this->image_lib->resize()	) {
				//// Resize was unsuccessful.
				$arrr = array('error' => $this->image_lib->display_errors());
				$this->TPL['ProfilePicUploadmsg'] = $arrr['error'];
				$this->template->show('myprofile_view', $this->TPL);		
			} else {
				//// Resize was successful.			
				$this->TPL['ProfilePicUploadmsg'] = "Upload was successful";				
				$this->template->show('myprofile_view', $this->TPL);						
				/*$page = base_url() . "index.php?/MyProfile";
				$this->redirect($page);*/
			}
		}
	}//End of function ProfilePicUpload();
	
	
	
	
	/** function redirect()
		purpose: allows page to be re directed to myprofile page, with andy variables.
	*/
    public function redirect($page) 
    {
        header("Location: ".$page);
        exit();
    }
    
	
	
	/*
		Problems are...
		no how to upload profile picture yet.
		no success message returned when successful... yet
		need to size image.
		
	*/
  
		/** Function GetAvailability
			Purpose: 
		*/
		public function GetAvailability( )
		{
			session_start();		
			$ID = $_SESSION['EmployeeID'];

			$CI =& get_instance(); //TODO: FIX security.
			$query = $CI->db->query("SELECT Availability FROM `Employees` WHERE  employeeID =".$ID.";");
			$list = $query->result_array();			
			$Availability = $list[0]['Availability'];
			if(json_decode($Availability) == null )
			{
				$Availability = json_encode($Availability);
			}
			
			return $Availability;
		}/*End of Function GetAvailability*/
		
		
		
		
		/** Function GetNotifications
			Purpose: Gets all the notifications for the currently logged in employee, and changes 
			         all there unread status to read.
		*/
		public function GetNotifications( )
		{
		
			session_start();		
			$ID = $_SESSION['EmployeeID'];
			/** Types of  Notificcation messages.
					 *	1. SubSlip was taken.
					 *	2. SubSlip was approved.
					 *	3. SubSlip was rejected.
					 *	4. You were removed from a shift by a supervisor.
					 *	5. You were assigned a shift by a supervisor. 
				     *	6. You submitted a subslip.
					 * 	7. You took someones SubSlip.
					 *  8. The subSlip you took was approved.
					 *  9. The Subslip you took was rejected.
					 */
					
			$CI =& get_instance(); //TODO: FIX security.
			$query = $CI->db->query("SELECT * FROM `Notifications` WHERE  employeeID =".$ID.";");
			$list = $query->result_array();			
						
			/* Sort by date and time, Newest first.*/
			function date_compare($a, $b)	
			{
				$time1 = strtotime($a['CreatedDateAndTime']);
				$time2 = strtotime($b['CreatedDateAndTime']);
				return $time2 - $time1;
			}   
			usort($list, 'date_compare');
			
			//$query = $CI->db->query("UPDATE `Notifications` SET `readOrUnread`= '1' WHERE `employeeID` = ".$ID.";");						
			
			/*Now delete the oldest notifications if the size is greater than 50*/
			if(  sizeof($list) > 50 )
			{
				$lstSize = sizeof($list);
				$Subtrack = $lstSize - 50;	

				/*Start at the 30th oldest notification and delete all after that.*/
				for( $i = 30; $i < $lstSize; $i++ )
				{
					$Nid = $list[$i]["notificationID"];
					$query = $CI->db->query("DELETE FROM `Notifications` WHERE notificationID = ".$Nid.";");
				}
			}
			
			
			
			return json_encode($list, JSON_HEX_APOS); // JSON_HEX_APOS fixes single quotes problem			
			 
		}/*End of Function GetNotifications*/
		

	public function updateNotificationRead() {
		$EmployeeID =$_REQUEST['EmployeeID'];
		$notificationID = $_REQUEST['notificationID'];		
		 
		$e =  is_numeric ($EmployeeID);
		$n =  is_numeric ($notificationID);
		if (  $n == true && $e == true) {
			$CI =& get_instance();  
			$UPDATESTATEMENT = "UPDATE `Notifications` SET `readOrUnread`= '1' WHERE `employeeID` = '".$EmployeeID."' AND `notificationID` = '".$notificationID."' ;";
			$query = $CI->db->query($UPDATESTATEMENT); 
			echo  "Success";  		
		}
		
	}
}

?>

