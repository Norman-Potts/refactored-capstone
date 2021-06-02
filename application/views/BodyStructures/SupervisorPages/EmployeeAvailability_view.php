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
	
			$('#DELETEEmployeeListInner').on('mouseenter', '.DELETEEmployeeCell', function(){																			
				$( this ).removeClass("DELETEnotHover");
				$( this ).addClass("DELETEliOVER");	
			}).on('mouseleave', '.DELETEEmployeeCell', function() {
				$( this ).removeClass("DELETEliOVER");
				$( this ).addClass("DELETEnotHover");				
			});		
			
			/* When a Employee Cell gets clicked gets clicked */						
			$('#DELETEEmployeeListInner').on('click', '.DELETEEmployeeCell', function(){															
				var ID = $(this).find(".HideEmployeeID").text(); //Get the ID of this row.
				var idnum = parseInt(ID);								
				loadForm(	idnum	);//load form with the employee with this ID.				
			});/*End of on click of employee row.*/
				
		
		/*TODO: need to make jquery rule so that when ANOT is click on a day all the
		checkboxes are clear except ANOT only for that day.*/	

		//On Load populate the list with employees
		var EmployeeList = "";
		var EmList = JSON.parse('<?php echo $EmList; ?>');
		for( var row in EmList )
		{
			
			
			EmployeeList += "<div class = \"DELETEEmployeeCell DELETEnotHover\">";
			var firstname = EmList[row]['Firstname'];
			var lastname = EmList[row]['Lastname'];
			var EID = EmList[row]['employeeID'];
			EmployeeList += "	<span class = \"MakeLeft\" > "+firstname+" "+lastname+" </span><span class =\"HideEmployeeID\">"+EID+"</span>";
			EmployeeList += "</div>";		
			

		}
		$('#DELETEEmployeeListInner').empty().append(EmployeeList);
		
		
		
		/*When ANOT gets click set its Neighbours to unchecked */
		$('#ANOT').change(function(){

			$('#ANOT').siblings('.A1');
			var stORsu = $(":parent").siblings('h3').val();
			
			//If the neighboor h3 value is Saturday or sunday
			if ( stORsu == "Sunday" || stORsu == "Saturday" ) {				
				$(this).siblings('.A1').prop('checked', false);								
				$(this).siblings('.A2').prop('checked', false);								
				$(this).siblings('.A3').prop('checked', false);								
				$(this).siblings('.A4').prop('checked', false);																																	
				$(this).siblings('.Anytime').prop('checked', false);
								
			} else {
				$(this).siblings('.A1').prop('checked', false);								
				$(this).siblings('.A2').prop('checked', false);								
				$(this).siblings('.A3').prop('checked', false);								
				$(this).siblings('.A4').prop('checked', false);								
				$(this).siblings('.A5').prop('checked', false);								
				$(this).siblings('.A6').prop('checked', false);								
				$(this).siblings('.A7').prop('checked', false);											
				$(this).siblings('.Anytime').prop('checked', false);
			}
		});
		
		
		
		
		/**
		*/
		function loadForm(ID)
		{	
			$('#innerAvailabilityForm').css({'display': "none"});	
			for( var row in EmList )
			{
				if( EmList[row]['employeeID'] == ID)
				{
					var firstname = EmList[row]['Firstname'];
					var lastname = EmList[row]['Lastname'];
					var EID = EmList[row]['employeeID'];
					var lifeguard = "";var instructor = "";var headguard = "";var supervisor = "";
					if(EmList[row]['Lifeguard'] == true){ lifeguard = "Yes";}else{lifeguard = "No";}
					if(EmList[row]['Instructor'] == true){ instructor = "Yes";}else{instructor = "No";}
					if(EmList[row]['Headguard'] == true){ headguard = "Yes";}else{headguard = "No";}
					if(EmList[row]['Supervisor'] == true){ supervisor = "Yes";}else{supervisor = "No";}
					
					$('#Anotes').val("Write extra notes about this employees availability here.");
					$('#EmployeeCertifications').empty().append("Employee Certifications:  LG: "+lifeguard+"  INST: "+instructor+"  HG: "+headguard+"  Sup: "+supervisor+"");
					$('#appendEmployeeName').empty().append(" "+firstname+" "+lastname+" ");
					$('#SshiftID').empty().append(EID);
					
					//Clear check boxes
					//Set up Monday...								
									$('#MondayAvailability .A1').prop('checked', false);								
									$('#MondayAvailability .A2').prop('checked', false);								
									$('#MondayAvailability .A3').prop('checked', false);								
									$('#MondayAvailability .A4').prop('checked', false);								
									$('#MondayAvailability .A5').prop('checked', false);								
									$('#MondayAvailability .A6').prop('checked', false);						
									$('#MondayAvailability .A7').prop('checked', false);								
									$('#MondayAvailability .ANOT').prop('checked', false);								
									$('#MondayAvailability .Anytime').prop('checked', false);								
								//Set up Tuesday...							
									$('#TuesdayAvailability .A1').prop('checked', false);								
									$('#TuesdayAvailability .A2').prop('checked', false);								
									$('#TuesdayAvailability .A3').prop('checked', false);								
									$('#TuesdayAvailability .A4').prop('checked', false);							
									$('#TuesdayAvailability .A5').prop('checked', false);								
									$('#TuesdayAvailability .A6').prop('checked', false);								
									$('#TuesdayAvailability .A7').prop('checked', false);							
									$('#TuesdayAvailability .ANOT').prop('checked', false);								
									$('#TuesdayAvailability .Anytime').prop('checked', false);						
								//Set up Wednesday...								
									$('#WednesdayAvailability .A1').prop('checked', false);								
									$('#WednesdayAvailability .A2').prop('checked', false);								
									$('#WednesdayAvailability .A3').prop('checked', false);								
									$('#WednesdayAvailability .A4').prop('checked', false);								
									$('#WednesdayAvailability .A5').prop('checked', false);								
									$('#WednesdayAvailability .A6').prop('checked', false);								
									$('#WednesdayAvailability .A7').prop('checked', false);								
									$('#WednesdayAvailability .ANOT').prop('checked', false);								
									$('#WednesdayAvailability .Anytime').prop('checked', false);												
								//Set up Thrusday...								
									$('#ThrusdayAvailability .A1').prop('checked', false);								
									$('#ThrusdayAvailability .A2').prop('checked', false);								
									$('#ThrusdayAvailability .A3').prop('checked', false);								
									$('#ThrusdayAvailability .A4').prop('checked', false);								
									$('#ThrusdayAvailability .A5').prop('checked', false);								
									$('#ThrusdayAvailability .A6').prop('checked', false);								
									$('#ThrusdayAvailability .A7').prop('checked', false);								
									$('#ThrusdayAvailability .ANOT').prop('checked', false);								
									$('#ThrusdayAvailability .Anytime').prop('checked', false);						
								//Set up Friday...							
									$('#FridayAvailability .A1').prop('checked', false);								
									$('#FridayAvailability .A2').prop('checked', false);								
									$('#FridayAvailability .A3').prop('checked', false);								
									$('#FridayAvailability .A4').prop('checked', false);								
									$('#FridayAvailability .A5').prop('checked', false);								
									$('#FridayAvailability .A6').prop('checked', false);								
									$('#FridayAvailability .A7').prop('checked', false);								
									$('#FridayAvailability .ANOT').prop('checked', false);								
									$('#FridayAvailability .Anytime').prop('checked', false);						
								//Set up Saturday...								
									$('#SaturdayAvailability .A1').prop('checked', false);								
									$('#SaturdayAvailability .A2').prop('checked', false);								
									$('#SaturdayAvailability .A3').prop('checked', false);								
									$('#SaturdayAvailability .A4').prop('checked', false);								
									$('#SaturdayAvailability .ANOT').prop('checked', false);								
									$('#SaturdayAvailability .Anytime').prop('checked', false);							
								//Set up Sunday...								
									$('#SundayAvailability .A1').prop('checked', false);								
									$('#SundayAvailability .A2').prop('checked', false);								
									$('#SundayAvailability .A3').prop('checked', false);								
									$('#SundayAvailability .A4').prop('checked', false);								
									$('#SundayAvailability .ANOT').prop('checked', false);								
									$('#SundayAvailability .Anytime').prop('checked', false);
					
					
					
					
					//Get this employees availability...
						var inputarr= {};
						inputarr['ID'] = EID;												
						$.post('<?= base_Url(); ?>index.php/EmployeeAvailability/GetAvailability', inputarr, function(data)
						{	
							var Availability = JSON.parse( data );		
							//$('#Print').append(data);
																																								
							//Determine if the Availability has been set. then fill the form.
							if( Availability == "Nothing yet" )
							{	//Set everytjing to Anytime.
								$('#MondayAvailability .Anytime').prop('checked', true);
								$('#TuesdayAvailability .Anytime').prop('checked', true);
								$('#WednesdayAvailability .Anytime').prop('checked', true);
								$('#ThrusdayAvailability .Anytime').prop('checked', true);
								$('#FridayAvailability .Anytime').prop('checked', true);
								$('#SaturdayAvailability .Anytime').prop('checked', true);
								$('#SundayAvailability .Anytime').prop('checked', true);
							}
							else
							{
								//Set up Monday...
								if(Availability["Mondays"]["A1"] == true)
									$('#MondayAvailability .A1').prop('checked', true);
								if(Availability["Mondays"]["A2"] == true)
									$('#MondayAvailability .A2').prop('checked', true);
								if(Availability["Mondays"]["A3"] == true)
									$('#MondayAvailability .A3').prop('checked', true);
								if(Availability["Mondays"]["A4"] == true)
									$('#MondayAvailability .A4').prop('checked', true);
								if(Availability["Mondays"]["A5"] == true)
									$('#MondayAvailability .A5').prop('checked', true);
								if(Availability["Mondays"]["A6"] == true)
									$('#MondayAvailability .A6').prop('checked', true);
								if(Availability["Mondays"]["A7"] == true)
									$('#MondayAvailability .A7').prop('checked', true);
								if(Availability["Mondays"]["ANOT"] == true)
									$('#MondayAvailability .ANOT').prop('checked', true);
								if(Availability["Mondays"]["Anytime"] == true)
									$('#MondayAvailability .Anytime').prop('checked', true);
								
								//Set up Tuesday...
								if(Availability["Tuesday"]["A1"] == true)
									$('#TuesdayAvailability .A1').prop('checked', true);
								if(Availability["Tuesday"]["A2"] == true)
									$('#TuesdayAvailability .A2').prop('checked', true);
								if(Availability["Tuesday"]["A3"] == true)
									$('#TuesdayAvailability .A3').prop('checked', true);
								if(Availability["Tuesday"]["A4"] == true)
									$('#TuesdayAvailability .A4').prop('checked', true);
								if(Availability["Tuesday"]["A5"] == true)
									$('#TuesdayAvailability .A5').prop('checked', true);
								if(Availability["Tuesday"]["A6"] == true)
									$('#TuesdayAvailability .A6').prop('checked', true);
								if(Availability["Tuesday"]["A7"] == true)
									$('#TuesdayAvailability .A7').prop('checked', true);
								if(Availability["Tuesday"]["ANOT"] == true)
									$('#TuesdayAvailability .ANOT').prop('checked', true);
								if(Availability["Tuesday"]["Anytime"] == true)
									$('#TuesdayAvailability .Anytime').prop('checked', true);
						
								//Set up Wednesday...
								if(Availability["Wednesday"]["A1"] == true)
									$('#WednesdayAvailability .A1').prop('checked', true);
								if(Availability["Wednesday"]["A2"] == true)
									$('#WednesdayAvailability .A2').prop('checked', true);
								if(Availability["Wednesday"]["A3"] == true)
									$('#WednesdayAvailability .A3').prop('checked', true);
								if(Availability["Wednesday"]["A4"] == true)
									$('#WednesdayAvailability .A4').prop('checked', true);
								if(Availability["Wednesday"]["A5"] == true)
									$('#WednesdayAvailability .A5').prop('checked', true);
								if(Availability["Wednesday"]["A6"] == true)
									$('#WednesdayAvailability .A6').prop('checked', true);
								if(Availability["Wednesday"]["A7"] == true)
									$('#WednesdayAvailability .A7').prop('checked', true);
								if(Availability["Wednesday"]["ANOT"] == true)
									$('#WednesdayAvailability .ANOT').prop('checked', true);
								if(Availability["Wednesday"]["Anytime"] == true)
									$('#WednesdayAvailability .Anytime').prop('checked', true);
						
								
								//Set up Thrusday...
								if(Availability["Thrusday"]["A1"] == true)
									$('#ThrusdayAvailability .A1').prop('checked', true);
								if(Availability["Thrusday"]["A2"] == true)
									$('#ThrusdayAvailability .A2').prop('checked', true);
								if(Availability["Thrusday"]["A3"] == true)
									$('#ThrusdayAvailability .A3').prop('checked', true);
								if(Availability["Thrusday"]["A4"] == true)
									$('#ThrusdayAvailability .A4').prop('checked', true);
								if(Availability["Thrusday"]["A5"] == true)
									$('#ThrusdayAvailability .A5').prop('checked', true);
								if(Availability["Thrusday"]["A6"] == true)
									$('#ThrusdayAvailability .A6').prop('checked', true);
								if(Availability["Thrusday"]["A7"] == true)
									$('#ThrusdayAvailability .A7').prop('checked', true);
								if(Availability["Thrusday"]["ANOT"] == true)
									$('#ThrusdayAvailability .ANOT').prop('checked', true);
								if(Availability["Thrusday"]["Anytime"] == true)
									$('#ThrusdayAvailability .Anytime').prop('checked', true);
						
								//Set up Friday...
								if(Availability["Friday"]["A1"] == true)
									$('#FridayAvailability .A1').prop('checked', true);
								if(Availability["Friday"]["A2"] == true)
									$('#FridayAvailability .A2').prop('checked', true);
								if(Availability["Friday"]["A3"] == true)
									$('#FridayAvailability .A3').prop('checked', true);
								if(Availability["Friday"]["A4"] == true)
									$('#FridayAvailability .A4').prop('checked', true);
								if(Availability["Friday"]["A5"] == true)
									$('#FridayAvailability .A5').prop('checked', true);
								if(Availability["Friday"]["A6"] == true)
									$('#FridayAvailability .A6').prop('checked', true);
								if(Availability["Friday"]["A7"] == true)
									$('#FridayAvailability .A7').prop('checked', true);
								if(Availability["Friday"]["ANOT"] == true)
									$('#FridayAvailability .ANOT').prop('checked', true);
								if(Availability["Friday"]["Anytime"] == true)
									$('#FridayAvailability .Anytime').prop('checked', true);
						
								//Set up Saturday...
								if(Availability["Saturday"]["A1"] == true)
									$('#SaturdayAvailability .A1').prop('checked', true);
								if(Availability["Saturday"]["A2"] == true)
									$('#SaturdayAvailability .A2').prop('checked', true);
								if(Availability["Saturday"]["A3"] == true)
									$('#SaturdayAvailability .A3').prop('checked', true);
								if(Availability["Saturday"]["A4"] == true)
									$('#SaturdayAvailability .A4').prop('checked', true);
								if(Availability["Saturday"]["ANOT"] == true)
									$('#SaturdayAvailability .ANOT').prop('checked', true);
								if(Availability["Saturday"]["Anytime"] == true)
									$('#SaturdayAvailability .Anytime').prop('checked', true);
							
								//Set up Sunday...
								if(Availability["Sundays"]["A1"] == true)
									$('#SundayAvailability .A1').prop('checked', true);
								if(Availability["Sundays"]["A2"] == true)
									$('#SundayAvailability .A2').prop('checked', true);
								if(Availability["Sundays"]["A3"] == true)
									$('#SundayAvailability .A3').prop('checked', true);
								if(Availability["Sundays"]["A4"] == true)
									$('#SundayAvailability .A4').prop('checked', true);
								if(Availability["Sundays"]["ANOT"] == true)
									$('#SundayAvailability .ANOT').prop('checked', true);
								if(Availability["Sundays"]["Anytime"] == true)
									$('#SundayAvailability .Anytime').prop('checked', true);
						
						
						
								var notes = Availability["Notes"];
								$('#Anotes').val(notes);
							}
							$('#innerAvailabilityForm').css({'display': "block"});																											
							
						});					
				}
			}
		}
		
		$("#SumitAvailiability").click(function(){
			//Get values from form.
			var AvailabilityArr = {};		
			var dayAvailability = {};
			
			
			//Monday Availability
			var MA1 = $('#MondayAvailability .A1').is( ":checked" );
			var MA2 = $('#MondayAvailability .A2').is( ":checked" );
			var MA3 = $('#MondayAvailability .A3').is( ":checked" );
			var MA4 = $('#MondayAvailability .A4').is( ":checked" );
			var MA5 = $('#MondayAvailability .A5').is( ":checked" );
			var MA6 = $('#MondayAvailability .A6').is( ":checked" );
			var MA7 = $('#MondayAvailability .A7').is( ":checked" );
			var MANOT = $('#MondayAvailability .ANOT').is( ":checked" );
			var MAnytime = $('#MondayAvailability .Anytime').is( ":checked" );		
				dayAvailability = {};									
					if (	MANOT	)
					{
						dayAvailability["A1"] = false;
						dayAvailability["A2"] = false;
						dayAvailability["A3"] = false;
						dayAvailability["A4"] = false;
						dayAvailability["A5"] = false;
						dayAvailability["A7"] = false;
						dayAvailability["A6"] = false;
						dayAvailability["ANOT"] = true;
						dayAvailability["Anytime"] = false;
					}
					else if ( MAnytime	)
					{
						dayAvailability["A1"] = true;
						dayAvailability["A2"] = true;
						dayAvailability["A3"] = true;
						dayAvailability["A4"] = true;
						dayAvailability["A5"] = true;
						dayAvailability["A6"] = true;
						dayAvailability["A7"] = true;
						dayAvailability["ANOT"] = false;
						dayAvailability["Anytime"] = true;
						
					}
					else
					{
						dayAvailability["A1"] = MA1;
						dayAvailability["A2"] = MA2;
						dayAvailability["A3"] = MA3;
						dayAvailability["A4"] = MA4;
						dayAvailability["A5"] = MA5;
						dayAvailability["A6"] = MA6;
						dayAvailability["A7"] = MA7;
						dayAvailability["ANOT"] = false;
						dayAvailability["Anytime"] = false;
					
					}
					AvailabilityArr["Mondays"] = dayAvailability;
			
			
			//Tuesday Availability			
			var TUA1 = $('#TuesdayAvailability .A1').is( ":checked" );
			var TUA2 = $('#TuesdayAvailability .A2').is( ":checked" );
			var TUA3 = $('#TuesdayAvailability .A3').is( ":checked" );
			var TUA4 = $('#TuesdayAvailability .A4').is( ":checked" );
			var TUA5 = $('#TuesdayAvailability .A5').is( ":checked" );
			var TUA6 = $('#TuesdayAvailability .A6').is( ":checked" );
			var TUA7 = $('#TuesdayAvailability .A7').is( ":checked" );
			var TUANOT = $('#TuesdayAvailability .ANOT').is( ":checked" );
			var TUAnytime = $('#TuesdayAvailability .Anytime').is( ":checked" );
					dayAvailability = {};						
					if (	TUANOT	)
					{
						dayAvailability["A1"] = false;
						dayAvailability["A2"] = false;
						dayAvailability["A3"] = false;
						dayAvailability["A4"] = false;
						dayAvailability["A5"] = false;
						dayAvailability["A7"] = false;
						dayAvailability["A6"] = false;
						dayAvailability["ANOT"] = true;
						dayAvailability["Anytime"] = false;
					}
					else if ( TUAnytime	)
					{
						dayAvailability["A1"] = true;
						dayAvailability["A2"] = true;
						dayAvailability["A3"] = true;
						dayAvailability["A4"] = true;
						dayAvailability["A5"] = true;
						dayAvailability["A6"] = true;
						dayAvailability["A7"] = true;
						dayAvailability["ANOT"] = false;
						dayAvailability["Anytime"] = true;
						
					}
					else
					{
						dayAvailability["A1"] = TUA1;
						dayAvailability["A2"] = TUA2;
						dayAvailability["A3"] = TUA3;
						dayAvailability["A4"] = TUA4;
						dayAvailability["A5"] = TUA5;
						dayAvailability["A6"] = TUA6;
						dayAvailability["A7"] = TUA7;
						dayAvailability["ANOT"] = false;
						dayAvailability["Anytime"] = false;					
					}								
					AvailabilityArr["Tuesday"] = dayAvailability;
			
						
			//Wednesday Availability
			var WEDA1 = $('#WednesdayAvailability .A1').is( ":checked" );
			var WEDA2 = $('#WednesdayAvailability .A2').is( ":checked" );
			var WEDA3 = $('#WednesdayAvailability .A3').is( ":checked" );
			var WEDA4 = $('#WednesdayAvailability .A4').is( ":checked" );
			var WEDA5 = $('#WednesdayAvailability .A5').is( ":checked" );
			var WEDA6 = $('#WednesdayAvailability .A6').is( ":checked" );
			var WEDA7 = $('#WednesdayAvailability .A7').is( ":checked" );
			var WEDANOT = $('#WednesdayAvailability .ANOT').is( ":checked" );
			var WEDAnytime = $('#WednesdayAvailability .Anytime').is( ":checked" );
				dayAvailability = {};
					if (	WEDANOT	)
					{
						dayAvailability["A1"] = false;
						dayAvailability["A2"] = false;
						dayAvailability["A3"] = false;
						dayAvailability["A4"] = false;
						dayAvailability["A5"] = false;
						dayAvailability["A7"] = false;
						dayAvailability["A6"] = false;
						dayAvailability["ANOT"] = true;
						dayAvailability["Anytime"] = false;
					}
					else if ( WEDAnytime	)
					{
						dayAvailability["A1"] = true;
						dayAvailability["A2"] = true;
						dayAvailability["A3"] = true;
						dayAvailability["A4"] = true;
						dayAvailability["A5"] = true;
						dayAvailability["A6"] = true;
						dayAvailability["A7"] = true;
						dayAvailability["ANOT"] = false;
						dayAvailability["Anytime"] = true;
						
					}
					else
					{
						dayAvailability["A1"] = WEDA1;
						dayAvailability["A2"] = WEDA2;
						dayAvailability["A3"] = WEDA3;
						dayAvailability["A4"] = WEDA4;
						dayAvailability["A5"] = WEDA5;
						dayAvailability["A6"] = WEDA6;
						dayAvailability["A7"] = WEDA7;
						dayAvailability["ANOT"] = false;
						dayAvailability["Anytime"] = false;					
					}				
					AvailabilityArr["Wednesday"] = dayAvailability;
			
						
			//Thrusday Availability
			var THRA1 = $('#ThrusdayAvailability .A1').is( ":checked" );
			var THRA2 = $('#ThrusdayAvailability .A2').is( ":checked" );
			var THRA3 = $('#ThrusdayAvailability .A3').is( ":checked" );
			var THRA4 = $('#ThrusdayAvailability .A4').is( ":checked" );
			var THRA5 = $('#ThrusdayAvailability .A5').is( ":checked" );
			var THRA6 = $('#ThrusdayAvailability .A6').is( ":checked" );
			var THRA7 = $('#ThrusdayAvailability .A7').is( ":checked" );
			var THRANOT = $('#ThrusdayAvailability .ANOT').is( ":checked" );
			var THRAnytime = $('#ThrusdayAvailability .Anytime').is( ":checked" );
				dayAvailability = {};	
					if (	THRANOT	)
					{
						dayAvailability["A1"] = false;
						dayAvailability["A2"] = false;
						dayAvailability["A3"] = false;
						dayAvailability["A4"] = false;
						dayAvailability["A5"] = false;
						dayAvailability["A7"] = false;
						dayAvailability["A6"] = false;
						dayAvailability["ANOT"] = true;
						dayAvailability["Anytime"] = false;
					}
					else if ( THRAnytime	)
					{
						dayAvailability["A1"] = true;
						dayAvailability["A2"] = true;
						dayAvailability["A3"] = true;
						dayAvailability["A4"] = true;
						dayAvailability["A5"] = true;
						dayAvailability["A6"] = true;
						dayAvailability["A7"] = true;
						dayAvailability["ANOT"] = false;
						dayAvailability["Anytime"] = true;
						
					}
					else
					{
						dayAvailability["A1"] = THRA1;
						dayAvailability["A2"] = THRA2;
						dayAvailability["A3"] = THRA3;
						dayAvailability["A4"] = THRA4;
						dayAvailability["A5"] = THRA5;
						dayAvailability["A6"] = THRA6;
						dayAvailability["A7"] = THRA7;
						dayAvailability["ANOT"] = false;
						dayAvailability["Anytime"] = false;					
					}	
					AvailabilityArr["Thrusday"] = dayAvailability;
			
						
			//Friday Availability
			var FRIA1 = $('#FridayAvailability .A1').is( ":checked" );
			var FRIA2 = $('#FridayAvailability .A2').is( ":checked" );
			var FRIA3 = $('#FridayAvailability .A3').is( ":checked" );
			var FRIA4 = $('#FridayAvailability .A4').is( ":checked" );
			var FRIA5 = $('#FridayAvailability .A5').is( ":checked" );
			var FRIA6 = $('#FridayAvailability .A6').is( ":checked" );
			var FRIA7 = $('#FridayAvailability .A7').is( ":checked" );
			var FRIANOT = $('#FridayAvailability .ANOT').is( ":checked" );
			var FRIAAnytime = $('#FridayAvailability .Anytime').is( ":checked" );
				dayAvailability = {};
					if (	FRIANOT	)
					{
						dayAvailability["A1"] = false;
						dayAvailability["A2"] = false;
						dayAvailability["A3"] = false;
						dayAvailability["A4"] = false;
						dayAvailability["A5"] = false;
						dayAvailability["A7"] = false;
						dayAvailability["A6"] = false;
						dayAvailability["ANOT"] = true;
						dayAvailability["Anytime"] = false;
					}
					else if ( FRIAAnytime	)
					{
						dayAvailability["A1"] = true;
						dayAvailability["A2"] = true;
						dayAvailability["A3"] = true;
						dayAvailability["A4"] = true;
						dayAvailability["A5"] = true;
						dayAvailability["A6"] = true;
						dayAvailability["A7"] = true;
						dayAvailability["ANOT"] = false;
						dayAvailability["Anytime"] = true;
						
					}
					else
					{
						dayAvailability["A1"] = FRIA1;
						dayAvailability["A2"] = FRIA2;
						dayAvailability["A3"] = FRIA3;
						dayAvailability["A4"] = FRIA4;
						dayAvailability["A5"] = FRIA5;
						dayAvailability["A6"] = FRIA6;
						dayAvailability["A7"] = FRIA7;
						dayAvailability["ANOT"] = false;
						dayAvailability["Anytime"] = false;					
					}					
					AvailabilityArr["Friday"] = dayAvailability;
			
						
			
			//Saturday Availability
			var SATA1 = $('#SaturdayAvailability .A1').is( ":checked" );
			var SATA2 = $('#SaturdayAvailability .A2').is( ":checked" );
			var SATA3 = $('#SaturdayAvailability .A3').is( ":checked" );
			var SATA4 = $('#SaturdayAvailability .A4').is( ":checked" );
			var SATANOT = $('#SaturdayAvailability .ANOT').is( ":checked" );
			var SATAnytime = $('#SaturdayAvailability .Anytime').is( ":checked" );
				dayAvailability = {};
					if (	SATANOT	)
					{
						dayAvailability["A1"] = false;
						dayAvailability["A2"] = false;
						dayAvailability["A3"] = false;
						dayAvailability["A4"] = false;
							dayAvailability["A5"] = " ";
							dayAvailability["A6"] = " ";
							dayAvailability["A7"] = " ";
						dayAvailability["ANOT"] = true;
						dayAvailability["Anytime"] = false;
					}
					else if ( SATAnytime	)
					{
						dayAvailability["A1"] = true;
						dayAvailability["A2"] = true;
						dayAvailability["A3"] = true;
						dayAvailability["A4"] = true;
							dayAvailability["A5"] = " ";
							dayAvailability["A6"] = " ";
							dayAvailability["A7"] = " ";
						dayAvailability["ANOT"] = false;
						dayAvailability["Anytime"] = true;
						
					}
					else
					{
						dayAvailability["A1"] = SATA1;
						dayAvailability["A2"] = SATA2;
						dayAvailability["A3"] = SATA3;
						dayAvailability["A4"] = SATA4;
							dayAvailability["A5"] = " ";
							dayAvailability["A6"] = " ";
							dayAvailability["A7"] = " ";
						dayAvailability["ANOT"] = false;
						dayAvailability["Anytime"] = false;					
					}					
					AvailabilityArr["Saturday"] = dayAvailability;
			
						
			//Sunday Availability
			var SUNA1 = $('#SundayAvailability .A1').is( ":checked" );
			var SUNA2 = $('#SundayAvailability .A2').is( ":checked" );
			var SUNA3 = $('#SundayAvailability .A3').is( ":checked" );
			var SUNA4 = $('#SundayAvailability .A4').is( ":checked" );
			var SUNANOT = $('#SundayAvailability .ANOT').is( ":checked" );
			var SUNAnytime = $('#SundayAvailability .Anytime').is( ":checked" );
				dayAvailability = {};
					if (	SUNANOT	)
					{
						dayAvailability["A1"] = false;
						dayAvailability["A2"] = false;
						dayAvailability["A3"] = false;
						dayAvailability["A4"] = false;
							dayAvailability["A5"] = " ";
							dayAvailability["A6"] = " ";
							dayAvailability["A7"] = " ";
						dayAvailability["ANOT"] = true;
						dayAvailability["Anytime"] = false;
					}
					else if ( SUNAnytime	)
					{
						dayAvailability["A1"] = true;
						dayAvailability["A2"] = true;
						dayAvailability["A3"] = true;
						dayAvailability["A4"] = true;
							dayAvailability["A5"] = " ";
							dayAvailability["A6"] = " ";
							dayAvailability["A7"] = " ";
						dayAvailability["ANOT"] = false;
						dayAvailability["Anytime"] = true;
						
					}
					else
					{
						dayAvailability["A1"] = SUNA1;
						dayAvailability["A2"] = SUNA2;
						dayAvailability["A3"] = SUNA3;
						dayAvailability["A4"] = SUNA4;
							dayAvailability["A5"] = " ";
							dayAvailability["A6"] = " ";
							dayAvailability["A7"] = " ";
						dayAvailability["ANOT"] = false;
						dayAvailability["Anytime"] = false;					
					}					
					AvailabilityArr["Sundays"] = dayAvailability;
			
						
			//Availabile Notes
			var Anotes = $('#Anotes').val();			
			AvailabilityArr["Notes"] = Anotes;
			
			var ID = $('#SshiftID').text();			
			var jAvailability = JSON.stringify( AvailabilityArr );											
			var inputarr = {};			
			inputarr['ID'] = ID;
			inputarr['AvailabilityStr'] = jAvailability; 		
			$.post('<?= base_Url(); ?>index.php/EmployeeAvailability/SetAvailability', inputarr, function(data)
			{	
				if( data == 1 )
				{
					alert( "Availability update was succesful");
				}else{
					alert("Availability update had an error");
				}
			});
			
			
		});
		
	});//End of Jquery Document dot ready
		


