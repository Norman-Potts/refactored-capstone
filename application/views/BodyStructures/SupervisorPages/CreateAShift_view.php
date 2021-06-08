<?php
/*If the currently logged in user doesn't have Supervisor certification they shouldn't be
		allowed to view this page*/
	if ( $_SESSION['Supervisor']	== false)
	{
		$page = base_url() . "index.php?/Home";
		$this->userauth->redirect($page);
	}			
?>
<script type="text/javascript" src="<?= assetUrl(); ?>js/DisplaySchedule.js"></script>
<script type = "text/javascript">		
/**PHP TPL variables		
	$TodaysScheduleArray an object that holds the information to build the schedule.
	$CurrentYear holds current year as YYYY. Used to set determine current year on the html front end.
	$CurrentDay holds current month as MM. Used to set determine current month on the html front end.
	$Date holds the current date as 10 August 2017 format. Used to display date in date box on first load.
*/	
	var TodayScheduleArray = JSON.parse( '<?php echo $TodaysScheduleArray; ?>'	);
	var CurrentYear = '<?php echo $CurrentYear; ?>'; // holds the current year in 2017 format.
	var CurrentMonth = '<?php echo $CurrentMonth; ?>'; // holds the current month in 09 format
	var CurrentDay = '<?php echo $CurrentDay; ?>'; //holds the current day in 21 format
	var ListofDates = 	JSON.parse( 	'<?php echo $LISTofDATES ?>' 	);		
	
	$(document).ready(function() {	
		
		reloadEmployeeSelectBox();//Try to load schedule off the start. This causes the feed back for no shifttype choosen to be triggered.
				
		var DisplayDate = "Date: <?php echo $Date; ?>";
		$('#CreateShiftDateBox p').append(	DisplayDate	); //Set the display date for today.
		DisplaySchedule( TodayScheduleArray	); //Display the schedule with current schedule of today.			
				
	
	
	
/**Step 1			
	They are set to todays date. They are recalculated every time they get changed.				
*/							
		/*For each year.
			Append the year to the year select box.
			For each month in that year.
				Append that month as an option to the month select box.
		*/
		for ( var Year in ListofDates ) 
		{
			$('#Year').append($('<option>', { value: Year, text: Year }));						
			for ( var Month in ListofDates[Year])
			{							
				if (  Year == CurrentYear )
				{
					$('#Month').append($('<option>', { value: Month, text: Month }));
				}
			}				
		}		
		/* Populate Day Select Options with current month. */
		var MaxDaysInMonth = ListofDates[CurrentYear][CurrentMonth];
		for ( var i = 1; i <= MaxDaysInMonth; i++)
		{
			$('#Day').append($('<option>', { value: i, text: i }));
		}
		/* Set Default values for list boxes. */				
		$('#Year').val(CurrentYear);	//Set Year to This Year	
		$('#Month').val(CurrentMonth);	//Set Month to This Month		
		$('#Day').val(CurrentDay);	//Set to CurrentDay
		/** JQuery Functionality.
			When a Date box value gets changed...
				Set the Date of Schedule to that date,
				Reload the employee select box with employees who can work that time.
				Reload the schedule array with the selected date.
				When its month or year, recalculate the days of that month.						
		*/
		$('#Year').change(function(){
			reloadEmployeeSelectBox();
			var selectDate = getdateFromSelect(); 
			reloadTheScheduleArray(selectDate);  
			ReCalculateDays();
		});		
		$('#Month').change(function(){
			reloadEmployeeSelectBox(); 
			var selectDate = getdateFromSelect();
			reloadTheScheduleArray(selectDate);
			ReCalculateDays();
		});		
		$('#Day').change(function(){
			reloadEmployeeSelectBox();
			var selectDate = getdateFromSelect(); 
			reloadTheScheduleArray(selectDate);			
		});
/*End of Step 1*/

		
		
		
/** Set up Step 2.			
	Populate start time with array times 12:00am to 12:00pm					
*/
		/*Array of times hold 12 hour time for start end time select options, 48 elements 12x4,*/
		var AMPM12HourArray = [ "12:00am", "12:30am", "1:00am", "1:30am", "2:00am", "2:30am", "3:00am", "3:30am", "4:00am", "4:30am", "5:00am", "5:30am", "6:00am", "6:30am", "7:00am", "7:30am", "8:00am", "8:30am", "9:00am", "9:30am", "10:00am", "10:30am", "11:00am", "11:30am", "12:00pm", "12:30pm", "1:00pm", "1:30pm", "2:00pm", "2:30pm", "3:00pm", "3:30pm", "4:00pm", "4:30pm", "5:00pm", "5:30pm", "6:00pm", "6:30pm", "7:00pm", "7:30pm", "8:00pm", "8:30pm", "9:00pm", "9:30pm", "10:00pm", "10:30pm", "11:00pm", "11:30pm" ];
		/* for each element in ampm12hourarray populate StartTime with option elements. */
		for ( var i = 0; i < 48; i++)
		{			
			var time = AMPM12HourArray[i];
			$('#StartTime').append($('<option>', { value: i, text: time }));
		}
		/* for each element in ampm12hourarray populate EndTime with option elements. */
		for ( var i = 1; i < 48; i++)
		{
			var time = AMPM12HourArray[i];
			$('#EndTime').append($('<option>', { value: i, text: time }));
		}						
		/** JQuery Functionality.
			When #StartTime changes repopulate #EndTime with half hours from array.
		*/
		$('#StartTime').change(function(){								
			$('#EndTime').empty();//Empty select box
			
			var starttime = 0;//Get selected starttime
			$( "#StartTime option:selected" ).each(function() {
				starttime = parseInt( $( this ).val() );
			});
			
			/* EndTime will contain every time after start time. */
			var beginingofEndTimes = starttime + 1;	
			for ( var i = beginingofEndTimes; i < 48; i++)
			{				
				var time = AMPM12HourArray[i];
				$('#EndTime').append($('<option>', {
					value: i,
					text: time
				}));
			}			
			reloadEmployeeSelectBox();
		});	
		/* When End time list box gets changed. reload the employee select box.*/
		$('#EndTime').change(function(){ reloadEmployeeSelectBox(); });				
/* End of Step 2. */		
			
			
		
		
/**Step 3. Certifications
	When a certifications gets changed... 
*/
		$('#Lifeguard').change(function(){
			reloadEmployeeSelectBox();
		});
		$('#Instructor').change(function(){
			reloadEmployeeSelectBox();
		});
		$('#Headguard').change(function(){
			reloadEmployeeSelectBox();
		});
		$('#Supervisor').change(function(){
			reloadEmployeeSelectBox();
		});
/* End of Step 3*/
	
	
	
	
/* Step 4 Staff Available */
		/** JQuery Functionality.
			When an employee cell gets clicked in the employee listbox. Remember it being clicked.
		*/
		$('#EmployeeListInner').on('click', '#EmployeeCell', function(){															
				var ID = $(this).find(".HideEmployeeID").text(); //Get the ID of this row.
				$('#ChosenEmployee').empty().append(ID);//Append to hidden div.
				/**
					Remove the mouse hover classes and add the choose background blue class.
				*/
				$( this ).removeClass("DELETEnotHover");
				$( this ).removeClass("DELETEliOVER");
				$(".Backgroundblue" ).removeClass("Backgroundblue");//Also remove any previously chosen employee cells.
				$( this ).addClass("Backgroundblue");//Add background blue to this chosen class.
		});/*End of on click of employee row.*/
		/** JQuery functionality.
			When mouse overs over an employee cell change colour of cell.
		*/
		$('#EmployeeListInner').on('mouseenter', '#EmployeeCell', function(){																			
			$( this ).removeClass("DELETEnotHover");
			$( this ).addClass("DELETEliOVER");	
		}).on('mouseleave', '#EmployeeCell', function() {
			$( this ).removeClass("DELETEliOVER");
			$( this ).addClass("DELETEnotHover");				
		});		
		/** JQuery Functionality.
				When the button create shift gets clicked validate the form and create the shift.		
		*/
		$('#CreateShiftSubmit').click(function(){			
			var cancelSubmission = false;	//boolean
			var DayDate;		//Example: 21
			var MonthDate;		//Example: July
			var YearDate;		//Example: 2017			
			var StartTim;		//Example: 3:00am
			var EndTime;		//Example: 7:00pm			
			var ShiftType;		//Example: Lifeguard			
			var ChosenEmployee;	//array		
			
			/* Get the values of step one and two. */
			DayDate = $('#Day').val();
			MonthDate = $('#Month').val();
			YearDate = $('#Year').val();			
			StartTime = $('#StartTime').val();
			EndTime = $('#EndTime').val();			
			
			/* If no type of shift is chosen should cancel. */
			ShiftType = $('input[name=Cert]:checked', '#CreateShiftForm').val();
			if (  ShiftType != 1 && ShiftType != 2 && ShiftType != 3 && ShiftType != 4   )
			{			
				//Do not insert and report feedback.
				cancelSubmission = true; 
				$('#FeedBack').empty();
				var errmsg = "<span style ='text-decoration: underline; font-size: 2em;' > Shift type has not been choosen yet in step 3. </span> ";
				$('#FeedBack').append(errmsg);
			}								
			else if ($("#ChosenEmployee").is(':empty'))
			{					
				cancelSubmission = true; 
				$('#FeedBack').empty();
				var errmsg = "<span style ='text-decoration: underline; font-size: 2em;' > An employee has yet to be choosen in step 4. </span> ";
				$('#FeedBack').append(errmsg);
			}
			else
			{
				$('#FeedBack').empty();
				
				ChosenEmployee = $('#ChosenEmployee').text();				
				$('#ChosenEmployee').empty();// Empty the ChosenEmployee Div.
				
				/* Send data to PHP so shift can be inserted into form. */
				var inputarr = {};
				inputarr['Day'] = DayDate;
				inputarr['Month'] = MonthDate;
				inputarr['Year'] = YearDate;
				inputarr['StartTime'] = StartTime;
				inputarr['EndTime'] = EndTime;
				inputarr['ShiftType'] = ShiftType;
				inputarr['ID'] = ChosenEmployee;				
				$.post('<?= base_Url(); ?>index.php/CreateAShift/CreateTheShift', inputarr, function(data)
				{						
					var feedback = "";															
					switch (data)
					{
						case "0": //error
							feedback = "There was an error making the shift.";
						break;
						case "1": //Success
							feedback = "The shift was created succesfuly.";
						break;
						case "2": //Does not have certification.
							feedback = "This employee does not have the correct certification for the shift.";
						break;
						case "3": //Already has shift.
							feedback = "This employee already has a shift at that time.";
						break;
						default:
							feedback = "There was an error making the shift.";
					}					
					$('#FeedBack').empty().append(feedback);
					var selectDate = getdateFromSelect(); //Determines the date in the select boxes 					
					reloadTheScheduleArray(selectDate);				
					reloadEmployeeSelectBox();
				});				
			}			
		});/* End of click on create shift button */
/* End of Step 4 */ 	
		
		
		
	
		
		


		
		
		
		
		
		
		
/***************************************************************************************************
		Functions below.
***************************************************************************************************/		
	
	
		/** Function reloadEmployeeSelectBox			
				Purpose: Loads the employee list box with employees that have the chosen
				         certifications and don't have a shift at the given time. This function 
						 occurs when step 1 2 or 3 get changed.
		*/
		function reloadEmployeeSelectBox()
		{	
			var AvialableStaff; 
			/* Get values in step 1,2,3 */
			DayDate = $('#Day').val();
			MonthDate = $('#Month').val();
			YearDate = $('#Year').val();
			/* Get the start time and end time values. */
			StartTime = $('#StartTime').val();
			EndTime = $('#EndTime').val();
			
			ShiftType = $('input[name=Cert]:checked', '#CreateShiftForm').val();
			if ( ShiftType != 1 && ShiftType != 2 && ShiftType != 3 && ShiftType != 4  )
			{
				//Cert Has not been set yet... 
				$('#FeedBack').empty().append("A type of shift has yet to been choosen.");			
			}
			else
			{
				
				var dayofweek = determineDayOfWeek(YearDate, MonthDate, DayDate);// Determine the day of week for availability.
				
				/** Send data to PHP using Ajax so shift can be inserted into form.
					Then build the select options with the returned json data.
					Finally append them to the EmployeeListInner div.
				*/			
				var inputarr = {};
				inputarr['Day'] = DayDate; //Example: 8
				inputarr['Month'] = MonthDate; //Example: August
				inputarr['Year'] = YearDate;	//Example: 2017
				inputarr['StartTime'] = StartTime; //In Half hour time
				inputarr['EndTime'] = EndTime;		//In half hour time
				inputarr['ShiftType'] = ShiftType; //1 2 3 4 or 5											
				
				$.post('<?= base_Url(); ?>index.php/CreateAShift/reloadEmployeeSelectBox', inputarr, function(data)
				{	
					AvialableStaff = JSON.parse( data );		
					var EmployeeSelectOptions = "";
					$('#EmployeeListInner').empty();
					
					/** for each employee in AvialableStaff
							Build the select option for that employee.
							
					*/
					for ( 	var Staff in AvialableStaff	)
					{			
						/* Get the certification of this employee. */					
						var lifeguard = "";var instructor = "";var headguard = "";var supervisor = "";
						if(AvialableStaff[Staff]['Lifeguard'] == true){ lifeguard = "Yes";}else{lifeguard = "No";}
						if(AvialableStaff[Staff]['Instructor'] == true){ instructor = "Yes";}else{instructor = "No";}
						if(AvialableStaff[Staff]['Headguard'] == true){ headguard = "Yes";}else{headguard = "No";}
						if(AvialableStaff[Staff]['Supervisor'] == true){ supervisor = "Yes";}else{supervisor = "No";}
						
						/*Build cert table for this employee*/
						var certtbl  = "";
						certtbl  += "<table  border=\"1\" class = \"CertTableinEmployeeList\">";
						certtbl  += "<thead><tr><th>Certifications</th><th>Yes or No </th> </tr></thead><tbody>";
						certtbl  += "<tr><td>Lifeguard </td> <td> "+lifeguard+"</td>";
						certtbl  += "</tr><tr>";
						certtbl  += "<td>Instructor </td> <td>"+instructor+"</td>";
						certtbl  += "</tr><tr>";
						certtbl  += "<td>Headguard </td>  <td>"+headguard+"</td>";
						certtbl  += "</tr><tr>";
						certtbl  += "<td>Supervisor </td> <td> "+supervisor+" </td>";
						certtbl  += "</tr></tbody></table>";
						
						/*Get Availability String for this employee */
						var Av = AvialableStaff[Staff]['Availability'];										
						var AvailabilityStr = displayAvailability(Av, dayofweek);
						
						/* Get ID and name for this employee */ 
						var ID = AvialableStaff[Staff]["employeeID"];
						var name = AvialableStaff[Staff]["Firstname"]+" "+ AvialableStaff[Staff]["Lastname"];	
						
						/*Build Select option for this employee.*/
						EmployeeSelectOptions += "<div id = \"EmployeeCell\" class = \"DELETEnotHover\">";
							EmployeeSelectOptions += "<div class = \"EmployeeCellLEFT\"> "+name+"";					
							EmployeeSelectOptions += "<span class = \"HideEmployeeID\">"+ID+"</span>";
							EmployeeSelectOptions += "<br /><span class = \"Availability\"> Availability: <br />"+AvailabilityStr+"</span>";	
							EmployeeSelectOptions += "</div><div class = \"EmployeeCellRIGHT\">"+certtbl+"</div>";
						EmployeeSelectOptions += "</div>";								
					}
					$('#EmployeeListInner').append( EmployeeSelectOptions );//Append the select options to the list.
				});
			}
		}/* End of reloadEmployeeSelectBox */
		
		
		
		
		/** Function determineDayOfWeek
				Purpose: Returns day of week as a string.
		*/
		function determineDayOfWeek(YearDate, MonthDate, DayDate )
		{	
			var dayofweek;			
			var month;
			switch (MonthDate)	
			{
				case "January":
					month = "01";
					break;
				case "February":
					month = "02";
					break;
				case "March":
					month = "03";
					break;
				case "April":
					month = "04";
					break;
				case "May":
					month = "05";
					break;
				case "June":
					month = "06" ;
					break;
				case "July":
					month = "07";
					break;
				case "August":
					month = "08";
					break;
				case "September":
					month = "09";
					break;
				case "October":
					month = "10";
					break;
				case "November":
					month = "11";
					break;
				case "December":
					month = "12";					
			}/* End of switch MonthDate */
					
			var d = new Date(YearDate, month, DayDate, 0,0,0,0);
			var day = d.getDay();
			var dayofweek = "";
			switch (day)	
			{	case 0:
					dayofweek = "Friday";
					break;
				case 1:
					dayofweek = "Saturday";
					break;
				case 2:
					 dayofweek = "Sundays";
					break;
				case 3:
					dayofweek = "Mondays";
					break;
				case 4:
					dayofweek = "Tuesday";
					break;
				case 5:
					dayofweek = "Wednesday";
					break;
				case 6:
					dayofweek = "Thrusday";
					break;
				
			}/* End of switch day */
			
			return dayofweek;
		}/* End of Function determineDayOfWeek*/
		
		
		
		
		/** Function displayAvailability
				Purpose: Displays the availability of each employee in their cell.
		*/
		function displayAvailability( Availability, dayofweek )
		{
			var MSG = "";	var MondayMSG = "";
			if (Availability == null)
			{
				MondayMSG += "<li>Not yet set.</li>";
			}
			else
			{				
				MondayMSG += "<li>Notes: "+Availability['Notes']+"  </li>";							
				if( Availability[dayofweek]['ANOT'] == true )
				{
					MondayMSG += "<li>Not Available.</li>"
				}
				else if (  Availability[dayofweek]['Anytime'] == true || Availability[dayofweek]['A1'] == false &&Availability[dayofweek]['A2'] == false &&Availability[dayofweek]['A3'] == false &&Availability[dayofweek]['A4'] == false &&Availability[dayofweek]['A5'] == false &&Availability[dayofweek]['A6'] == false &&Availability[dayofweek]['A7'] == false )
				{
					MondayMSG += "<li>Available Anytime.</li>";
				}
				else
				{
					if( Availability[dayofweek]['A1'] == true)
					{	MondayMSG += "<li>Before 5:30am.</li>";	}									
					
					if( Availability[dayofweek]['A2'] == true)
					{	MondayMSG += "<li>5:30am to 9:00am</li>"; }
					
					if( Availability[dayofweek]['A3'] == true)
					{ MondayMSG += "<li>9:00am to 11:30pm</li>"; }
				
					if( Availability[dayofweek]['A4'] == true)
					{	MondayMSG += "<li>11:30pm to 1:30pm</li>"; }
					
					if( Availability[dayofweek]['A5'] == true)
					{	MondayMSG += "<li>1:30pm to 3:15pm</li>";	}
					
					if( Availability[dayofweek]['A6'] == true)
					{	MondayMSG += "<li>3:25pm to 4:30pm</li>";	}
					
					if( Availability[dayofweek]['A7'] == true)
					{	MondayMSG += "<li>4:30pm onwards</li>";	}
				}								
			}
			MSG += " "+dayofweek+": <ul>"+MondayMSG+"</ul>";			
			return MSG;
		}/* End of Function displayAvailability*/




		
		/** Function ReCalculateDays
				Purpose: When year or month gets changed repopulate days. Re-calculates the days
						 select box based on the month and year chosen.
		*/
		function ReCalculateDays()
		{			
			/* Get selected Month string in the select month box.*/
			var ChoosenMonth = '';
			$( "#Month option:selected" ).each(function() {ChoosenMonth += $( this ).text() + '';});
					
			/*Get selected Year string in the selected year box. */
			var ChosenYear = '';
			$( "#Year option:selected" ).each(function(){ChosenYear += $( this ).text() + '';});
				
			//Empty SelectBox.
			$('#Day').empty();
					
			//Now Repopulate days selectbox.
			var MaxDaysInMonth = ListofDates[ChosenYear][ChoosenMonth];//<-- This returns the count of days in the chosen month.
			for ( var i = 1; i <= MaxDaysInMonth; i++)
			{
				$('#Day').append($('<option>', { value: i, text: i }));
			}			
		}/*End of Function ReCalculateDays */		
		
		
		
		
		/**	Function reloadTheScheduleArray			
		 		Purpose: Reloads the schedule array with the date requested and displays the 
		 		         schedule.			
				Parameters: 'selectDate' the date of the schedule requested to be loaded.
		*/
		function reloadTheScheduleArray( selectDate )
		{			
			/*Change the DateBox of the schedule to selectDate formatted with month as a word.*/
			var formattedDate = formatDate(selectDate);		
			DisplayDate = "Date:"+formattedDate+"";
			$('#CreateShiftDateBox p').empty().append(	DisplayDate	);		
			
			/* Retrieve scheduleArray using AJAX with the selectDate. */
			var inputDate = {}; inputDate['GivenDate'] = selectDate;			
			$.post('<?= base_Url(); ?>index.php/CreateAShift/reloadTheScheduleArraybyGivenDate', inputDate, function(data)
			{	
				var TheScheduleArray  = JSON.parse( data );
				DisplaySchedule(TheScheduleArray); //Display the schedule with the schedule array returned from AJAX.
			});
		}/* End of Function reloadTheScheduleArray */
		
		
	});/*End of Jquery */
	
	
