<?php
/*If the currently logged in user doesn't have Supervisor certification they shouldn't be
		allowed to view this page*/
	if ( $_SESSION['Supervisor']	== false)
	{
		$page = base_url() . "index.php?/Home";
		$this->userauth->redirect($page);
	}			
?>
<script type = "text/javascript">
		
		/** ValidateEmployeeForm()
				Purpose: Determine if form is acceptable to be posted to php script.
						 Allow form to be validated before reaching server.
			
				Validation requirements:
					Firstname textbox field:
						* Cannot be empty.
						* Must have letters only.
						* Must start with Capital and followed by lowercase letters.
					
					Lastname textbox field:
						* Cannot be empty.
						* Must have letters only.
						* Must start with Capital and followed by lowercase letters.
					
					Password textbox field:
						* Cannot be empty.
						* Must have letters and/or numbers only.
						* Must be between 6 and 12 characters long.
					
					Certifcation Check box fields:
						* Atleast one certification box must be checked.


			When everything is correct an alert box will appear confirming creation of employee.  Then return true.
			
			
			
		*/
		function ValidateEmployeeForm() {

			/* 	Declare variables and initinalise them. 
				Then logic for field tests. 
				Then determine if pass or fail. 
				Finally return pass or fail.
			*/
			
			
		   
			//Passfail variable used to allow form to post or not.
			var passorfail = false; 
		

				
				
				
			//Create string variables for textboxes.
			var FirstnameField = "";
			var LastnameField = "";
			var passwordField = "";
					
		    //Get text box values			
			FirstnameField += CreateEmployeeForm.Firstname.value;
			LastnameField += CreateEmployeeForm.Lastname.value;
			passwordField += CreateEmployeeForm.password.value;
				
			//Create boolean variables for certs
			var LifeguardField = false;
			var InstructorField = false;
			var HeadguardField = false;
			var SupervisorField = false;

			//Set booleans for each field. When  all fields are correct passorfail gets set to true.
			var FirstnameTest = true;
			var LastnameTest = true;
			var passwordTest = true;
			var certTest = true;
							
			//Set check box booleans 
			LifeguardField = CreateEmployeeForm.Lifeguard.checked;				
			InstructorField = CreateEmployeeForm.Instructor.checked;
			HeadguardField = CreateEmployeeForm.Headguard.checked;
			SupervisorField = CreateEmployeeForm.Supervisor.checked;			
						
			//Empty the contents of the errormessages so new error message can be displayed.
			document.getElementById("FirstnameError").innerHTML = "";
			document.getElementById("LastnameError").innerHTML = "";	
			document.getElementById("passwordError").innerHTML = "";	
			document.getElementById("CertificationError").innerHTML = "";	
			document.getElementById("SuccessORfailbox").innerHTML = "";	

			
			
			
			
			
			
	


			// Begining of Firstname field tests.									
			if ( FirstnameField == ""  )										//Check for emptys 
			{	
				FirstnameTest = false;				
				var ErrorMsg = " Firstname cannot be empty. " ;//FirstnameError msg
				document.getElementById("FirstnameError").innerHTML = ErrorMsg;						
			}else 
			{											
				var PatternLettersOnly = /^[a-zA-Z]+$/;
				var answerFirstname = false;
				answerFirstname = PatternLettersOnly.test(FirstnameField);			
				if ( answerFirstname == false ) 									// Check firstname field for letters only 
				{
					FirstnameTest = false;					
					var ErrorMsg = " Firstname must contain letters only. " ;//FirstnameError msg	
					document.getElementById("FirstnameError").innerHTML = ErrorMsg;																
				}else 
				{
					var pattCapital = /^[A-Z][a-z]+$/;
					var firstletterCapital = false;
					var ansFirstCapital = pattCapital.test( FirstnameField );
					if ( ansFirstCapital == false )                                  //Check first letter capital for first name
					{						
						FirstnameTest = false;														
						var ErrorMsg = "Firstname must start with a uppercase and follow with lowercase." ;//FirstnameError msg
						document.getElementById("FirstnameError").innerHTML = ErrorMsg;							
					}														
				}
			}
			//End of FirtnameField test.

			
			
			
			
			
			
			
			
			
			//Start of Lastname Field test.
			if ( LastnameField == "" )
			{				
				LastnameTest = false;												
				var ErrorMsg = "Lastname cannot be empty. " ; //Lastname Error msg	
				document.getElementById("LastnameError").innerHTML = ErrorMsg;		
			}else 
			{
				var PatternLettersOnly = /^[a-zA-Z]+$/;
				var answerLastname = false;
				answerLastname = PatternLettersOnly.test(LastnameField);			
				if ( answerLastname == false )										//Check LastnameField for letters only 
				{					
					LastnameTest = false;						
					var ErrorMsg = "Lastname must contain letters only. " ;	//Lastname Error msg	
					document.getElementById("LastnameError").innerHTML = ErrorMsg;		
				}else
				{
					var pattCapital = /^[A-Z][a-z]*$/;
					var firstletterCapital = false;
					var ansFirstCapital = pattCapital.test( LastnameField );
					if ( ansFirstCapital == false )                              	//Check for first letter capital of Lastname
					{						
						LastnameTest = false;																									
						var ErrorMsg = "Lastname must start with a uppercase and follow with lowercase characters." ;//Lastname Error msg	
						document.getElementById("LastnameError").innerHTML = ErrorMsg;																			
					}															
				}
			}
			//End of Lastname Field test.

			
			
			
			
			
			
			
			
			
			//Start of password field test.
			if ( passwordField == "" )
			{  
				passwordTest = false;						
				var ErrorMsg = "Password cannot  be empty. " ; //Password Error msg
				document.getElementById("passwordError").innerHTML = ErrorMsg;	
			}else
			{
				var passwordAcceptableCount = false; // Count password length must be between 6 and 12 
				var pCount = passwordField.length				
				if ( pCount < 6  )//Too small
				{					
					passwordTest = false;  										
					var ErrorMsg = "Password must be more that 6 characters.  " ; //Password Error msg	
					document.getElementById("passwordError").innerHTML = ErrorMsg;						
				}else if ( pCount > 12 ) //too big
				{
					passwordTest = false;  
					var ErrorMsg = "Password must be less than 12.   " ; //Password Error msg	
					document.getElementById("passwordError").innerHTML = ErrorMsg;	
				} 
				
				//Check password field for acceptable characters.
				var pattPassword = /^[a-zA-Z0-9]+$/;								//Pattern for all letters and numbers. Should match only strings containing those characters					
				var passAcceptableCharacters = false;								//Assume string is wrong first.
				passAcceptableCharacters = pattPassword.test( passwordField );		//Check if an acceptable password.
				if (  passAcceptableCharacters == false ) 	
				{					
					passwordTest = false;												
					var ErrorMsg = "Password Must contain digits or letters.    " ; //Password Error msg
					document.getElementById("passwordError").innerHTML = ErrorMsg;						
				}
			}	
			//End of password field test.
			
			
			




			
	

			// Start of check box field test. At least one check box must be true.
			if ( LifeguardField == false &&	InstructorField == false && HeadguardField 	== false &&	SupervisorField == false  )				//If the user hasnt given them a certification, then they are not allowed 		
			{				
				certTest = false; 				
				//CertificationError msg	
				var CheckStr = " You must choose a certification. " ;
				document.getElementById("CertificationError").innerHTML = CheckStr;	
			}
			//End of checkbox field test.


			
			
				
			//Start of final vertict. Should the form be allowed to post? only post if all the field test pass.			
			if (certTest == true && passwordTest == true && LastnameTest == true && FirstnameTest == true)	
			{
				passorfail = true;
			}
			//End of final vertict.
			

			//Confirm user wants to create a new user account.
			if ( passorfail == true )
			{
				if (confirm(" Are you sure you wish to create this user acount?  \n Firstname: "+FirstnameField+"\n Lastname: "+LastnameField+"\n Password: "+passwordField+" \n Certifcations \n        Lifeguard: "+LifeguardField+" \n        Instructor: "+InstructorField+"\n        Headguard: "+HeadguardField+"\n        Supervisor: "+SupervisorField+""  ) == true) 	
				{
					passorfail = true;
				
				} else {
					passorfail = false;
				
				}
				
			}
			
			
			
			//Return the boolean for allowing a pass or fail.
			return passorfail;
			
					
		}
		
