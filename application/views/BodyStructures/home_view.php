<? session_start(); ?>
<script type = "text/javascript">

	/* JS Global variables */
	var AllShiftsThisUserHas; //will hold it after an Ajax call.
	var EmployeeID = <?= $_SESSION['EmployeeID']; ?>; 
	var certs = {
		Supervisor:	<?= $_SESSION['Supervisor']; ?>,
		Lifeguard:<?= $_SESSION['Lifeguard'] ; ?>,
		Instructor: <?= $_SESSION['Instructor']; ?>,
		Headguard:	<?= $_SESSION['Headguard']; ?>
	}	
	var ListofDates = 	JSON.parse( 	'<?php echo $LISTofDATES ?>' 	);	
	var CurrentYear = '<?php echo $CurrentYear; ?>'; // holds the current year in 2017 format.
	var CurrentMonth = '<?php echo $CurrentMonth; ?>'; // holds the current month in 09 format
	var CurrentDay = '<?php echo $CurrentDay; ?>'; //holds the current day in 21 format
	
	
	
	$(document).ready(function() {	

		getAllsubslipsAvialableForEmployee();
		LoadStepOne();
		Load_AllShiftsThisUserHas();
		ReloadChatBox();
		
		
		
		
		/** Function getAllsubslipsAvialableForEmployee
				Purpose: Retrieves all the subslips that are available for this employee, based on
						 there certifications, and if they are already working.
		*/
		function getAllsubslipsAvialableForEmployee()
		{			
			var inputarr = {};
			inputarr['ID'] = EmployeeID;	
			inputarr['Supervisor'] = certs['Supervisor'];
			inputarr['Lifeguard'] = certs['Lifeguard'];
			inputarr['Instructor'] = certs['Instructor'];
			inputarr['Headguard'] = certs['Headguard'];
		
			$.post('<?= base_Url(); ?>index.php/Home/GetAvailableSubSlips', inputarr, function(data)
			{	
				 		
				AllSubSlipsThatHaventBeenTaken	= JSON.parse(data);
				LoadShiftsUpForGrabs(AllSubSlipsThatHaventBeenTaken);
			});
		}/* End of function getAllsubslipsAvialableForEmployee */
		
		
		
		
		
		/**	Function Load_AllShiftsThisUserHas
				Purpose: Loads the global array object AllShiftsThisUserHas with... All the shifts 
						 this user has Then loads step two.
		*/
		function Load_AllShiftsThisUserHas()
		{
			//Make post call for AllShiftsThisUserHas.
			var inputarr = {};
			inputarr['ID'] = EmployeeID;		
			$.post('<?= base_Url(); ?>index.php/Home/GetThisEmployeesShifts', inputarr, function(data)
			{	
				AllShiftsThisUserHas = JSON.parse(data) ; 						
				LoadStepTwo();			
				
			}); /*End of Ajax post for retrieval of Array OF  All Shifts This User Has*/			
		}/* End of function Load_AllShiftsThisUserHas */
		
		
		
		
		/** Function SetNavNotificationCount.
				Purpose: Sets the navigation count in the nav bar.
		*/
		function SetNavNotificationCount( )
		{						
			$.post('<?= base_Url(); ?>index.php/Home/GetNewNotifications', 0, function(data)
			{	
				var NotificationCount = data;
				$('#NavNotificationCount').empty().append(NotificationCount );
			
			});
		}/*End of Function SetNavNotificationCount*/
		
		
		
		/** Function LoadStepTwo
				Purpose: To load step two with Shifts on a given date from step 1.
		*/
		function LoadStepTwo()
		{
			var SelectDate = getdateFromSelect(); //Get Date From select boxes.
			
			/**
			 * Take SelectDate and create a new array from AllShiftsThisUserHas 
			 * with only shifts that are one a given date. Use that array to populate
			 * step two with options.
			 */			
			var count = 0;
			for( var p in AllShiftsThisUserHas )
			{
					if( AllShiftsThisUserHas[p]["date"] == SelectDate)
					{	count++;	}
			}
			/* Make ShiftOnDate array the size of count of shifts that are on that date... */			
			var ShiftsOnDate = new Array(count);						
			var i = 0;								
			/**for each shift in allShiftsthisuserhas.
			 * 		if this shift is on the selectedDate...
			 *			Add that shift to the ShiftsOnDate array.
			 */
			for( var Shift in AllShiftsThisUserHas )
			{							
					if( AllShiftsThisUserHas[Shift]["date"] == SelectDate)
					{ 																		
						var Position = AllShiftsThisUserHas[Shift]["Position"];
						var Cert; // Will hold the word name of Position						
						Cert = ConvertNumberPosition( Position ); //convert number name of position to word name of position. 																								
						ShiftsOnDate[i] = [];
						ShiftsOnDate[i]["Position"] =  Cert;												
						ShiftsOnDate[i]["startTime"] = AllShiftsThisUserHas[Shift]["startTime"];
						ShiftsOnDate[i]["endTime"] = AllShiftsThisUserHas[Shift]["endTime"];
						ShiftsOnDate[i]["date"] = AllShiftsThisUserHas[Shift]["date"];		
						ShiftsOnDate[i]["ShiftID"] =  AllShiftsThisUserHas[Shift]["ShiftID"];		
						ShiftsOnDate[i]["ShiftHasASubSlip"] = AllShiftsThisUserHas[Shift]["ShiftHasASubSlip"]; //True if shift has a sub slip, false if there is no sub slip.
						ShiftsOnDate[i]["DateOfShiftHasAlreadyPast"] = AllShiftsThisUserHas[Shift]["DateOfShiftHasAlreadyPast"];						
						i++; //Keep count of the shifts on date.
					}
			}
			
			/**
			 * Now start to build option elements for the select box.
			 */
			var CountOFshiftsOnDate = i;			
			var OptionsMSG = "";
			if ( CountOFshiftsOnDate == 0 )
			{		//There are no shifts on date... so make one option saying there are no shifts.
					OptionsMSG += "<option value = \"0\" >";
					OptionsMSG += "No Shifts on this date.";
					OptionsMSG += "</option>";
			}
			else
			{
				//There are shifts on date, begin making options for shifts.
				OptionsMSG = ""; 				
				for(var i in ShiftsOnDate)
				{																				
					var	ShiftHasASubSlip =  ShiftsOnDate[i]["ShiftHasASubSlip"];
					var	DateOfShiftHasAlreadyPast = ShiftsOnDate[i]["DateOfShiftHasAlreadyPast"];	
					//If shift has already passed...
					if( DateOfShiftHasAlreadyPast == true ) 
					{   //Make an option saying it already passed.
						OptionsMSG += "<option value = \"0\" >"				
						OptionsMSG +=  "( This date has past. )";
					}
					else if(ShiftHasASubSlip == true  ) //If shift already has a subslip
					{ 	//Make an option saying it already has a subslip.
						OptionsMSG += "<option value = \"0\" >"				
						OptionsMSG += "( You have  submitted a SubSlip for this shift. )";						
					}
					else
					{   //Make an option for a shift that can take a subslip.
						var Position = ShiftsOnDate[i]["Position"];
						var Start = ShiftsOnDate[i]["startTime"];
						var End = ShiftsOnDate[i]["endTime"];
						var Date = ShiftsOnDate[i]["date"];							
						var ShiftID = ShiftsOnDate[i]["ShiftID"];		
						var OptionValue = ""+Position+" "+Start+" "+End+" "+Date+" "+ShiftID+"";						
						OptionsMSG += "<option value = \""+OptionValue+"\" >"										
					}
					
					//Finally close the subslip option element with the important information.
					OptionsMSG += "Position: "+	ShiftsOnDate[i]["Position"];					
					var ST = convert24HourTo12hr(ShiftsOnDate[i]["startTime"]);
					OptionsMSG += " Time: "+ST;					
					var ET = convert24HourTo12hr(ShiftsOnDate[i]["endTime"]);				
					OptionsMSG += " - "+	ET;					
					OptionsMSG += " Date: "+	ShiftsOnDate[i]["date"];							
					OptionsMSG += "</option>";				
				}
			}
			$('#ShiftsOnDateSelect').empty();				
			$('#ShiftsOnDateSelect').append( OptionsMSG);
		}/* End of Function LoadStepTwo */
		
		
		
		
		
		
		


		
		/* When Hand in Sub Slip Form button gets clicked  */
		$('#HandInSubslipSubmit').click(function(){
		
			SelectedSHIFTval = $('#ShiftsOnDateSelect option:selected').val();
			/* if SelectedSHIFTval == 0 then do not upload. otherwise load. */
			if ( 	SelectedSHIFTval  == 0	)
			{	//do not upload.
				alert("Cannot make a SubSlip for this shift.");
			}
			else
			{	//Value is not zero. Prepare data to be sent to server.				
				//Initialize the input array.
				var inputArr = {};
					inputArr['Date'] = "";			
					inputArr['Position'] = "";		
					inputArr['startTime'] = ""; 	
					inputArr['endTime'] = ""; 		
					inputArr['EmployeeID'] = "";
					inputArr['Reason'] = "";
					inputArr['ShiftID'] = "";				
				
				/* Break up SelectedSHIFTval into holding variables.	*/
				var valsplit = SelectedSHIFTval.split(" ");
				var Position = valsplit[0];
				Position = ConvertWordPosition(Position); // Convert the word value of position to the number value of position.
				var Start = valsplit[1];
				var End = valsplit[2];
				var Date = valsplit[3];
				var ShiftID = valsplit[4];
				var ID = EmployeeID;
				var Reason = $('#reason').val();			
		
				//Place in inputArr	 then make the sub slip.		
				inputArr['Date'] = Date;	
				inputArr['Position'] = Position;		
				inputArr['startTime'] = Start; 	
				inputArr['endTime'] = End; 		
				inputArr['EmployeeID'] = ID;
				inputArr['ShiftID'] = ShiftID;
				inputArr['Reason'] = Reason;											
				$.post('<?= base_Url(); ?>index.php/Home/MakeThisSubSlip', inputArr, function(data)
				{						
					var MSG = JSON.parse(data);														
					var Instructions = MSG["Instructions"];//Get the instructions returned form php.
					
					/* Based on the Instructions choose the correct feedback messge.*/					
					switch (Instructions)	
					{
						case 0:
							alert("Hand in SubSlip failed because of an error."); 
							break;
						case 1:
							Load_AllShiftsThisUserHas()							
							alert( "SubSlip was submitted. You will be notified if your shift gets taken.");							
							break;
						case 2:
							alert("You have already submitted a SubSlip for this shift.");
							break;
						case 5:
							alert("Notification updated failed.");
							break;
						default:
							alert("Hand in SubSlip failed because of an error.");
					}/* End of switch */
					SetNavNotificationCount();					
				});/*End of Ajax post*/														
			}/* End of Else value == 0 then do not upload. */			
		});/* End of When Hand in Sub Slip Form button gets clicked. */

		
		
		
		
		
		
		
		
		
		/**	Function LoadStepOne
				Purpose: Is to load step one with correct dates.
		*/
		function LoadStepOne()
		{			
			//Populate Year and Month select boxes
			for ( var Year in ListofDates ) 
			{
				/* Append an option element to the year select element */
				$('#Year').append($('<option>', { value: Year, text: Year }));
					
				for ( var Month in ListofDates[Year])
				{							
					if (  Year == CurrentYear          )
					{						 
						/* Append an option element to the month select element */
						$('#Month').append($('<option>', { value: Month, text: Month }));
					}
				}				
			}
			
			//Populate Day Select Options with current month
			var MaxDaysInMonth = ListofDates[CurrentYear][CurrentMonth];
			for ( var i = 1; i <= MaxDaysInMonth; i++)
			{
				$('#Day').append($('<option>', { value: i, text: i }));
			}
			/* Set Default values for list boxs. */					
			$('#Year').val(CurrentYear); //Set Year to This Year			
			$('#Month').val(CurrentMonth); //Set Month to This Month			
			$('#Day').val(CurrentDay); //Set to CurrentDay
		}/* End of Function LoadStepOne */

		
		
		
		
		
		/* When Step one list boxes get changed */	
		$('#Year').change(function(){
			ReCalculateDays();//Re-calculates the days select box based on the month and year chosen.
			LoadStepTwo();
		});
		$('#Month').change(function(){
			ReCalculateDays();//Re-calculates the days select box based on the month and year chosen.
			LoadStepTwo();
		});
		$('#Day').change(function(){
			LoadStepTwo();
		});
		/*	End of When Step one list boxes get changed. */
		
		
		
		
		
		/** Function ReCalculateDays
				Purpose: Re-calculates the days select box based on the month and year chosen.
		*/
		function ReCalculateDays()
		{
			//When year or month gets changed re-populate days 			
			var ChoosenMonth = '';		//Get selected Month
			$( "#Month option:selected" ).each(function() {
				ChoosenMonth += $( this ).text() + '';
			});								
			var ChosenYear			= ''; //Get selected year.. 
			$( "#Year option:selected" ).each(function() {
				ChosenYear += $( this ).text() + '';
			});							
			$('#Day').empty();//Empty SelectBoxes 					
			/* now re-populate days */
			var MaxDaysInMonth = ListofDates[ChosenYear][ChoosenMonth];
			for ( var i = 1; i <= MaxDaysInMonth; i++)
			{
				/*Append an option box to day select box.*/
				$('#Day').append($('<option>', { value: i, text: i }));
			}			
		}/* End of function ReCalculateDays */
		
		
		
		
		
		
		/** Function LoadShiftsUpForGrabs		
				Purpose: Loads the ShiftsUpForGrabs div with shifts that are available.
		*/
		function LoadShiftsUpForGrabs(AllSubSlipsThatHaventBeenTaken)
		{
			var MSG = "";
			/* For subslips in AllSubSlipsThatHaventBeenTaken array*/
			for( var subslip in AllSubSlipsThatHaventBeenTaken)
			{
				var TypeofShift = "";
				var ShiftClass = "";
				var Position = AllSubSlipsThatHaventBeenTaken[subslip]["Position"];
				
				/* If user has cert for this position then print a sub slip*/ 
				var displayShift = false; //Assuming user doesn't have the certs for shift.				
				if( Position == 1)
				{
					if ( certs[ "Lifeguard" ] == 1 )
					{
						displayShift = true;
						TypeofShift = "Lifeguard";
						ShiftClass = "LifeguardShift";
					}
				}
				else if( Position == 2)				
				{
					if ( certs[ "Instructor" ] == 1 )
					{
						displayShift = true;
						TypeofShift = "Instructor";
						ShiftClass = "InstructorShift";
					}
				}
				else if( Position == 3)
				{
					if ( certs[ "Headguard" ] == 1 )
					{
						displayShift = true;
						TypeofShift = "Headguard";
						ShiftClass = "HeadGuardShift";
					}
				}
				else if( Position == 4)				
				{
					if ( certs[ "Supervisor" ] == 1 )
					{
						displayShift = true;
						TypeofShift = "Supervisor";
						ShiftClass = "SupervisorShift";
					}
				}
				
				/* If the subslip's creator is the same as the user that is logged in do not display the shift. */
				var NotUsersShift = true; //Assuming its not their shift..
				var CreatorID = AllSubSlipsThatHaventBeenTaken[subslip]["CreatorID"];				
				if(	CreatorID == EmployeeID	)
				{ NotUsersShift = false; }	
				
				/*If they have ther certifications and there not the same user logged in, display the subslip. */
				if ( displayShift == true && NotUsersShift == true && AllSubSlipsThatHaventBeenTaken[subslip]["Conflict"] == false)
				{																					
					var ShiftDate = AllSubSlipsThatHaventBeenTaken[subslip]["ShiftDate"];
					var displayDate = formatDate(ShiftDate);
					var name = AllSubSlipsThatHaventBeenTaken[subslip]["Firstname"]+" "+AllSubSlipsThatHaventBeenTaken[subslip]["Lastname"];
					var Time = AllSubSlipsThatHaventBeenTaken[subslip]["ShiftTime"];
					var subslipID = AllSubSlipsThatHaventBeenTaken[subslip]["subslipID"];
					var ownerID = AllSubSlipsThatHaventBeenTaken[subslip]["CreatorID"];
					var ShiftID = AllSubSlipsThatHaventBeenTaken[subslip]["ShiftID"];
					var shReason = AllSubSlipsThatHaventBeenTaken[subslip]["Reason"];
					MSG += "<div id = \"SubSlipCell\" class = \""+ShiftClass+"\"><p>";		
					MSG += "<div id = \"ApproveSubslipID\"  >"+subslipID+"</div>";
					MSG += "<div id = \"ApproveOwnerID\"  >"+ownerID+"</div>";	
					MSG += "<div id = \"SshiftID\">"+ShiftID+"</div> ";					
					MSG += "Date: <span id = \"SshiftDate\">"+displayDate+"</span> <br>";
					MSG += "Time: <span id = \"SshiftTime\">"+Time+"</span><br>";
					MSG += "Position: <span id = \"SshiftPosition\">"+TypeofShift+"</span><br>";
					MSG += "Name: <span id = \"SshiftName\">"+name+"</span><br>";
					MSG += "Reason: <br> <div class = \"ReasonDiv\">"+shReason+"</div><br>"
					MSG += "Take This Shift?"
					MSG += " <input type = \"button\" value = \"Take Shift\" id = \"TakeShiftButton\">";																			
					MSG += "</p></div>";
				}			
			}
			$('#InnerShiftsUpForGrabs').empty();
			$('#InnerShiftsUpForGrabs').append(MSG);
		}/* End of Function LoadShiftsUpForGrabs */
		
		

		
		
		
		
		
		/* When InnerShiftsUpForGrabs gets clicked... allow them to take the shift.*/
		$('#InnerShiftsUpForGrabs').on('click', '#TakeShiftButton', function(){			
			var Position = $(this).siblings('#SshiftPosition').text();
			var theDate = $(this).siblings('#SshiftDate').text();
			var Time = $(this).siblings('#SshiftTime').text();
			var Name = $(this).siblings('#SshiftName').text();
			thedisplayDate = formatDate(theDate); 
			var ShiftID = $(this).siblings('#SshiftID').text();
			var subslipID = $(this).siblings('#ApproveSubslipID').text();
			var ownerID = $(this).siblings('#ApproveOwnerID').text();
			var TakerID = EmployeeID;
			/* Determine if user wants to take this shift... */		
			if (confirm(" Are you sure you wish to take this shift?  \n Position: "+Position+"\n Date: "+thedisplayDate+"\n Time: "+Time+" \n  Current owner: "+Name+"") == true)
			{
				var inputarr = {};
				inputarr['takerID'] = TakerID;		
				inputarr['ownerID'] = ownerID;
				inputarr['subslipID'] = subslipID; 
				inputarr['ShiftID'] = ShiftID;
				$.post('<?= base_Url(); ?>index.php/Home/TakeSubSlip', inputarr, function(data)
				{				
						if( data == 1)
						{
							getAllsubslipsAvialableForEmployee();
							SetNavNotificationCount();
							alert( "You have signed that subslip. Subslip now needs to be approved by the supervisor. You will be notified when it gets approved." );					
						}					
				});
			}
		});
		/*End of when InnerShiftsUpForGrabs gets clicked.*/
		
		
		
		
		/*When PostButton gets clicked... write the message on the chatbox.*/
		$('#PostButton').click(function(){
			var ChatMessage = "";
			ChatMessage = $('#ChatInputTextArea').val();
			var inputarr = {};
				inputarr['employeeID'] = EmployeeID;		
				inputarr['ChatMessage'] = ChatMessage;				
			$.post('<?= base_Url(); ?>index.php/Home/PostToChatBox', inputarr, function(data) {	
					var instructions = data;									
					if( instructions == 0) {
						//Post failed.
						$('#ChatInputTextArea').val("...");
						ReloadChatBox();
					} else {
						//Post worked.
						$('#ChatInputTextArea').val("...");
						ReloadChatBox();
					}
			});
		});
		/*End of When PostButton gets clicked */
		
		
		
		
		
		
		
		
		/** Function ReloadChatBox
				Purpose: Reloads the chatbox.
		*/
		function ReloadChatBox()
		{
			$.post('<?= base_Url(); ?>index.php/Home/ReloadChatBox', 0, function(data)
			{	
				var msg = "";
				ChatList = JSON.parse(data);
				for(var post in ChatList)
				{					
					var name = ChatList[post]['Name'];
					var date = ChatList[post]['CreatedDateAndTime'];
					var eid = ChatList[post]['employeeID'];
					var Message = ChatList[post]['Message'];					
					msg += "<div id = \"ChatPost\">";
					msg += "<div id = \"PostName\">"+name+"</div><div class = \"HideEmployeeID\">"+date+"</div>";
					msg += "<div id = \"PostDate\">"+date+"</div>";
					msg += "<div id = \"PostMessage\">";
					msg += "<p>";
					msg += ""+Message+"";
					msg += "</p>";
					msg += "</div>";
					msg += "</div>";																				
				}
				$("#ChatMessageBox").empty().append(msg);
			});			
		}/*End of Function ReloadChatBox */
		
		
		
		
	}); //End of Jquery's Document dot on load.
