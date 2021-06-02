<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Accountcreation {
    
	
	
	/*Final pass or fail boolean*/
	var $passORfail;
		
		
		
	
	/** Accountcreation TODO:
		- find out how to delete folders that get created by this script in userprofilepics.
			currently trying to develop delete employee which should do this.
		- Re work the logic for when the employeeID gets set for profile pic creation.
			currrently it chooses the highest employeeID and adds one, this causes problems because delete employee will leave spaces in employeeIDs
		-Should be re structured into smaller components and re commented.
	
	
	*/
    function __construct() 
    {
		error_reporting(E_ALL & ~E_NOTICE);
		
    }

	
	
	
	
	/** create()
		purpose: Starts the creation of a new account on the server side.
		
		account creation steps...
			Step 1: Validate
					1.1: Confirm Javascript validations were correct.					
					1.2: Check php side validations.
					1.3: Have form memory set. Done.
					If validation fails stop account creation and report error message.
					
			Step 2: Create Username. ( Username = firstname.lastname )
			
			Step 3: Search db for matching and account with matching Username, or matching, Firstname and lastname. 
						If there is already an account with these names then report a error message and stop account creation.
			            If there is not matching account aready proceed with step 4.
			Step 4: Try to create the employee account in the employee table.
					When account gets successfuly created a succes message is displayed.
					When  an error occurs for reasons that are not the users's falut but the system display error message and write to error log for Accountcreation. (Implement this feature later after development.)
		
	*/
	public function create($Firstname, $Lastname, $password, $Lifeguard, $Instructor, $Headguard, $Supervisor ) 
    {
		//initinalise boolean vales to false.
		$BoolLifeguard  =  false;
		$BoolInstructor =  false;
		$BoolHeadguard  =  false;
		$BoolSupervisor =  false;
			
		//Determine which checkboxes were checked. true is checked. false is not checked.
		try {	
			$BoolLifeguard  =  (is_null( $Lifeguard )) ?  false : true ;
			$BoolInstructor =  (is_null( $Instructor )) ?  false : true ;
			$BoolHeadguard  =  (is_null( $Headguard )) ?  false : true ;
			$BoolSupervisor =  (is_null( $Supervisor )) ?  false : true ;
			
		}catch  (Exception $e) {
			return "There was a problem converting the checkboxes to booleans."; 
		}						
		
				
		//Validate.		(JS validation is probabbly complete.. )
		$this->JSanswer = $this->ValidateJS($Firstname, $Lastname, $password, $BoolLifeguard, $BoolInstructor, $BoolHeadguard, $BoolSupervisor  );
		if ($this->JSanswer  == false)
		{	
			$this -> PHPanswer = $this->ValidatePHPSIDE($Firstname, $Lastname, $password, $Lifeguard, $Instructor, $Headguard, $Supervisor );
			if ( $this -> PHPanswer == false )
			{								
				/* VALIDATION COMPLETE TRY TO ADD NEW ACCOUNT. */

				//Get the codeignter instance because we will be accessing the database
				$CI =& get_instance(); 
		
				/* STEP 1  Prep the Data */		
				//Determine LastemployeeID then add one and assign it to this employeeID.								
				$query = $CI->db->query("SELECT max( employeeID ) AS LastID FROM `Employees`   ");
				$row = $query->row();
				$this->employeeID  = $row -> LastID;   			
				$this ->employeeID = $this->employeeID + 1;   												
				//The employeeID has been incremented and is ready to be inserted.
						
				//Create the username by Attaching  the firstname and lastname to create the username.
				$this->Username = "".$Firstname.".".$Lastname."";

				/*Convert the certs to a basic 1 or 0 true or false booleans.*/
				if (  $Lifeguard == null )
				{	$Lifeguard = 0;	}
				else
				{	$Lifeguard = 1;	}
				
				if (  $Instructor == null )
				{	$Instructor = 0;	}
				else
				{	$Instructor = 1;	}
				
				if ( $Headguard == null )
				{	$Headguard = 0;	}
				else 
				{	$Headguard = 1;	}
				
				if ( $Supervisor == null )
				{	$Supervisor = 0;	}
				else
				{	$Supervisor = 1;	}
				/*The Certs have been converted to booleans that can be used in mysql */
				
				// Create the data array to be inserted. 
				$data = array ( 	
						'employeeID' => $this-> employeeID,
						'Firstname' => $Firstname,
						'Lastname' => $Lastname,
						'Username' => $this->Username,
						'Password' => $password,
						'Lifeguard' =>  $Lifeguard,						
						'Instructor' => $Instructor,
						'Headguard' => $Headguard,
						'Supervisor' => $Supervisor,
						'Availability' => 'Nothing yet'
				);
		
				//Now Insert the employee into the database.
				$sql = $CI->db->insert( 'Employees'  , $data );		
				
				/* Now create a new directory in UserProfilePics using this employeeID and then choose a random default profile pic. */
				//Make the directory
				$path = "application/assets/img/UserProfilePics/".$this-> employeeID."";
			
				//if the directory does not exists create it
				if( !file_exists($path))
				{
					mkdir($path , 0777); //Create a directory for this employee
				}		

				//Now pic a random picture and put it in that folder.
				$Number = rand( 1, 17);  //random number from 1 to 17.
				$defalutpic = "application/assets/img/defaultProfilePictures/D".$Number.".jpg";	
				$path .= "/".$this->employeeID."_profilepic.jpg"; 								//adds file name to path
				
				//Copy default pic to new directory.
				if(copy(  $defalutpic  , $path  ))
				{											
					/* Display success message.*/
					/*Change boolean values to string values..*/				
					$this->ConvertedLifeguard  =  ($Lifeguard) ?  'true' : 'false' ;
					$this->ConvertedInstructor =  ($Instructor)  ? 'true' : 'false';
					$this->ConvertedHeadguard  =  ($Headguard)  ? 'true' : 'false';
					$this->CovertedSupervisor  =  ($Supervisor) ? 'true' : 'false';								
					
					$this->passORfail; //This passORfail variable will hold the return message for confiming the employee was created.
					$this->passORfail = "<h3>The Employee was created. </h3><p> Firstname:".$Firstname."     <br> Lastname: ".$Lastname."   <br> Password: ".$password."   <br>  Lifeguard:". $this->ConvertedLifeguard ." <br>  Instructor:". $this->ConvertedInstructor."   <br>  Headguard:".$this -> ConvertedHeadguard."  <br> Supervisor:".$this->CovertedSupervisor."   </p>";
										
						

					//// Creation of thumb 
					$newpath ="application/assets/img/UserProfilePics/".$this-> employeeID."/".$this->employeeID."_profilepic.jpg";
					//resize:
					$config['image_library'] = 'gd2'; 						//default
					$config['source_image'] = $path;						//path of the img just uploaded.
					$config['maintain_ratio'] = TRUE;						//maintains ratio of image
					$config['create_thumb'] = TRUE;
					$config['width']     = 170;			
					$config['height']   = 170;
					$config['new_image'] = $newpath; 
					$ci=&get_instance();
					$ci->load->library('image_lib', $config);

					$ci->image_lib->initialize($config);
					if ( ! $ci->image_lib->resize());
					{
							echo $ci->image_lib->display_errors();
					}
										
										
										

				}else
				{	$this->passORfail = "Copy failed";	}
					
				//Return the madeit message.	
				return $this-> passORfail;

			}
			else
			{	//The php side validation failed.
				return $this -> PHPanswer;
			}		
	
		}
		else
		{	// JS validation failed... Almost impossible conition unless, JS some how gets compromised.
			return $this->JSanswer;
		}
		
	}// End of function create.
	
	
	
	
	
	
	
	
	/** function ValidateJS
		Purpose: This funcion is to revalidate the javascript validation. Typically this wont get triggered.
	
	
	*/
	public function ValidateJS(  $Firstname, $Lastname, $password, $BoolLifeguard, $BoolInstructor, $BoolHeadguard, $BoolSupervisor  )
	{
		
		/*Final pass or fail boolean*/
		//$this->passORfail;
		
		
		/*Each field boolean */
		$this->passwordTest = true;
		$this->CheckBoxes = true;
		$this->LastnameTest = true;
		$this->FirstnameTest = true;
		
		/*Error message builds message to be displayed*/
		$this->ErrorMsg = "The user was not created for these reasons: ";
		
				
		
		//Start of Firstname
		if ( $Firstname == ""  )										//Check for emptys 
		{	
			$this->FirstnameTest = false;				
			$this->ErrorMsg .= " <br> &#x25CF; Firstname cannot be empty " ;//FirstnameError msg
			
		}else 
		{											
			$this->PatternLettersOnly = '/^[a-zA-Z]+$/';
			$this->answerFirstname = false;
			
			$this->answerFirstname = preg_match($this->PatternLettersOnly, $Firstname);			
			
			if ( $this->answerFirstname == false ) 									// Check firstname field for letters only 
			{
				$this->FirstnameTest = false;					
				$this->ErrorMsg .= "<br> &#x25CF; Firstname must contain letters only. " ;//FirstnameError msg	
				
			}else 
			{
				$this-> pattCapital = '/^[A-Z][a-z]+$/';
				$this-> firstletterCapital = false;
				
				$this->ansFirstCapital = preg_match($this->pattCapital, $Firstname );
				
				if ( $this->ansFirstCapital == false )                                  //Check first letter capital for first name
				{						
					$this->FirstnameTest = false;														
					$this->ErrorMsg .= "<br> &#x25CF; Firstname must start with a uppercase and follow with lowercase letters." ;//FirstnameError msg
					
				}														
			}
		}
		//End of Firstname test
		
		
		
		
		
		
		
		
		
		
		
		//Start of Lastname Field test.
		if ( $Lastname == "" )
		{				
			$this-> LastnameTest = false;												
			$this-> ErrorMsg .= "<br> &#x25CF; Lastname cannot be empty. " ; //Lastname Error msg	
			
		}else 
		{
			$this-> PatternLettersOnly = '/^[a-zA-Z]+$/';
			$this-> answerLastname = false;
			$this-> answerLastname = preg_match( $this-> PatternLettersOnly, $Lastname );			
			
			if ( $this-> answerLastname == false )										//Check LastnameField for letters only 
			{					
				$this-> LastnameTest = false;						
				$this-> ErrorMsg .= "<br> &#x25CF; Lastname must contain letters only. " ;	//Lastname Error msg	
				
			}else
			{
				$this-> pattCapital = '/^[A-Z][a-z]*$/';
				$this-> firstletterCapital = false;
				$this-> ansFirstCapital = preg_match( $this-> pattCapital , $Lastname );
				
				if ( $this-> ansFirstCapital == false )                              	//Check for first letter capital of Lastname
				{						
					$this-> LastnameTest = false;																									
					$this-> ErrorMsg .= "<br> &#x25CF; Lastname must start with a uppercase and follow with lowercase letters. " ;//Lastname Error msg	
																							
				}															
			}
		}
		//End of Lastname Field test.
		
		
		//password Test
		if ( $password == "" )
		{  
			$this->passwordTest = false;						
			$this->ErrorMsg .= "<br> &#x25CF; Password cannot  be empty " ; //Password Error msg							
		}
		else
		{
			$this->passwordAcceptableCount = false; // Count password length must be between 6 and 12 
			$this->pCount = strlen($password);				
			if ( $this->pCount < 6  )//Too small
			{					
				$this->passwordTest = false;  										
				$this->ErrorMsg .= "<br> &#x25CF; Password must be more that 6 characters  " ; //Password Error msg	
								
			}else if ( $this->pCount > 12 ) //too big
			{
				$this->passwordTest = false;  
				$this->ErrorMsg .= "<br> &#x25CF; Password must be less than 12.   " ; //Password Error msg	
								
			} 
			
			//Check password field for acceptable characters.
			$this->pattPassword = '/^[a-zA-Z0-9]+$/';								//Pattern for all letters and numbers. Should match only strings containing those characters					
			$this->passAcceptableCharacters = false;								//Assume string is wrong first.
			$this->passAcceptableCharacters  = preg_match($this->pattPassword, $password) ; 		//Check if an acceptable password.
			if (  $this->passAcceptableCharacters == false ) 	
			{					
				$this->passwordTest = false;												
				$this->ErrorMsg .= "<br> &#x25CF; Password Must contain digits or letters.    " ; //Password Error msg
								
			}
		}//End of password test.
		
		
		//Determine if all check boxes are false meaning none of the certs were checked.		
		if (  $BoolLifeguard == false &&  $BoolInstructor  == false && $BoolHeadguard  == false && $BoolSupervisor  == false  )
		{
			$this->ErrorMsg .= "<br> &#x25CF; You must choose a certification.";
			$this->CheckBoxes = false;
		}
		else
		{
			$this->CheckBoxes = true;
		}
		//End of cert check boxs test.
		
		/* Determine pass or fail by field test booleans */
		if ($this->CheckBoxes == true && $this->passwordTest == true && $this->LastnameTest == true && $this->FirstnameTest == true)	
		{
			$this->passORfail = "";
		}
		else
		{
			$this->passORfail = $this->ErrorMsg;
		}	
		
		//Return the vertict.
		return $this->passORfail;						
	
		
	}//End of function validateJS
	
	
	
	
	/** ValidatePHPSIDE
		The purpose of this function is to validate the input form in ways that are not safe to do with JS, ( ex look up db for matches )
		Test: look for matching first and lastname.
	*/
	public function ValidatePHPSIDE( $Firstname, $Lastname, $password, $Lifeguard, $Instructor, $Headguard, $Supervisor  ) 
	{
		//$this->passORfail; //Declare the passORfail boolean variable.
		$CI =& get_instance(); //Declare the Codeigniter instance. 	
		
		//Create Username as Firstname.Lastname.
		$Username = "".$Firstname.".".$Lastname."";
					
		//Search db for a matching username.
		$query = $CI->db->query("Select Username FROM `Employees` WHERE `Username` = '".$Username."';");
		$row = $query->row();
		
		if($query->row() == null ) {
			$this->passORfail = false; // Let account be created.
		} 
		else
		{
			$Username = $row->Username;	
			//If account cannot be found then...
			if ( $row->Username == null    )
			{	//Set passORfail to false.
				$this->passORfail = false; // Let account be created.
			}
			else
			{	//Set the passORfail variable to a string message (Equivilant to true.).
				$this->passORfail = "The Username: '".$Username."' is already in the database.<br> The account was not created.";
			}
			
		}
		
		
		return $this->passORfail;
		
	}// End of ValidatePHPSIDE function.
	
	
	
	
	
	
}// End of Account Creation libary.





 ?>