</script>


<div id= "CreatenewEmployeeBOX" >







	<h1>Create a New Employee </h1>


	<form action = "<?= base_Url(); ?>index.php?/CreateNewEmployee/CreateEmployee" id = "CreateEmployeeForm" name = "CreateEmployeeForm" onsubmit = "return ValidateEmployeeForm();" method = "post" accept-charset = "utf-8" >

		
		
		Firstname <input type = "textbox" name="Firstname"    value = ""      maxlength="21" /> 
		<br><div id = "FirstnameError" > </div><br>
		
		
		
		Lastname <input type = "textbox" name = "Lastname"   value = ""           maxlength="21"  />  
		<br><div id = "LastnameError" > </div><br>
		
		
		
		Password <input type = "textbox"  name= "password"   value = ""             maxlength="20" /> 
		<br><div id = "passwordError" ></div><br>
		
		
		<div id = "CertificationError" ></div>
		<br>
		<div id = "certsDiv"> 
			Lifeguard <input type = "checkbox" name = "Lifeguard" value = "1"                      />  
			<br>
			Instructor <input type = "checkbox" name = "Instructor"   value = "2"             /> 
			<br>
			Headguard <input type = "checkbox" name = "Headguard"      value = "3"                /> 
			<br>
			Supervisor <input type = "checkbox" name = "Supervisor"    value ="4"                 /> 
			<br>
		</div>
		<input type = "submit" value = "Create Employee" id = "CreateEmpButton" />

	</form>




	<div id = "SuccessORfailbox" >
		<?= $msg ?>
	</div>



</div>