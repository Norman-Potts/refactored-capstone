		/** function convertTimeToDisplayTime
				
			purpose: converts 24 Time To AmPm time
			
			parameters: twentFourHourTime  in HH:MM:SS
			
			return Time;
		*/
		function convert24HourTo12hr( twentFourHourTime )
		{
			var d = twentFourHourTime.split(":");
			var HH = d[0];
			var MM = d[1];
			var SS = d[2];
			var AMPM = "";			
			var hour = "";			
			HH = parseInt(HH);			
			if ( HH > 12)
			{
				hour = HH - 12;
				AMPM = "PM";
			}
			if ( HH == 12 )
			{
				hour = 12;
				AMPM = "PM"
			}
			if ( HH == 0 )
			{
				hour = 12;
				AMPM = "AM";
			}
			if( HH < 12 && HH > 0)
			{
				hour = HH;
				AMPM = "AM";
			}
			var m = MM;			
			var s = SS			
			var Time = ""+hour+":"+m+" "+AMPM+"";
			
			return Time;
			
		}//End of function convert24HourTo12hr
		
		
		
		
		
		
		
		
		
		
		/** function convertAMPMto24hour( AMPM )
				
			purpose: converts AMPM to 24 hour time.
									
			parameters: AMPM
			
			return twentFourHourTime;
		*/
		function convert12hrTo24Hour( AMPM )
		{
			var twentFourHourTime = "";
			
			return twentFourHourTime;
		}
		
		
		
		
		
		
		
		
		
		
		/** function formatDate		
				Purpose: formats a given mysql date for display on top of schedule table.		
			parameters date the mysql date needing to be formatted for display.
		*/
		function formatDate(date) {
		  
		  var monthNames = [
			"January", "February", "March",
			"April", "May", "June", "July",
			"August", "September", "October",
			"November", "December"
		  ];

		  
		  var m =	date.substring(	5, 7);	
		  var month; 
		  
		  
			switch (m)	
			{
				case "01":
					month = "January";
					break;
				case "02":
					month = "February";
					break;
				case "03":
					month = "March";
					break;
				case "04":
					month = "April";
					break;
				case "05":
					month = "May";
					break;
				case "06":
					month = "June" ;
					break;
				case "07":
					month = "July";
					break;
				case "08":
					month = "August";
					break;
				case "09":
					month = "September";
					break;
				case "10":
					month = "October";
					break;
				case "11":
					month = "November";
					break;
				case "12":
					month = "December";					
			}/*End of switch */
		  
		  
		  
		  var day = date.substring(	8, 10);		  
		  var year = date.substring( 0 , 4 );; 

		  return ' '+ year + '-' + month + '-' +day + ' ';
		}