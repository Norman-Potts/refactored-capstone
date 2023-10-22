		
		/** Function getdateFromSelect		
				Purpose: determines the date in the select boxes 			
			return: selectDate the date of the select boxes in yyyy-mm-dd format.
		*/
		function getdateFromSelect()
		{
			var day = "";
			var month = "";
			var year = "";	
			var d = $('#Day').val();			
			if ( d < 10) 
			{ day = "0"+d; }
			else
			{	day = ""+d+"" }	
			var m = $('#Month').val();			
			switch (m)	
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
			
			}/*End of switch */			
			var y = $('#Year').val();
			year = y;
			var selectDate =  ""+year+"-"+month+"-"+day+"";
			return selectDate;
		}//End of function getdateFromSelect
		
		