</script>
<div id = "CreateAShiftPage">
	<div id = "CreateAShiftPageTop">		
		<h1>Create a New Shift </h1>
		
		<form name = "CreateShiftForm" id = "CreateShiftForm" >		
		
			<!-- Step 1.
				 Gets loaded by JavaScript from PHP array. TODO: Needs mechanism that stops 
				 presenting dates that are before today. When Step 1 gets changed, reloads step 4.
			-->
			<div id = "Step1"  class = "GeneralWhiteGreyContainer">
				<h3>Step 1</h3>
				<div class = "GeneralWhiteGreyInner">
					<p>Select Shift Date</p>
					<div id = "SelectShiftDate">
						<select name = "Year" id = "Year" ></select>
						<select name = "month" id = "Month" ></select>
						<select name = "day" id = "Day" ></select>	
					</div>
				</div>
			</div><!-- End of Step1 div -->

						
			<!-- Step 2.
				 Gets Loaded by JavaScript. Displays start and end times for every half hour. There 
				 are 48 time pieces compared to the normal 24 hour clock because it splits into 
				 half hours. When Step 2 gets changed reload step 4.
			-->
			<div id = "Step2" class = "GeneralWhiteGreyContainer"  >
				<h3>Step 2</h3>
				<div class = "GeneralWhiteGreyInner">
				<p>Select Shift Time</p>
				<p>Start: 						
					<select name = "StartTime" id = "StartTime" ></select>					
					<span class ="End">End:</span> 						
					<select name = "EndTime" id = "EndTime" ></select>
				</p>
				</div>
			</div><!-- End of Step 2 div -->

					
			<!-- Step 3. 
				 Controls what type of shift will be created. Values are written in the html.								
				 When Step 3 gets changed... Reload step 4 with employees with these certifications.
			-->
			<div id = "Step3" class = "GeneralWhiteGreyContainer" >
				<h3>Step 3</h3>	
				<div class = "GeneralWhiteGreyInner">
					<p>Select type of Shift</p>
						<ul>
							<li><input type = "radio" name="Cert" value = "1" id = "Lifeguard" />Lifeguard</li>
							<li><input type = "radio" name="Cert" value = "2" id = "Instructor" />Instructor</li>
							<li><input type = "radio" name="Cert" value = "3" id = "Headguard" />Headguard</li>
							<li><input type = "radio" name="Cert" value = "4" id = "Supervisor" />Supervisor</li>
						</ul>
				</div>
			</div><!-- End of Step3 div-->
			
			<div id = "FeedBackBox">
			<h3> Feedback</h3>
				<p id = "FeedBack" >				
				</p>
			</div>
			<!-- Step 4.
				 Holds all employees that available based on the time they are available, 
				 and certifications they have. Reloads after Step 1, Step 2, and Step 3.
			-->			
			<div id = "Step4" class = "GeneralWhiteGreyContainer">
				<div id = "ChosenEmployee" class = "HideEmployeeID"></div>				
				<h3>Step 4</h3>	
				<div class = "GeneralWhiteGreyInner">
					<h4>	Select an available employee.</h4>				
					<div id = "EmployeeListBox" >								
						<div id = "EmployeeListInner">							
						</div>
					</div>
				</div>
			</div><!--End of Step4 div -->
			
			<input type = "button" value = "Create Shift" id = "CreateShiftSubmit">							
		</form>	
	</div><!-- End of CreateAShiftPageTop -->

	
	<div id = "CreateAShiftPageBottom">
					<div id = "CreateShiftDateBox"><p>	</p></div>
					<div id = "ScheduleTable" >
							<table id = "schedule" cellpadding = "0" cellspacing = "0" border = "0" >																
							</table>
					</div><!-- End of schedule table -->
	</div><!--End of CreateAShiftPageBottom -->	
	
</div><!-- End of Create Shift page -->