</script>


<div id = "HomePage">

	<h1> The Home page </h1>
	
	<form id="SubSlipForm" name = "SubSlipForm"  accept-charset= "utf-8"  >		
		<h2>SubSlip Form</h2>
	
		<div id = "SubSlipSelectDate"> 
			<p><strong>First</strong>, select the date of the shift. </p>
			<select form_id = "" name = "Year" id = "Year" ></select>
			<select form_id = "" name = "month" id = "Month" ></select>
			<select form_id = "" name = "day" id = "Day" ></select>					
		</div>
		
		<div id = "SelectShiftsDiv" >
			<p>	<strong>Second</strong>, choose the shifts on that date. </p>
			<select form_id = "" class = "dropdown dropdown-header" id = "ShiftsOnDateSelect" name = "ShiftsOnDateSelect" >
			</select>
		</div> 
	
		<div id = "ReasonDiv">
			<p><strong>Third</strong>, write the reason you cannot work this shift. </p>
			<h3>Reason:	</h3>
			<textarea required id = "reason" form_id = "" name="reason" maxlength = "200"  form="SubSlipForm"> Reason for why you cannot work </textarea>
		</div>
		
		<div id = "HandInSubslip">
			<p><strong>Finally</strong>, click <strong>"Hand in Subslip"</strong> when you filled out form.</p>
			<input type = "button" value = "Hand in Subslip" name = "HandInSubslipSubmit" id = "HandInSubslipSubmit" size = "200" >		
		</div>
	</form>

	
	<div id = "ChatBox">	
		<h3 id = "ChatBoxTite"> Chat Box </h3> 
		<div id = "ChatInputBox"> 
			<div id = "ChatName">Write something below to post.</div>		
			<textarea  id = "ChatInputTextArea" form_id = "" name="ChatInputTextArea" maxlength = "200"  form="SubSlipForm"> ... </textarea>		
			<input type = "button" value = "Post" class="btn btn-success" name = "PostButton" id = "PostButton" size = "200" >				
		</div>
		<div id = "ChatMessageBox">					
		</div>	
	</div>


	<div id = "ShiftsUpForGrabs"> 
		<h3>Subslips Available For You</h3>
		<div id = "InnerShiftsUpForGrabs"></div>
	</div>

	
</div><!--End of HomePage. -->