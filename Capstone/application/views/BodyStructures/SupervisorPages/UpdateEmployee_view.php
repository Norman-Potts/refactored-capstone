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

	
	
	$(document).ready(function() {	
		//On load JS						
		ReloadTableEmployeelist(); // Load the table with all employees in Database.
		var EmployeeList;//The global employee list.
			/*Apply css to employee cells in table
			$(".UpdateEmployeeCell").addClass("notHoverTable");		*/
		
		
		/*When things get changed on document.			*/	
		
			
		$('#UpdateEmployeeTableinner').on('mouseenter', '.UpdateEmployeeCell', function(){																			
				$( this ).removeClass("notHoverTable");
				$( this ).addClass("liOVERTable");	
			}).on('mouseleave', '.UpdateEmployeeCell', function() {
				$( this ).removeClass("liOVERTable");
				$( this ).addClass("notHoverTable");				
			});										
		
			/* When a Employee row gets clicked gets clicked */						
			$('#UpdateEmployeeTableinner').on('click', '.UpdateEmployeeCell', function(){															
				var ID = $(this).find(".upEmCell:first").text(); //Get the ID of this row.
				var idnum = parseInt(ID);								
				loadForm(	idnum	);//load form with the employee with this ID.				
			});/*End of on click of employee row.*/
			
			
			/* When Update button gets clicked */
			$('#btn_UpdateEmployee').click(function(){
				validateForm();
			});		
		/*End of when things get changed on document.*/
		
		
		
		
		
		
		
		
		
		
		
		
	
		/** function GetEmployeelist
			purpose: gets employee list threw an ajaxs call.		
		*/
		function ReloadTableEmployeelist()
		{
			var employeelist;
			var inputarr =[];
			$.post('<?= base_Url(); ?>index.php/UpdateEmployee/loadList', inputarr, function(data)
			{					
				var employees = JSON.parse( data);
				loadTable( employees);								
			});
		}/*End of function ReloadTableEmployeelist*/
		
		
		
		
		
		
		
		
		
		/** function loadTable
			purpose: loads table with all employee information in employeelist
			
			parameter: employeelist	 the array list of employee information. 
		*/
		function loadTable(	 employeelist	)
		{
			var MSG = "";
			EmployeeList = employeelist;
									
			for( var emp in  employeelist 	)
			{
				var id = employeelist[emp]["employeeID"];
				var firstname = employeelist[emp]["Firstname"];
				var lastname = employeelist[emp]["Lastname"];
				var username = employeelist[emp]["Username"];
				var Password = employeelist[emp]["Password"];
				var lifeguard =	"";
					if (  employeelist[emp]["Lifeguard"] == true ){ lifeguard = "Yes"; }else{ lifeguard = "no"; }
				var instructor = "";
					if (  employeelist[emp]["Instructor"] == true ){ instructor = "Yes"; }else{ instructor = "no"; }
				var headguard = "";		 
					if (  employeelist[emp]["Headguard"] == true ){ headguard = "Yes"; }else{ headguard = "no"; }
				var supervisor = "";		 
					if (  employeelist[emp]["Supervisor"] == true ){ supervisor = "Yes"; }else{ supervisor = "no"; }
				
				
				MSG += "<tr class = \"UpdateEmployeeCell notHoverTable\">";
					MSG += "<td class =\"upEmCell\"> "+id+"   </td>";
					MSG += "<td class =\"upEmCell\"> "+firstname+"   </td>";
					MSG += "<td class =\"upEmCell\"> "+lastname+"  </td>";
					MSG += "<td class =\"upEmCell\"> "+username+"   </td>";
					MSG += "<td class =\"upEmCell\"> "+Password+"    </td>";
					MSG += "<td class =\"upEmCell\"> "+lifeguard+"  </td>";
					MSG += "<td class =\"upEmCell\"> "+instructor+"   </td>";
					MSG += "<td class =\"upEmCell\"> "+headguard+" 	 </td>";
					MSG += "<td class =\"upEmCell\"> "+supervisor+"   </td>";	
				MSG += "</tr>";
			}//End of for each employee in employeelist
					
			//Put the rows into tbody of the table.			
			$('#UpdateEmployeeTableinner').empty();
			$('#UpdateEmployeeTableinner').append( MSG );
			
		}/* End of Function loadTable */
		
		

		
		
		
		
		
		/** function loadForm
			purpose: loads form with ID
		
			parameters: ID	
		*/
		function loadForm( ID )
		{								
			var firstname = "";
			var lastname = "";			
			var username = "";
			var password = "";
			var lifeguard = "";
			var instructor = "";
			var headguard = "";
			var supervisor = "";
			
			for( var i = 0; i < EmployeeList.length ; i++)
			{				
				if ( EmployeeList[i]["employeeID"] == ID  ) 
				{				
					firstname = EmployeeList[i]["Firstname"];
					lastname = EmployeeList[i]["Lastname"];
					username = EmployeeList[i]["Username"];
					password = EmployeeList[i]["Password"];
					lifeguard = EmployeeList[i]["Lifeguard"];
					instructor = EmployeeList[i]["Instructor"];
					headguard = EmployeeList[i]["Headguard"];
					supervisor = EmployeeList[i]["Supervisor"];										
				}
				else
				{}						
			}
			
			$('#EmployeeID').val( 	ID );
			$('#Firstname').val( 	firstname );
			$('#Lastname').val( 	lastname );
			$('#password').val(		password );
			
			if( lifeguard == true )
			{	$('#Lifeguard').prop('checked',true);			}
			else
			{	$('#Lifeguard').prop('checked', false);			}
			
			if( instructor == true )
			{	$('#Instructor').prop('checked',true);			}
			else
			{	$('#Instructor').prop('checked', false);		}
			
			if( headguard == true )
			{	$('#Headguard').prop('checked', true);			}
			else
			{	$('#Headguard').prop('checked', false);			}	
			
			if( supervisor == true)
			{	$('#Supervisor').prop('checked', true);			}
			else
			{	$('#Supervisor').prop('checked', false);		}
		
		}//End of function loadForm			
		
		
		
		
		
		
		
		
		
		/*	function validateForm
			purpose: Checks forms for buisness rules and logic problems. 
			If form is okay it will call sendForm other wise it will report errors.
						
		*/
		function validateForm()
		{
			//Passfail variable used to allow form to post or not.
			var passorfail = false; 							
			var ID = UpdateForm.EmployeeID.value;
			var ErrorMsg = "";
			var IDEmpty = false;
											
			//Create string variables for textboxes.
			var FirstnameField = "";
			var LastnameField = "";
			var passwordField = "";
					
			//Get text box values			
			FirstnameField += UpdateForm.Firstname.value;
			LastnameField += UpdateForm.Lastname.value;
			passwordField += UpdateForm.password.value;
				
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
			LifeguardField = UpdateForm.Lifeguard.checked;				
			InstructorField = UpdateForm.Instructor.checked;
			HeadguardField = UpdateForm.Headguard.checked;
			SupervisorField = UpdateForm.Supervisor.checked;			

			
			if( ID == "" )
			{
				//Form hast to be empty. skip everything else and say you must select someone on the table.
				IDEmpty = true;
				ErrorMsg += "You must select an employee to update from the table first.";
			}else{																
				// Begining of Firstname field tests.									
				if ( FirstnameField == ""  )										//Check for emptys 
				{	
					FirstnameTest = false;				
					ErrorMsg += " Firstname cannot be empty <br>" ;//FirstnameError msg
					
				}else{											
					var PatternLettersOnly = /^[a-zA-Z]+$/;
					var answerFirstname = false;
					answerFirstname = PatternLettersOnly.test(FirstnameField);			
					if ( answerFirstname == false ) 									// Check firstname field for letters only 
					{
						FirstnameTest = false;					
						ErrorMsg += " Firstname must contain letters only. <br>" ;//FirstnameError msg							
					}else{
						var pattCapital = /^[A-Z][a-z]+$/;
						var firstletterCapital = false;
						var ansFirstCapital = pattCapital.test( FirstnameField );
						if ( ansFirstCapital == false )                                  //Check first letter capital for first name
						{						
							FirstnameTest = false;														
							ErrorMsg += "Firstname must start with a uppercase and follow with lowercase.<br>" ;//FirstnameError msg				
						}														
					}
				}//End of FirtnameField test.

				
				//Start of Lastname Field test.
				if ( LastnameField == "" )
				{				
					LastnameTest = false;												
					ErrorMsg += "Lastname cannot be empty. <br>" ; //Lastname Error msg					
				}else 
				{
					var PatternLettersOnly = /^[a-zA-Z]+$/;
					var answerLastname = false;
					answerLastname = PatternLettersOnly.test(LastnameField);			
					if ( answerLastname == false )										//Check LastnameField for letters only 
					{					
						LastnameTest = false;						
						ErrorMsg += "Lastname must contain letters only. <br>" ;	//Lastname Error msg	
						
					}else
					{
						var pattCapital = /^[A-Z][a-z]*$/;
						var firstletterCapital = false;
						var ansFirstCapital = pattCapital.test( LastnameField );
						if ( ansFirstCapital == false )                              	//Check for first letter capital of Lastname
						{						
							LastnameTest = false;																									
							ErrorMsg += "Lastname must start with a uppercase and follow with lowercase <br>" ;//Lastname Error msg	
							
						}															
					}
				}//End of Lastname Field test.

				
				
				
				
				//Start of password field test.
				if ( passwordField == "" )
				{  
					passwordTest = false;						
					ErrorMsg += "Password cannot  be empty <br>" ; //Password Error msg
					
				}else
				{
					var passwordAcceptableCount = false; // Count password length must be between 6 and 12 
					var pCount = passwordField.length				
					if ( pCount < 6  )//Too small
					{					
						passwordTest = false;  										
						ErrorMsg += "Password must be more that 6 characters <br>"; //Password Error msg	
						
					}else if ( pCount > 12 ) //too big
					{
						passwordTest = false;  
						ErrorMsg += "Password must be less than or equal to 12.<br>" ; //Password Error msg	
						
					} 
					
					//Check password field for acceptable characters.
					var pattPassword = /^[a-zA-Z0-9]+$/;								//Pattern for all letters and numbers. Should match only strings containing those characters					
					var passAcceptableCharacters = false;								//Assume string is wrong first.
					passAcceptableCharacters = pattPassword.test( passwordField );		//Check if an acceptable password.
					if (  passAcceptableCharacters == false ) 	
					{					
						passwordTest = false;												
						ErrorMsg += "Password Must contain digits or letters.<br>" ; //Password Error msg					
					}
				}//End of password field test.

				
							
				
				// Start of check box field test. At least one check box must be true.
				if ( LifeguardField == false &&	InstructorField == false && HeadguardField 	== false &&	SupervisorField == false  )				//If the user hasnt given them a certification, then they are not allowed 		
				{				
					certTest = false; 				
					//CertificationError msg	
					ErrorMsg += " You must choose a certification. <br>" ;
					
				}//End of checkbox field test.

			}//End of if id is empty test.

			
			//Start of final vertict. Should the form be allowed to post? only post if all the field test pass.			
			if (certTest == true && passwordTest == true && LastnameTest == true && FirstnameTest == true && IDEmpty == false )	
			{
				passorfail = true;
			}
			else
			{
				passorfail = false;
				$('#UpdateReturnError p').empty();
				$('#UpdateReturnError p').append(ErrorMsg);				
				
			}//End of final vertict.
			

			//Confirm user wants to create a new user account.
			if ( passorfail == true )
			{
				if (confirm(" Are you sure you wish to Update this user acount?  \n Firstname: "+FirstnameField+"\n Lastname: "+LastnameField+"\n Password: "+passwordField+" \n Certifcations \n        Lifeguard: "+LifeguardField+" \n        Instructor: "+InstructorField+"\n        Headguard: "+HeadguardField+"\n        Supervisor: "+SupervisorField+""  ) == true) 	
				{	
					sendForm(FirstnameField, LastnameField, passwordField, LifeguardField, InstructorField, HeadguardField, SupervisorField, ID );
				}
				
			}//End of confirm.
			
		}//End of validateForm.

		
		
		
		
		
		
		
		
		





		
		/*	function sendForm()
			purpose: packages up form and sends to server. Waits for servers instructions of what to do next.
		*/
		function sendForm(FirstnameField, LastnameField, PasswordField, LifeguardField, InstructorField, HeadguardField, SupervisorField, ID )
		{
			//alert(FirstnameField+" "+LastnameField+" "+PasswordField+" "+LifeguardField+" "+InstructorField+" "+HeadguardField );
			$("#UpdateReturnError p").empty();
			//Make post call for Update Employee.
			var inputarr = {};
			inputarr['ID'] = ID;		
			inputarr['firstname'] = FirstnameField;
			inputarr['lastname'] =LastnameField;			
			inputarr['password'] =PasswordField;
			inputarr['lifeguard'] =LifeguardField;
			inputarr['instructor'] =InstructorField;
			inputarr['headguard'] = HeadguardField
			inputarr['supervisor'] = SupervisorField;
			//inputarr['FormUniqueID'] = FormUniqueID
			
			$.post('<?= base_Url(); ?>index.php/UpdateEmployee/UpdateEmployee', inputarr, function(data) {	
			
				console.log(data);				
				//Send employee update information.
				UpdateReturnArr = JSON.parse(data); // Get return information			
				
				var Instructions = UpdateReturnArr['Instructions'];											
				
				switch (Instructions)	
				{
					case 4:
						//Update happened
						alert("Update was succesful.");
						var username = ""+FirstnameField+"."+LastnameField+"";
						var feedback = "Update was succesful with employeeID: "+ID+" Username:"+username+"<br>";
						ReloadTableEmployeelist();
						$("#UpdateReturnError p").append(feedback);
						break;
					case 1:
						//Safety message.	
						alert("The data was not acceptable."); 						
						var Reasons = UpdateReturnArr['Reasons'];	 		
						$("#UpdateReturnError p").append(Reasons);
						break;
					case 2:
						//Illogical message.
						var Reasons = UpdateReturnArr['Reasons'];	 					
						$("#UpdateReturnError p").append(Reasons);													
						break;					
					case 3:
						//Catch error
						alert("There was a computer error when we tried to update.");
						var Reasons = UpdateReturnArr['Reasons'];	 					
						$("#UpdateReturnError p").append(Reasons);							
						break;
					default:
						//Default must be an error on server side.
						alert("There was a computer error when we tried to update.");
						var Reasons = UpdateReturnArr['Reasons'];	 					
						$("#UpdateReturnError p").append(Reasons);							
				}/*End of switch */										
			}); 
		
		}//End of function sendForm
		
		
		
		
	});//End of Jquery Document dot ready
		
