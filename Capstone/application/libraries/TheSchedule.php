<?php	if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
	/* This will manage the scheule */

	class TheSchedule  { 
	  

		function __construct() 
		{
			error_reporting(E_ALL & ~E_NOTICE);
			
			date_default_timezone_set('EST');
			$this->TodaysDate = date('l F Y');
			
		}
	 
	 
		public function Schedule( ) 
		{
			
		}

		/** function GetTodaysDate()
			purpose: produces the date to be displayed for the schedule in the date of the schedule for today.
			MAJOR EDIT NEEDED PROBBABBLY: should get changed to GetGivenDisplayDate( $givenDate ) which would provide the display date of a given day, to be seen later if need to change.
			return: Todays Date written how it should be displayed as a string.
		*/
		public function GetTodaysDate()
		{
			$TodaysSpecialDate = date('Y\-F\-d');
			return $TodaysSpecialDate;
		}
		
		public function GetFormatTodayDate()
		{			
			$today = date('y\-m\-d');
			return $today;
		}

		/**  function GetThisYear()
			purpose:			
			return: 
		*/
		public function GetThisYear()
		{
			$year = date('Y');
			return $year;
		}
		
		public function GetThisMonth()
		{
			$Month = date('F');
			return $Month;
		}
		
		public function GetThisDay()
		{
			$Day = date('j');
			return $Day;
		}
		
		public function YYYMMDDTodayplz()
		{
			$YYYMMDDToday = date('Y\-m\-d');
			return $YYYMMDDToday;
		}
		
	
	
	
	
	/**	Function QueryByGivenDay
		Purpose: Determine what staff are working to day by mysql.			
		Parameters
			dayDate: The day being examined. A variable that holds the date of a day as YYYY-MM-DD
		
		return theStaffWhoareworking
	*/
	function QueryByGivenDay( $dayDate )
	{
	
		//Staff = Query
		$this->staff = "";		
		$CI =& get_instance(); 				
		$writeQuery = "SELECT DISTINCT s.CurrentOwnerEmployeeID, e.Firstname, e.Lastname FROM `Shifts` s JOIN `Employees` e ON s.CurrentOwnerEmployeeID = e.employeeID WHERE `date` = '".$dayDate."' ;"	;				
		$query = $CI->db->query($writeQuery);
		$staff = $query->result_array(); 
				
		foreach( $staff as $Key => $employee)
		{
			$thisEmployeeID = $employee["CurrentOwnerEmployeeID"];			
			$QueryForShift = "SELECT s.startTime, s.endTime, s.Position, s.ShiftID FROM `Shifts` s JOIN `Employees` e ON s.CurrentOwnerEmployeeID = e.employeeID WHERE `date` =    '".$dayDate."' AND `CurrentOwnerEmployeeID` = '".$thisEmployeeID."' ";			
			$query = $CI->db->query($QueryForShift);			
			$SHIFTS = $query->result_array(); 
								
			foreach( $SHIFTS as $Shiftkey => $shift	)
			{
				$ST = $shift["startTime"];
				$ET = $shift["endTime"];								
				/*
					determine duration and attach it to shifts.
					times are in hh:mm:ss format
					duration will be in 1 to 48 halfhours. 
					determine the duration given the start and end times.
				*/					
					$starthour = substr($ST, 0, 2);
					$starthour = $starthour;
					$halfhour = substr($ST, 3, 1);
					if ( $halfhour == 3 )
					{
						$halfhour = 1;
					}
					else{
						$halfhour = 0;
					}
					$ST = ( $starthour * 2 ) + $halfhour;
					
					$endhour = substr($ET, 0, 2);
					$endhour = (int)$endhour;
					$endhalf = substr($ET, 3, 1);
					if ( $endhalf == 3)
					{
						$endhalf = 1;
					}
					else{
						$endhalf = 0;
					}
					$ET = ( $endhour * 2 ) + $endhalf;
					
					
				$duration = $ET - $ST;				
				$SHIFTS[$Shiftkey]["duration"] = $duration;
				
				$pos = "";
				$position = (int)$shift["Position"];
				
				switch ($position)
				{
					case 1:
						$pos = "Lifeguard";
						break;
					case 2:
						$pos = "Instructor";
						break;
					case 3:
						$pos = "Headguard";
						break;
					case 4:
						$pos = "Supervisor";
						break;
					default: 
						$pos = "ERROR".$position;											
				}
				$SHIFTS[$Shiftkey]["Position"] = $pos;		
				$SHIFTS[$Shiftkey]["ShiftID"] = $shift["ShiftID"];				
			}			
			//Place shifts in employee array
			$staff[$Key]["Shifts"] = $SHIFTS;
			$staff[$Key]["TheDate"] = $dayDate;
			$staff[$Key]["EmployeeID"] = $thisEmployeeID;			
		}		
		return json_encode( $staff );
	}/* End of Function QueryByGivenDay*/
  
  
  
  
  
  
  
  
  
  
  
  
	/* function SelectArrayStartingToday()
	
		parameters: none
		
		purpose: Develop a JS array of all real dates of the next two years starting today.
		This does not currently work. Using CreateSelectArray() instead to save time.
		
		return: ListOfDates
	
	*/
    public function SelectArrayStartingToday()
    {
		$ListOfDates;
		
		
		$TodayDay = $this->GetThisDay();
		$ThisMonth = date('m');
		$ThisYear = $this->GetThisYear();
		$NextYear = $ThisYear+1;
		$ListOfDates = [ $ThisYear, $NextYear ];
		
		$rangeMonths = array();
		for ($i = $ThisMonth; $i <= 12; $i++ )
		{
			array_push( $rangeMonths, $i);
		}
		$ListOfDates[$ThisYear] =  $rangeMonths;
		
		$rangeMonths = array();
		for ($i = 1; $i <= 12; $i++ )
		{
			array_push($rangeMonths, $i );
		}
		$ListOfDates[$NextYear] = $rangeMonths;
		
		//Get the number of days in this month
		$NumberofDays = cal_days_in_month(CAL_GREGORIAN, $ThisMonth, $ThisYear);
		
		//Subtract NumberofDays by $TodayDay   to get days left. 31 - 14  = 17days left   15, 16 , 17  to 31 
		$Daysleft = $NumberofDays - $TodayDay;
		$rangeofdaysleft = array(); // will hold the array of days left in the month, including today.
		
		for( $i = $TodayDay; $i <= $NumberofDays; $i++ )
		{
			array_push($rangeofdaysleft, $i);
		}
	
		//$ListOfDates[$ThisYear];
		for( $i = $ThisMonth; $i <= 12 ; $i++)
		{
			if($i == $ThisMonth)
			{
				$ListOfDates[$ThisYear][$i] = $rangeofdaysleft;
			}
			else
			{
				$range = array();
				$MaxDays = cal_days_in_month(CAL_GREGORIAN, $i, $ThisYear);
				for($d = 1; $d <= $MaxDays; $d++)
				{
					array_push($range, $d);
				}
				$ListOfDates[$ThisYear][$i] = $range;
			}
			
		}
		//array_push($ListOfDates, $arrCurrentYear);
		
	
		for( $i = 1 ; $i <= 12; $i++	)
		{
			$range = array();
			$MaxDays = cal_days_in_month(CAL_GREGORIAN, $i, $NextYear);
			for($d = 1; $d <= $MaxDays; $d++)
			{
				array_push($range, $d);
			}
			$ListOfDates[$NextYear][$i] = $range;
			
		}
		//array_push($ListOfDates, $arrNextYear);
		
		
		
		return json_encode($ListOfDates);
	
	}
  
  /** Function	CreateSelectArray
		Purpose: Creates the array of months holding days for each month.
		This will be used untill i have time to develop SelectArrayStartingToday().
  */
  public function CreateSelectArray()
  {
		//Make the array relative to today.... ill do later.
		$this->TodayDate = "";
		$this->TodaysDate = $this->GetTodaysDate();	  	
		$days = 0;		
 
		$LISTofDATES = [];
		
		for($ya = 2020; $ya < 2040; $ya++) {
			$year =  "".$ya."";
			$LISTofDATES[$year] = [ "January" => 0, "February" => 0, "March" => 0, "April" => 0, "May" => 0, "June" => 0, "July" => 0, "August" => 0, "September" => 0, "October" => 0, "November" => 0, "December" => 0 ];	 
		}

		foreach ( $LISTofDATES as $Year => $Months )
		{			
			for( $Month = 1; $Month <= 12; $Month++ )
			{
				if ( $Month == 1 )
				{
					$days = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
					$LISTofDATES[$Year]['January'] = $days;
				}
				if ( $Month == 2 )
				{
					$days = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
					$LISTofDATES[$Year]['February'] = $days;
				}
				if ( $Month == 3 )
				{
					$days = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
					$LISTofDATES[$Year]['March'] = $days;
				}
				if ( $Month == 4 )
				{
					$days = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
					$LISTofDATES[$Year]['April'] = $days;
				}
				if ( $Month == 5 )
				{
					$days = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
					$LISTofDATES[$Year]['May'] = $days;
				}
				if ( $Month == 6 )
				{
					$days = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
					$LISTofDATES[$Year]['June'] = $days;
				}
				if ( $Month == 7 )
				{
					$days = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
					$LISTofDATES[$Year]['July'] = $days;
				}
				if ( $Month == 8 )
				{
					$days = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
					$LISTofDATES[$Year]['August'] = $days;
				}
				if ( $Month == 9 )
				{
					$days = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
					$LISTofDATES[$Year]['September'] = $days;
				}
				if ( $Month == 10 )
				{
					$days = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
					$LISTofDATES[$Year]['October'] = $days;
				}
				if ( $Month == 11 )
				{
					$days = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
					$LISTofDATES[$Year]['November']= $days;
				}
				if ( $Month == 12 )
				{
					$days = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
					$LISTofDATES[$Year]['December'] = $days;
				}							
			}	  
		}//For each Year done		
		/*Should have a fully populated array of days for each month in 2 years*/		
		return json_encode($LISTofDATES);				
    }/* End of Function  CreateSelectArray */
	
  
  
  
	/** function convertTimeToDisplayTime
				
		purpose: converts 24 Time To display able Am Pm time
		
		return AmPm;
	*/
	public function convertTimeToDisplayTime( $twentFourHourTime )
	{
		$AmPm = "TIME";		
		$AmPm = date("g:i a", strtotime($twentFourHourTime));		
		return $AmPm;
	}/* End of Function convertTimeToDisplayTime */
				
}/*End of TheSchedule. php */








?>