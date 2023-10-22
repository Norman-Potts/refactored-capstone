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

	var TodayScheduleArray = JSON.parse( '<?php echo $TodaysScheduleArray; ?>'	);
	var SelectArray = JSON.parse(	'<?php echo $SelectArray; ?>'	);
	var CurrentYear = '<?php echo $CurrentYear; ?>'; // holds the current year in 2017 format.
	var CurrentMonth = '<?php echo $CurrentMonth; ?>'; // holds the current month in 09 format
	var CurrentDay = '<?php echo $CurrentDay; ?>'; //holds the current day in 21 format	
	var ListofDates = 	JSON.parse( 	'<?php echo $LISTofDATES ?>' 	);

	
	$(document).ready(function() {			
		/**
		 * On Load...
		 * Set display date and display the scheule for today.
         */	
		var DisplayDate = "Date: <?php echo $Date; ?>";
		$('#DeleteAShiftDateBox p').append(	DisplayDate	);
		DisplaySchedule( TodayScheduleArray, true ); //Display the schedule with current schedule of today.							

		
		/**
		 * Populate Year and Month select boxes based on the SelectArray array.
         * For each Year in SelectArray 
		 */
		for ( var Year in SelectArray ) 
		{
			/* Append an option box into the year select element. */
			$('#Year').append($('<option>', { value: Year, text: Year }));
			/*For each month in this year of ListofDates */
			for ( var Month in SelectArray[Year])
			{							
				if (  Year == CurrentYear )
				{
					/* Append an option box into the month select element. */
					$('#Month').append($('<option>', { value: Month, text: Month }));
				}
			}				
		}		
		
		
		/* Populate Day Select Options with current month */
		var MaxDaysInMonth = SelectArray[CurrentYear][CurrentMonth];
		for ( var i = 1; i <= MaxDaysInMonth; i++)
		{
			/* Append an option element into the day select element. */
			$('#Day').append($('<option>', { value: i, text: i }));
		}		
		
		
		/*	Set Default values for list boxes. */				
		$('#Year').val(CurrentYear); //Set Year to This Year|		
		$('#Month').val(CurrentMonth); //Set Month to This Month		
		$('#Day').val(CurrentDay); //Set to CurrentDay

		
		/** Function reloadTheScheduleArray
				Purpose: Reloads the schedule array with the date requested and displays the schedule.		
				Parameters: 'selectDate' is the date of the schedule requested to be loaded.
		*/
		function reloadTheScheduleArray( selectDate )
		{		
			var formattedDate = "";
			formattedDate = formatDate(selectDate);	//Format the selectDate so it can be displayed.
			TheCurrentdateoftheschedule = selectDate; //Declare That the Schedule is this date.						
			DisplayDate = "Date:"+formattedDate+"";
			$('#DeleteAShiftDateBox p').empty().append(	DisplayDate	);//Replace datebox date with DisplayDate.			
			/**
			 * Make an Ajax call to reload the schedule with the givenDate.
			 */
			var inputDate = {};
				inputDate['GivenDate'] = selectDate;				
			$.post('<?= base_Url(); ?>index.php/DeleteAShift/reloadTheScheduleArraybyGivenDate', inputDate, function(data)
			{	
				var TheScheduleArray  = JSON.parse( data );
				DisplaySchedule(TheScheduleArray, true); 
			});
		}/* End of Function reloadTheScheduleArray */

		
		/*When Year gets changed repopulate days. */
		$('#Year').change(function(){								
			var ChoosenMonth = '';
			$( "#Month option:selected" ).each(function() {					/* Get selected Month */
				ChoosenMonth += $( this ).text() + '';
			});			
			var ChosenYear			= '';
			$( "#Year option:selected" ).each(function() {  				/* Get selected year.. */
				ChosenYear += $( this ).text() + '';
			});
			$('#Day').empty();												//Empty SelectBoxes 			
			var MaxDaysInMonth = ListofDates[ChosenYear][ChoosenMonth]; 	//Now repopulate days 
			for ( var i = 1; i <= MaxDaysInMonth; i++)
			{
				/* Append an option element into the day select element. */
				$('#Day').append($('<option>', { value: i, text: i }));
			}
		});/*End of When year get changed */


		
		
		/*When Month Gets changed repopulate days */
		$('#Month').change(function(){
			var ChoosenMonth = '';
			$( "#Month option:selected" ).each(function() { 				/* Get selected Month */ 
				ChoosenMonth += $( this ).text() + '';
			});	
			var ChosenYear			= '';
			$( "#Year option:selected" ).each(function() {					/* Get Selected Year */
				ChosenYear += $( this ).text() + '';
			});			
			$('#Day').empty();												/* Empty SelectBoxes  */			
			var MaxDaysInMonth = ListofDates[ChosenYear][ChoosenMonth];		/* now repopulate days*/
			for ( var i = 1; i <= MaxDaysInMonth; i++)
			{
				/* Append an option element into the day select element. */
				$('#Day').append($('<option>', { value: i, text: i }));
			}						
		});/*When Month gets changed */	

		
		
		
		/* When Button gets clicked */
		$('#GoToDate').click(function(){
			/* Load select dates to controller and reload the page and schedule. */				
			var selectDate = getdateFromSelect();
			reloadTheScheduleArray(selectDate);				
		});
		
	});		
</script>
<div id = "DeleteAShiftpage">
	
	<div id = "DeleteAShiftPageTOP">
		<h1>Delete a Shift <div id = "Print"></div></h1>
		<p>
			Step 1 <br>
				<span class = "TabRight"> Choose a schedule date.</span> <br>
			Step 2 <br>
				<span class = "TabRight">  Select on the shift you wish to delete.</span> <br>
			Step 3 <br>
				<span class = "TabRight"> Confirm that the shift you want to delete is </span> <br>
				<span class = "TabRight"> in the grey box below.</span> <br>
			Step 4 <br>
				<span class  = "TabRight"> Click Delete when you are sure that is the shift</span> <br>
				<span class = "TabRight"> you wish to delete.</span> <br>
		</p>				
		<div id = "BoxOfDelete">
		</div>
	</div> 

	<div id = "DeleteAShiftPageBOTTOM">	
		
		<form name = "DELETESelectDate" id = "DELETESelectDate" >
		<p>Select Shift Date</p>
			<select name = "Year" id = "Year" >
			</select>
			<select name = "month" id = "Month" >
			</select>
			<select name = "day" id = "Day" >
			</select>	
			<input type = "button" id = "GoToDate" value = "Go">
		</form>		

		<div id = "DeleteAShiftDateBox">
			<p>	</p>
		</div>
		<div id = "ScheduleTable" >
			<table id = "schedule" cellpadding = "0" cellspacing = "0" border = "0" >								
			</table>
		</div><!-- End of schedule table -->
		
	</div>

</div><!--End of id DeleteAShiftpage -->