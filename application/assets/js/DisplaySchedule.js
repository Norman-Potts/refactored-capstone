		/**	
			Function DisplaySchedule
			
			Purpose: Displays the schedule as a html table.
					 Reads the ScheduleArrary to produce the html table.
					 If told to it will display a link on each shift that allows the
					 supervisor to select a shift.
					
			@paragam ScheduleArrary is an array that holds all the empoyees who have shifts
					 on the given day.
			
			@paragam DoyouWantSelectShift is a true or false value. If it is true than the 
					 select shift button will be displayed. if it is false it wont be displayed.
			
		*/
		function DisplaySchedule(	ScheduleArrary, DoyouWantSelectShift)
		{						
			/*	mysqlTimeArray: an array to hold every half hour in mysql time format. */
			var mysqlTimeArray =  [ "00:00:00", "00:30:00", "01:00:00", "01:30:00", "02:00:00", "02:30:00", "03:00:00", "03:30:00", "04:00:00", "04:30:00", "05:00:00", "05:30:00", "06:00:00", "06:30:00", "07:00:00", "07:30:00", "08:00:00", "08:30:00", "09:00:00", "09:30:00", "10:00:00", "10:30:00", "11:00:00", "11:30:00", "12:00:00", "12:30:00", "13:00:00", "13:30:00", "14:00:00", "14:30:00", "15:00:00", "15:30:00", "16:00:00", "16:30:00", "17:00:00", "17:30:00", "18:00:00", "18:30:00", "19:00:00", "19:30:00", "20:00:00", "20:30:00", "21:00:00", "21:30:00", "22:00:00", "22:30:00", "23:00:00", "23:30:00" ];						
			/* AMPM12HourArray: an array to hold every am/pm time format for displaying the time on the table. */
			var AMPM12HourArray = [ "12:00am", "12:30am", "1:00am", "1:30am", "2:00am", "2:30am", "3:00am", "3:30am", "4:00am", "4:30am", "5:00am", "5:30am", "6:00am", "6:30am", "7:00am", "7:30am", "8:00am", "8:30am", "9:00am", "9:30am", "10:00am", "10:30am", "11:00am", "11:30am", "12:00pm", "12:30pm", "1:00pm", "1:30pm", "2:00pm", "2:30pm", "3:00pm", "3:30pm", "4:00pm", "4:30pm", "5:00pm", "5:30pm", "6:00pm", "6:30pm", "7:00pm", "7:30pm", "8:00pm", "8:30pm", "9:00pm", "9:30pm", "10:00pm", "10:30pm", "11:00pm", "11:30pm" ];			
			/* numberofpeopleworking: Counts the number of people working. */
			var numberofpeopleworking = ScheduleArrary.length; 
			
			$('#schedule').empty(); //Clear the schedule, so a new schedule can get printed.						
			/**
			 * 	Start developing the table as a string. 
			 */
			var TableMsg =	"<colgroup>";
				TableMsg +=	"<col class = \"TopNamesBar\"/>";
				TableMsg +=	"<col class = \"BackgroundSchedule\" span = "+numberofpeopleworking+"/>";
				TableMsg += "</colgroup>";					
			TableMsg += "<thead><tr><th><br><!--Corner of schedule -\-></th>"; // Print the corner of the top row.			
			/** 	
			 *  For each employee in ScheduleArray
			 *	Print the name in the top header row.	
			 */
			for( 	var Employee in ScheduleArrary   ) 
			{				
				var Firstname = ScheduleArrary[Employee]["Firstname"];
				var Lastname  = ScheduleArrary[Employee]["Lastname"];
				TableMsg += "<th class = \"TopNamesBar\">"+Firstname+"<br>"+Lastname+"</th>";
			}			
		    TableMsg += "</tr></thead>"; //End Top row.
			TableMsg += "<tbody>"; // Starts the table body with the <tbody> element.
			
			
			
			
			/** How Schedule gets built!!!
			 *		Schedule gets build as a table. The table get built one TD at a time, left to 
			 *      right. The first for loop below loops 0 to 47. This is 48 iterations. The second
             *  	for loop runs for every employee. 
			 *      Within these loops is a decsion structure that decides what the TD element 
			 *		should be classified with. TD elements can ethier be a time block, starting 
			 *      shift block, middle shift block, end shift block, or empty (no shift at that 
			 *      time for this employee). The starting shift has the most possible types. It can
			 *      have select shift link displayed if it is on the delete page. It can be starting
			 *      and ending if it is only 30 minutes long.
			 *      
			 */			
			for( halfhour = 0; halfhour <= 47; halfhour++ )
			{				
				/**
				 * Begin developing a row. One row for every 48 halfhours.
				 * The first td to be developed is the time one. 
				 * This is done using the AMPM12HourArray.
				 */
				TableMsg += "<tr>";
				TableMsg += "<td class = \"time\">"+AMPM12HourArray[halfhour]+"</td>";	
				
				/**	
				 *	For each employee in ScheduleArrary
				 *		Decide what class the TD elemement gets at his halfhour time.				 
				 */
				for( 	var Employee in ScheduleArrary   ) 
				{					
					/**	
					 *	On this halfhour and on this employee.
					 *		Is there a shift TD element that should be printed?
					 *		Or is this TD an empty cell.					
					 */
					ThereIsAShiftforThisCell = false; // Assuming this cell doesn't have a shift to print.
					for( var shift in ScheduleArrary[Employee]["Shifts"])
					{							
						var ST = ScheduleArrary[Employee]["Shifts"][shift]["startTime"];
						var ET =  ScheduleArrary[Employee]["Shifts"][shift]["endTime"];						
						/* Convert StartTime and endTime to 48halhour formatt. */
						var ST_inHalfhourformat = mysqlTimeArray.indexOf(ST); //Equals the index of mysqlTimeArray
						var ET_inHalfhourformat = mysqlTimeArray.indexOf(ET); //Equals the index of mysqlTimeArray
						/**
						 *	Is there a shift at this time?
						 */
						if ( halfhour < ET_inHalfhourformat && halfhour >= ST_inHalfhourformat )
						{	ThereIsAShiftforThisCell = true;	}

					}/* End of for each shift in ScheduleArrary[Employee] */

			
					
					/**
     				 *	If this cell does have a shift block...
					 *		then determine what should be printed in this cell.
					 */
					if ( ThereIsAShiftforThisCell == true)
					{
						/** At this point what do we know?
						 *  	We know there is a shift in the cell.
						 * 		We know the "halfhour" time.
						 *		We know the "Employee".
						 */
						/** What can the td cell still be? It can be...
						 *  1. Shift Starting
						 *  2. Shift Starting and Ending after 30 Minutes.
						 *  3. Select Shift Starting
						 *  4. Select Shift Starting and Ending after 30 Minutes.
						 *  5. Middle of Shift
						 *  6. End of Shift
						 */
						/** For all td cell types what to we need to know?
						 *  Need to know Position.
						 *	Need to know if it is start
						 *	Need to know if it is start and end.
						 *	need to know if it is middle.
						 *	need to know if it is end.
						 */
						 
						 
						 
						/* Determine position of shift. */													
						var positionType; //classofShift, holds the CSS class for the color of the position
						var Position = ""; // Will hold the Position of the shift in this td.	
						var EndFound = false;
						var startfound = false;
						var startTime;
						var endTime;
						var shiftid;
						for( var shift in ScheduleArrary[Employee]["Shifts"])
						{	
							/* Need to figure out the Position for this employee and time  */
							var ST = ScheduleArrary[Employee]["Shifts"][shift]["startTime"];
							var ET =  ScheduleArrary[Employee]["Shifts"][shift]["endTime"];																				
							var ST_inHalfhourformat = mysqlTimeArray.indexOf(ST); 
							var ET_inHalfhourformat = mysqlTimeArray.indexOf(ET); 			
							ET_inHalfhourformat = ET_inHalfhourformat -1;
							if ( halfhour <= ET_inHalfhourformat && halfhour >= ST_inHalfhourformat )
							{									
								Position = ScheduleArrary[Employee]["Shifts"][shift]["Position"];						
								switch (	Position	)
								{
									case "Lifeguard":
										positionType = "LifeguardShift";
									break;
									
									case "Instructor":  
										positionType = "InstructorShift";
									break;
									
									case "Headguard": 
										positionType = "HeadGuardShift";
									break;
									
									case "Supervisor":
										positionType = "SupervisorShift";
									break;
									
									default:
										positionType == "ShiftTypeErrorColor";
									break;
								}
								
								if ( ST_inHalfhourformat == halfhour )
								{	startfound = true;	}		
								
								if ( ET_inHalfhourformat == halfhour )
								{ 	EndFound = true; }	
								startTime = ScheduleArrary[Employee]["Shifts"][shift]["startTime"];
								endTime = ScheduleArrary[Employee]["Shifts"][shift]["endTime"];;
								shiftid = ScheduleArrary[Employee]["Shifts"][shift]["ShiftID"];;
							}
						}/* End of for each shift */
						
					



					
						
						// If the start of the shift is on this halfhour time.
						if (startfound == true)
						{ 	/*Start of shift*/			
							
							var firstname = ScheduleArrary[Employee]["Firstname"];
							var lastname = ScheduleArrary[Employee]["Lastname"];									
							var TheDate = ScheduleArrary[Employee]["TheDate"];
							var EmployeeID = ScheduleArrary[Employee]["EmployeeID"];
							var displayST = convert24HourTo12hr(startTime);
							var displayET = convert24HourTo12hr(endTime);
							
					
							if (DoyouWantSelectShift == true) 
							{
									
								/**	If End is also at the start time then use STARTandENDshift css. */						
								if(EndFound == true)
								{
									TableMsg += "<td class = \"SmallerText STARTandENDshift "+positionType+" \" > "+Position+" <br> ";
									TableMsg += ""+displayST+" - "+displayET+" <br> <a id = \"SelectShift\" OnClick = \"SelectShift( "+shiftid+", '"+firstname+"', '"+lastname+"', '"+startTime+"', '"+endTime+"', '"+Position+"', '"+TheDate+"', '"+EmployeeID+"' ) \"> Delete Shift</a></td>";
								}
								else
								{
									TableMsg += "<td class = \"SmallerText STARTshift "+positionType+" \" >  "+Position+" <br> ";
									TableMsg += ""+displayST+" - "+displayET+" <br> <a id = \"SelectShift\" OnClick = \"SelectShift(  "+shiftid+", '"+firstname+"', '"+lastname+"', '"+startTime+"', '"+endTime+"', '"+Position+"', '"+TheDate+"', '"+EmployeeID+"' ) \"> Delete Shift</a></td>";
								}
							}
							else
							{	
								/** If End is also at the start time then use STARTandENDshift css. */						
								if(EndFound == true)
								{
									TableMsg += "<td class = \"SmallerText STARTandENDshift "+positionType+" \" >  "+Position+" <br> "+displayST+" - "+displayET+" </td>";
								}
								else
								{
									TableMsg += "<td class = \"SmallerText STARTshift "+positionType+" \" >  "+Position+" <br> "+displayST+" - "+displayET+" </td>";
								}
							}
							
						}else{
							
												
							//if the end of the shift is on this halfhour time.
							if (EndFound == true)
							{
								/* END of shift*/
								TableMsg += "<td class = \" ENDshift "+positionType+"\" ></td>";
							}
							else
							{
								/* Middle of shift */
								TableMsg += "<td class = \" MIDDLEshift "+positionType+"\" ></td>";		
								
							}//End of if else EndFound.
						
						}// End of if else startfound == true.
						
					}
					else 
					{	//This td cell doesn't have a shift
						TableMsg += "<td class = \"NoShiftTD\"></td>";
					}/* End of If else ThereIsAShiftforThisCell == true */

					
				}/*End of For each Employee loop */	
				TableMsg += "</tr>";//Close row.
			}/* End of 48 hour loop */
			
			TableMsg += "</tbody>";
			$('#schedule').append(TableMsg);
		}// End of DisplaySchedule function

		
		
		
		
		
		
		
		
		
		
		/** Function SelectShift 
				Purpose: Displays the Select shift div with the appropriate information. This div 
				         allows the user to confirm they want to delete a selected shift.
				Parameters: 
					shiftid, The shift ID of the selected Shift.
					firstname,
					lastname,
					startTime, 
					endTime,
					Ps, The position of the shift in string form.
					TheDate,
					EmployeeID
		*/
		function SelectShift( shiftid, firstname, lastname, startTime, endTime, Ps, TheDate, EmployeeID)
		{
			var EmployeeName = ""+firstname+" "+lastname+"";
			var time = ""+startTime+" - "+endTime+"";
			var Position = Ps;									
			var employeeID;
			
			$('#BoxOfDelete').empty();						
			var MSG = "";			
			MSG += "<div id = \"SelectedDelete\"><p>Are you sure you want to delete this Shift?</p>";
			MSG += "<p> Employee: "+EmployeeName+"<br>Time: "+time+"<br>Position: "+Position+"<br></p>";
			MSG += "<p>Shift ID: "+shiftid+"</p>";
			MSG += "<p id = \"CancelDelete\" OnClick = \"CANCELDelete()\"  >Cancel</p>";
			MSG += "<p id = \"DeleteShiftLink\" OnClick = \"DeleteThisShift( "+shiftid+", '"+EmployeeID+"', '"+startTime+"', '"+endTime+"', '"+TheDate+"' )\"  >Delete</p></div>";
			
			$('#BoxOfDelete').append(MSG);
		}/* End function SelectShift */
		
		
		
		
		
		
		
		/** Function CANCELDelete
			Purpose: Allows user to cancel the deletion of a shift if they change there mind.
					 emptys the box of delete.
		*/
		function CANCELDelete()
		{	
			$('#BoxOfDelete').empty();
		}/* End of Function CANCELDelete */
		
		
		
		
		
		/** Function DeleteThisShift
				Purpose: Deletes a shift from the table.
		*/
		function DeleteThisShift( shiftid, EmployeeID, StartTime, EndTime, TheDate)
		{
			/** 
			 * Call php to delete shift then reload the page with the date that it is sent to.		
			 */
			var inputarr = {};
				inputarr['startTime'] = StartTime;
				inputarr['endTime'] = EndTime;
				inputarr['EmployeeID'] = EmployeeID;
				inputarr['TheDate'] = TheDate;
				inputarr['shiftid'] = shiftid;					
			$.post('http://localhost/final-capstone-code/CapStone/index.php/DeleteAShift/DeleteTheShift', inputarr, function(data)
			{	
				/* Determine If delete was succesful, and reload the page. */ 
				$('#BoxOfDelete').empty();					
				//Need to reload $TheScheduleArray Variable.
				var inputDate = {};
				inputDate['GivenDate'] = TheDate;			
				$.post('http://localhost/final-capstone-code/CapStone/index.php/CreateAShift/reloadTheScheduleArraybyGivenDate', inputDate, function(data)
				{	
					var TheScheduleArray  = JSON.parse( data );
					DisplaySchedule(TheScheduleArray, true); 			
				});						
			});							
		}/* End of Function DeleteThisShift */
		 