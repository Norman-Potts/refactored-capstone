
<script type="text/javascript" src="<?= assetUrl(); ?>js/DisplaySchedule.js"></script>
<script type = "text/javascript">

	/* Global Variables */	
 	var YYYMMDDToday = "<?php echo $YYYMMDDToday ?>"; //YYYY-mm-dd of today
	var TheScheduleArray = JSON.parse( '<?php echo $TheScheduleArray; ?>'	);
	var TodaysStaff ='<?php echo $Todaystaff ?>' ;	
	var TheCurrentdateoftheschedule;			
	var CurrentYear = '<?php echo $CurrentYear; ?>';
	var CurrentMonth = '<?php echo $CurrentMonth; ?>';
	var CurrentDay = '<?php echo $CurrentDay; ?>';	
	var ListofDates = 	JSON.parse( 	'<?php echo $LISTofDATES ?>' 	);// The array of dates used for the select box.
	
	
	$(document).ready(function() {			
		/**
		 * On Load...
		 * Set display date and display the scheule for today.
         */
		var DisplayDate = "Date: <?php echo $Date; ?>";
		$('#DateBox p').append(	DisplayDate	);
		TheCurrentdateoftheschedule = YYYMMDDToday;
		DisplaySchedule( TheScheduleArray	);
		
		
				
				
		/**
		 * Populate Year and Month select boxes based on the ListofDates array.
		 * For each Year in ListofDates.
		 */
		for ( var Year in ListofDates ) 
		{
			/* Append an option box into the year select element. */
			$('#Year').append($('<option>', { value: Year, text: Year }));					
			/*For each month in this year of ListofDates */
			for ( var Month in ListofDates[Year])
			{							
				if (  Year == CurrentYear          )					
				{    
					/* Append an option box into the month select element. */
					$('#Month').append($('<option>', { value: Month, text: Month }));
				}
			}				
		}
		
		
		
		
		/* Populate Day Select element with Option elements of the current month. */
		var MaxDaysInMonth = ListofDates[CurrentYear][CurrentMonth];
		for ( var i = 1; i <= MaxDaysInMonth; i++)
		{
			/* Append an option element into the day select element. */
			$('#Day').append($('<option>', { value: i, text: i }));
		}
		
		
		
		
		/* Set Default values for list boxes.  */		 		
		$('#Year').val(CurrentYear); //Set Year to This Year
		$('#Month').val(CurrentMonth); //Set Month to This Month		
		$('#Day').val(CurrentDay); //Set to CurrentDay
		
		
		
		
		/* When Year gets changed. */
		$('#Year').change(function(){						
			determineMonthandYearThenRepopulateDays();
		});				
		
		
		
		
		/* When Month gets changed.	*/
		$('#Month').change(function(){						
			determineMonthandYearThenRepopulateDays();					
		});								

		
		
		
		/* When Button gets clicked */
		$('#GoToDate').click(function(){
			/* Load select dates to controller and reload the page and schedule. */			
			var selectDate = getdateFromSelect();
			reloadTheScheduleArray(selectDate);			
		});		

		
		
		
		
		/* When GoToToday gets clicked. */
		$('#GoToToday').click(function(){
			/* Load todays schedule. */			
			var TodayDate = YYYMMDDToday; //YYYY-mm-dd of today.										
			reloadTheScheduleArray(TodayDate);			
		});
		
		
				
		
		/* When Go Back a day gets clicked... */
		$('#GoBackADay').click(function(){			
			/**
			 * Load the previous day relative to the currently displayed schedule. 
			 * Format TheCurrentdateoftheschedule in to a date object then find the previous day.			
			 */
			var BackAdayDate; //YYYY-mm-dd of the previous day of the schedule.										
			var yyyy = TheCurrentdateoftheschedule.substring(0, 4);
			var MM = TheCurrentdateoftheschedule.substring( 5, 7 ); //Month Zero based.			
			var DD = TheCurrentdateoftheschedule.substring( 8, 10);												
			var d = new Date(yyyy+"-"+MM+"-"+DD);
			d.setDate(d.getDate() - 1);
			var BackAdayDate = d.toISOString();
			BackAdayDate = BackAdayDate.substring(0,10);								
			reloadTheScheduleArray(BackAdayDate);					
		});
		
		
		
		
		/* When Go Forward A Day gets clicked... */
		$('#GoForwardADay').click(function(){
			/**
			 * Load the next day relative to the currently displayed schedule.
			 * Format TheCurrentdateoftheschedule in to a date object then find the previous day.			
			 */
			var TheNextDayDate; //YYYY-mm-dd of the next day of the schedule.						
			var BackAdayDate; //YYYY-mm-dd of the previous day of the schedule.										
			var yyyy = TheCurrentdateoftheschedule.substring(0, 4);
			var MM = TheCurrentdateoftheschedule.substring( 5, 7 ); //Month Zero based.					
			var DD = TheCurrentdateoftheschedule.substring( 8, 10);												
			var d = new Date(yyyy+"-"+MM+"-"+DD);
			d.setDate(d.getDate() + 1);
			var TheNextDayDate = d.toISOString();
			TheNextDayDate = TheNextDayDate.substring(0,10);					
			reloadTheScheduleArray(TheNextDayDate);						
		});		
		



	
		$( "#GoToToday" ).addClass("GoToTodaynotHover");		
		$("#GoToToday").hover(
			function() {
				$( this ).removeClass("GoToTodaynotHover");
				$( this ).addClass("liOVER");				
				
			}, function() {
				$( this ).removeClass("liOVER");
				$( this ).addClass("GoToTodaynotHover");
			}
		);
		


		
		
		
		/** Function determineMonthandYearThenRepopulateDays
			Purpose: Determines the value of Month and the value of Year select elements.
					 Then Repopulate Days select box.
		*/ 
	    function determineMonthandYearThenRepopulateDays( )
		{
			var ChoosenMonth = '';			 														
			$( "#Month option:selected" ).each(function() {											//Get selected Month
				ChoosenMonth += $( this ).text() + '';
			});									
			var ChosenYear			= '';															//Get Selected Year
			$( "#Year option:selected" ).each(function() {
				ChosenYear += $( this ).text() + '';
			});									
			$('#Day').empty();																		//Empty SelectBoxes 			
			var MaxDaysInMonth = ListofDates[ChosenYear][ChoosenMonth];								//Now repopluate days 
			for ( var i = 1; i <= MaxDaysInMonth; i++)
			{
				$('#Day').append($('<option>', {value: i,text: i}));
			}				
		}/*End of Function determineMonthandYearThenRepopulateDays*/								
		
		
		
		
		/** Function reloadTheScheduleArray			
				Purpose: Reloads the schedule array with the date requested and displays the schedule.
				Parameters: 'chosenDate' the date of the schedule requested to be loaded.
		*/
		function reloadTheScheduleArray( chosenDate )
		{			
			var formattedDate = "";
			formattedDate = formatDate(chosenDate);					
			TheCurrentdateoftheschedule = chosenDate; //Declare That the Schedule is this date.		
			DisplayDate = "Date:"+formattedDate+"";
			$('#DateBox p').empty().append(	DisplayDate	); //Replace datebox date with chosenDate					
			
			/**
			 * Make Ajax call. Ask for the schedule array for the date of 'chosenDate'.
			 * On return from Ajax call, display the new schedule.
			 */
			var inputDate = {};
				inputDate['GivenDate'] = chosenDate;				
			$.post('<?= base_Url(); ?>index.php/CreateAShift/reloadTheScheduleArraybyGivenDate', inputDate, function(data)
			{	
				var TheScheduleArray  = JSON.parse( data );
				DisplaySchedule(TheScheduleArray); 
			});		
		}/* End of Function reloadTheScheduleArray */

	});			