</script>


<div id = "UpdateBox" >

		<h1> Update an Employee	</h1>
			
			
			<h3 id = "UpdateEmpyeeCaption">Update Employee Table</h3>			
			<table id = "UpdateEmployeeTable" >
			
				<thead id ="UpdateTableHeadders" >		
					<tr>
						<th class = "UpHeaderCell">EmployeeID </th>
						<th class = "UpHeaderCell">Firstname </th>
						<th class = "UpHeaderCell">Lastname </th>
						<th class = "UpHeaderCell">Username </th>
						<th class = "UpHeaderCell">Password </th>
						<th class = "UpHeaderCell">Lifeguard </th>
						<th class = "UpHeaderCell">Instructor </th>
						<th class = "UpHeaderCell">Headguard </th>
						<th class = "UpHeaderCell">Supervisor </th>
					</tr>
				</thead>
				
		
				<tbody id = "UpdateEmployeeTableinner">
				
				</tbody>
		
			</table>
		
		
		<div id = "UpdateReturnError"> 
				<h3>Update Feedback</h3>
				<p><br></p>
		</div>
		
		
		<form name = "UpdateForm" onsubmit = "return ValidateEmployeeForm();" method = "post" accept-charset = "utf-8" id = "UpdateForm">				
				
				<div id = "UpdateEmployeeDataHalf">
					<label for = "EmployeeID"  class="">ID: </label>
					<input  type = "text" maxlength="10"  size = "3" name = "EmployeeID" id = "EmployeeID" value = ""  disabled />
					
					<label for = "Firstname"  class="">Firstname: </label>
					<input type = "text" name = "Firstname" id = "Firstname" value = ""  maxlength="21"/>					
					
					<label for = "Lastname"  class="">Lastname: </label>
					<input type = "text" name = "Lastname" id = "Lastname"   value = "" maxlength="21"	/> 					
					
					<label for = "password"  class="">Password: </label>
					<input type = "text" name = "password" id = "password" value = "" maxlength="20" />
				</div>
				
				<div id = "UpdateEmployeeCertsHalf">
				<br>
					<label for = "Lifeguard"  class="">Lifeguard: </label>
					<input type = "checkbox"  name = "Lifeguard"  id = "Lifeguard"	/> 		
					
					<label for = "Instructor"  class="">Instructor: </label>
					<input type = "checkbox"  name = "Instructor"  id = "Instructor"	 />
					
					<label for = "Headguard"  class="">Headguard: </label>
					<input type = "checkbox" name = "Headguard" 	id = "Headguard"  />
					
					<label for = "Supervisor"  class="">Supervisor: </label>
					<input type = "checkbox" name = "Supervisor" id = "Supervisor"	/>	
				<div>
				<br>
				<input type = "button" name = "btn_UpdateEmployee" value = "Update Employee" id = "btn_UpdateEmployee"		/>
		</form>

	
		 
		 
		
			


</div>