</script>

<div id ="AvailabilityCRUDPage">
	<h1>Availability Manager </h1>
	<h3>Select An Employee</h3>
		<div id = "DELETEEmployeeList" >
			<h3> Employees </h3>
			<div id = "DELETEEmployeeListInner">
			</div>
		</div>
	
	<div id = "AvailabilityForm" >	
		
		<h3 id = "SelectedEmployeeBox">
			<span class = "block"> Selected Employee: <span id = "appendEmployeeName"> </span><span id ="SshiftID"><?= $EmForm['employeeID']; ?></span> </span>
			<span class = "block"><span id = "EmployeeCertifications">Employee Certifications: </span> </span>
		</h3>
		
		<div id = "innerAvailabilityForm">
		
		<div class = "AvailabilityDay" id = "MondayAvailability"><h3>Monday  </h3>
			<label><input type = "checkbox" class = "A1"/> Before 5:30 am <br></label>
			<label><input type = "checkbox" class = "A2"/> 5:30am to 9:00 am <br>  </label>
			<label><input type = "checkbox" class = "A3"/> 9:00am to 11:30 am <br></label>
			<label><input type = "checkbox" class = "A4"/> 11:30am to 1:30 am <br></label>
			<label><input type = "checkbox" class = "A5"/> 1:30am to 3:15 am <br> </label>
			<label><input type = "checkbox" class = "A6"/> 3:25am to 4:30 am <br> </label>
			<label><input type = "checkbox" class = "A7"/> 4:30pm onwards <br>    </label>
			<label><input type = "checkbox" class = "ANOT"/> Not Availabile <br>    </label>
			<label><input type = "checkbox" class = "Anytime"/> Anytime <br>     </label>
			
		</div> 		
		<div class = "AvailabilityDay" id = "TuesdayAvailability"><h3>Tuesday </h3>
			<label><input type = "checkbox" class = "A1"/> Before 5:30 am <br></label>
			<label><input type = "checkbox" class = "A2"/> 5:30am to 9:00 am <br>  </label>
			<label><input type = "checkbox" class = "A3"/> 9:00am to 11:30 am <br> </label>
			<label><input type = "checkbox" class = "A4"/> 11:30am to 1:30 am <br></label>
			<label><input type = "checkbox" class = "A5"/> 1:30am to 3:15 am <br>  </label>
			<label><input type = "checkbox" class = "A6"/> 3:25am to 4:30 am <br>  </label>
			<label><input type = "checkbox" class = "A7"/> 4:30pm onwards <br>     </label>
			<label><input type = "checkbox" class = "ANOT"/> Not Availabile <br>     </label>
			<label><input type = "checkbox" class = "Anytime"/> Anytime <br>     </label>
		</div> 				
		<div class = "AvailabilityDay" id = "WednesdayAvailability"><h3>Wednesday </h3>
			<label><input type = "checkbox" class = "A1" /> Before 5:30 am <br></label>
			<label><input type = "checkbox" class = "A2"/> 5:30am to 9:00 am <br>  </label>
			<label><input type = "checkbox" class = "A3"/> 9:00am to 11:30 am <br> </label>
			<label><input type = "checkbox" class = "A4"/> 11:30am to 1:30 am <br></label>
			<label><input type = "checkbox" class = "A5"/> 1:30am to 3:15 am <br>  </label>
			<label><input type = "checkbox" class = "A6"/> 3:25am to 4:30 am <br>  </label>
			<label><input type = "checkbox" class = "A7"/> 4:30pm onwards <br>     </label>
			<label><input type = "checkbox" class = "ANOT"/> Not Availabile <br>     </label>
			<label><input type = "checkbox" class = "Anytime"/> Anytime <br>     </label>
		</div> 		
		<div class = "AvailabilityDay" id = "ThrusdayAvailability"><h3>Thrusday </h3>
			<label><input type = "checkbox" class = "A1"/> Before 5:30 am <br></label>
			<label><input type = "checkbox" class = "A2"/> 5:30am to 9:00 am <br>  </label>
			<label><input type = "checkbox" class = "A3"/> 9:00am to 11:30 am <br> </label>
			<label><input type = "checkbox" class = "A4"/> 11:30am to 1:30 am <br></label>
			<label><input type = "checkbox" class = "A5"/> 1:30am to 3:15 am <br>  </label>
			<label><input type = "checkbox" class = "A6"/> 3:25am to 4:30 am <br>  </label>
			<label><input type = "checkbox" class = "A7"/> 4:30pm onwards <br>     </label>
			<label><input type = "checkbox" class = "ANOT"/> Not Availabile <br>     </label>
			<label><input type = "checkbox" class = "Anytime"/> Anytime <br>     </label>
		</div> 		
		<div class = "AvailabilityDay" id = "FridayAvailability"><h3>Friday </h3>
			<label><input type = "checkbox" class = "A1"/> Before 5:30 am <br></label>
			<label><input type = "checkbox" class = "A2"/> 5:30am to 9:00 am <br>  </label>
			<label><input type = "checkbox" class = "A3"/> 9:00am to 11:30 am <br> </label>
			<label><input type = "checkbox" class = "A4"/> 11:30am to 1:30 am <br></label>
			<label><input type = "checkbox" class = "A5"/> 1:30am to 3:15 am <br>  </label>
			<label><input type = "checkbox" class = "A6"/> 3:25am to 4:30 am <br>  </label>
			<label><input type = "checkbox" class = "A7"/> 4:30pm onwards <br>     </label>
			<label><input type = "checkbox" class = "ANOT"/> Not Availabile <br>     </label>
			<label><input type = "checkbox" class = "Anytime"/> Anytime <br>     </label>
		</div> 		
		<div class = "AvailabilityDay" id = "SaturdayAvailability"><h3>Saturday </h3>
			<label><input type = "checkbox" class = "A1"/> Before 8:00 am <br></label>
			<label><input type = "checkbox" class = "A2" /> 8:00 am to 2:00pm <br> </label>
			<label><input type = "checkbox" class = "A3" /> 2:00pm to 4:30pm <br>  </label>
			<label><input type = "checkbox" class = "A4"/> 4:30pm onwards <br>     </label>
			<label><input type = "checkbox" class = "ANOT"/> Not Availabile <br>     </label>
			<label><input type = "checkbox" class = "Anytime"/> Anytime <br>     </label>
		</div> 		
		<div class = "AvailabilityDay" id = "SundayAvailability">
			<h3>Sunday </h3>
			<label><input type = "checkbox" class = "A1"/> Before 8:00 am <br></label>
			<label><input type = "checkbox" class = "A2" /> 8:00 am to 2:00pm <br> </label>
			<label><input type = "checkbox" class = "A3" /> 2:00pm to 4:30pm <br>  </label>
			<label><input type = "checkbox" class = "A4"/> 4:30pm onwards <br>     </label>
			<label><input type = "checkbox" class = "ANOT"/> Not Availabile <br>     </label>
			<label><input type = "checkbox" class = "Anytime"/> Anytime <br>     </label>
		</div> 		
		
		<div id = "AvailabilityNotes" class ="AvailabilityDay">
			<h3> Availability Notes </h3>
			<textarea rows="5" cols="50" maxlength = "200" id = "Anotes" >Write extra notes about this employees availability here.
			</textarea>		
		</div>
		
		<input type = "button" value = "SubmitAvailiability" id = "SumitAvailiability" />
		</div>
	</div>	
	
</div>
			