</script>



<div id = "SchedulePage">

	<div id = "SchedulePageUpper">	
		<div id = "SchedulePageUpperRow">
			<div id = "GoToToday"><p>Go To Today</p></div>			
			<form name = "SelectDate" id = "SelectDate" >
				<p id = "SelectScheduleDate">Select Schedule Date</p>
				<select name = "Year" id = "Year" >			
				</select>				
				<select name = "month" id = "Month" >
				</select>
				<select name = "day" id = "Day" >
				</select>				
				<input type = "button" id = "GoToDate" value = "Go">
			</form>			
		</div>

		<div id = "SchedulePageBottomRow"> 
			<div id = "GoBackADay"><img src = "<?= assetUrl(); ?>img/arrows/CheapLeftArrow.jpg" /><p>Go back a day.</p></div> 
			<div id = "DateBox"><p></p></div>
			<div id = "GoForwardADay"> <p>Go Forward a day.</p><img src = "<?= assetUrl(); ?>img/arrows/CheapRightArrow.jpg" /></div> 
		</div>	
	</div>

	<div id = "ContainerSchedule">
		<table id = "schedule" cellpadding = "0" cellspacing = "0" border = "0">			
			<colgroup>
				<col class = "TopNamesBar"/>
				<col class = "BackgroundSchedule" span = ""/> 
			</colgroup>												
		</table>
	</div>
	<div id = "Print"> </div>
	
</div><!--End of schedule page. -->