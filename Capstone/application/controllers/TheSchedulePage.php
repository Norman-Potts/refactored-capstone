<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TheSchedulePage extends CI_Controller {
	
	/* This manages the Schedule page.  */		
	
  var $TPL;

  public function __construct()
  {
    parent::__construct();	
	
  }

  
  
  public function index()
  {    		
	//Get Todays Date.
	
	$this->TPL['Date'] =  $this->theschedule->GetTodaysDate();
	$this->TPL['LISTofDATES'] = $this->theschedule->CreateSelectArray(); //Creates the select array variables.
	$this->TPL['ThisYear'] = $this->theschedule->GetThisYear();
	$this->TPL['ThisMonth'] = $this->theschedule->GetThisMonth();
	$this->TPL['ThisDay'] = $this->theschedule->GetThisDay();
	$this->dayDate = $this->theschedule->GetFormatTodayDate(); //Get todays date
	$this->TPL['Todaystaff'] = $this->theschedule->QueryByGivenDay( 	$this->dayDate ); // Gets the array to build the schedule, by a given day.
	$this->TPL['SelectedScheduleStaff'] = $this->TPL['Todaystaff']; //On page load SelectedScheduleStaff should equal todaystaff since today staff gets displayed idunno...
	
	$this->TPL['CurrentYear'] = $this->theschedule->GetThisYear();
	$this->TPL['CurrentMonth'] = $this->theschedule->GetThisMonth();
	$this->TPL['CurrentDay'] = $this->theschedule->GetThisDay();

	//Need to figure out how the date of the current request schedule will work.



	$this->dayDate = $this->theschedule->GetFormatTodayDate(); //Get todays date
	$this->TPL['YYYMMDDToday'] = $this->theschedule->YYYMMDDTodayplz();
	
	$this->TPL['TheScheduleArray'] = $this->theschedule->QueryByGivenDay( $this->dayDate );
		
	
	
	
	$this->template->show('schedule_view', $this->TPL);

  }
  
  
  
  
}






?>
