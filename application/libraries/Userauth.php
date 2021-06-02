<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/** Userauth class
	This class process user athentication of people using the web app. 
	These are the functions.
		--> login
		--> CheckUserNameFormat
		--> loggedin
		--> logout
		--> validSessionExists
		--> isformEmpty
		-->	userIsInDatabase
		--> passwordMatch
		--> redirect
		--> getCertifications
		--> GetNewNotifications
		--> writeSession
		-->	passMeSession
		-->	passMeID
*/
class Userauth  { 	

    function __construct() 
    {
		error_reporting(E_ALL & ~E_NOTICE);
		$this->login_page = base_url() . "index.php?/Login";  /* Used to take user back to login page after it is determined that they are not logged in. */
		$this->logout_page = base_url() . "index.php?/Logout"; /* Used to take user to log out page after they have loged out.*/
    }

	
	
	
		
	/** Function login
			Purpose: Uses the username and password to try and log user in.
	*/
    public function login($Username,$password) 
    {
		session_start();

		/* Determine if user is logged in. */
		if ($this->validSessionExists() == true)
		{		$this->redirect(base_url() . "index.php?/Home");		}

		/* Prevent error messages for first arrival of user. */ 
		if ($_SERVER['REQUEST_METHOD'] == 'GET') return;

		/* Check login fields for empty values. */
		if ($this->isformEmpty($Username, $password) == false)
		{		return "The Username and Password fields cannot be blank.";	}
		
		/*Check regex pattern for username should be Text.Text */
		if ($this->CheckUserNameFormat($Username) == false )
		{	return "Username needs to be typed like Firstname.Lastname";	}
			
		/* Check database for username that was given by user. */
		if ($this->userIsInDatabase() == false)
		{	return "The username you entered does not match any account.";	}

		/*Check to see if password matches one in database.*/
		if ($this->passwordMatch() == true)
		{ 
			/*  Login Complete. Redirect authenticated users to the home page. */
			$this->writeSession();
			$this->redirect(base_url() . "index.php?/Home");
		}
		else
		{	return "Password incorrect";	}	
    }/* End of Function login */




	
	/** Function checkUserNameFormat
			Purpose: Checks user name format for Norman.Potts format.
	*/
	public function CheckUserNameFormat($Username)
	{ 		
		$pattern = "/^[a-zA-Z]+\.[a-zA-Z]+$/";
		if (preg_match($pattern, $Username))
		{	return true;	}
		else 
		{	return false;	}
	}/* End of Function checkUserNameFormat */
	
	
	
	
	
	/** Function loggedin
			Purpose: Will be used to determine if user has permission to access a webpage.
	*/
    public function loggedin($page) 
    {
		session_start();        
		/* Users who are not logged in are redirected out */
		if ($this->validSessionExists() == false)		
		{		$this->redirect($this->login_page);		}
		 
		/* Access Control List checking goes here..		  */
		return true;
	}/* End of Function loggedin */




	
	/** Function logout
			Purpose: Logs user out. Destroys session.
	*/
    public function logout() 
    {
      session_start(); 
      $_SESSION = array();
      session_destroy();
      header("Location: ".$this->logout_page);
    }/* End of Function logout */




	
    /** Function validSessionExists 
			Purpose: Determine if user has correctly logged in and started a session.
	*/
    public function validSessionExists() 
    {
      session_start();
      if (!isset($_SESSION['Username']))
      {		return false;	}
      else
      {		
		return true;
	  }
    }/* End of Function validSessionExists */



    
	
	
	
	
	
	
	/** Function isformEmpty 
			Purpose: Determine if username or password are empty. 
	*/
    public function isformEmpty($Username, $password) 
    {
		if ( (empty( $Username ) == false) && (empty( $password ) == false) )
		{	$this->Username = $Username;
			$this->password = $password;
			return true;	
		}
		else
		{	return false;	}
    }/* End of Function isformEmpty */

	
	
	
	
	/** Function userIsInDatabase
			Purpose: Looks in data base for the given username. also grabs password for that user. 
	*/
    public function userIsInDatabase() 
    {    
		$CI =& get_instance();
		$user = $this->Username;	  	  	 
		$query = $CI -> db ->query("Select Username, password FROM `Employees` WHERE `Username` = '".$user."';");
		$row = $query->row();
		if($query->row() == null ) {         /*Check for null record. */
			return false; /// User doesnt exist. 
		} 
		else
		{
			$result = $row->Username;
			$this->QueryReturnPASS = $row->password;
			/*If the database result equals the username */
			if ( $result == $user )  
			{		return true;	} 
			else 
			{		return false;	}
		}
    }/* End of Function userIsInDatabase */

	
	
	
	
    /** Function passwordMatch
			Purpose: Checks the password and sees if it matches the one in the database.			
	*/
	public function passwordMatch()
	{		
		/* Compares the password from user to the passwored in db. */
		if ( $this->QueryReturnPASS == $this->password )
		{  return true;		}
		else
		{	return false;	}
	}/*End of Function passwordMatch */

	
	
	
	
	/** Function redirect 
			Purpose: Redirects the user to the given page.
			Parameters: '$page' the page to be redirected too.
	*/
    public function redirect($page) 
    {
        header("Location: ".$page);
        exit();
    }/* End of Function redirect */




	
	/** Function getCertifications
			Purpose: Gets the certifications of the user who is logged in.
			
	*/
	public function getCertifications()
	{
		$CI =& get_instance();	
		$user = $this->Username;
		$pass = $this->password;
		$query = $CI->db->query("Select Supervisor, Instructor, Lifeguard, Headguard, Firstname, Lastname, employeeID FROM `Employees` WHERE `Username` = '".$user."';");
		$row = $query->row();
		$this->Supervisor = $row->Supervisor;		
		$this->Lifeguard = $row->Lifeguard;
		$this->Instructor = $row->Instructor;	
		$this->Headguard = $row->Headguard;				
		$this->Firstname = $row->Firstname;
		$this->Lastname = $row->Lastname;
		$this->EmployeeID = $row->employeeID;				
	}/* End of Function getCertifications */
	
	
	
	
	
	/** Function GetNewNotifications 
			Purpose: Gets all notifications that are unread for this employee.
	*/
	public function GetNewNotifications()
	{	session_start();
		$EID = $_SESSION["EmployeeID"];
		$CI =& get_instance();			
		$query = $CI->db->query("Select `NewNotfications` FROM `Employees` WHERE `employeeID` = '".$EID."';");
		$row = $query->row();
		return $row->NewNotfications;		
	}/* End of Function GetNewNotifications */

	
	
	
	
	
	/** Function writeSession
			Purpose: Sets the session variables for this user.
	*/
	public function writeSession() 
    {
		$this->getCertifications();		
        $_SESSION['Username'] = $this->Username;						
		$_SESSION['Supervisor'] = $this->Supervisor;
		$_SESSION['Lifeguard'] = $this-> Lifeguard;
		$_SESSION['Instructor'] = $this-> Instructor;
		$_SESSION['Headguard'] = $this-> Headguard;
		$_SESSION['Firstname'] = $this->Firstname;
		$_SESSION['Lastname'] = $this->Lastname;
		$_SESSION['EmployeeID'] = $this->EmployeeID;	
	}/*End of Function writeSession */
	
	
	
		
	/** Function writeSession
			Purpose: Sets the session variables for this user.
	*/
	public function ReFreshSession() 
    {
		$CI =& get_instance();	
		$employeeID = $_SESSION['EmployeeID'];	
		$query = $CI->db->query("Select Supervisor, Instructor, Lifeguard, Headguard, Firstname, Lastname, Username FROM `Employees` WHERE `employeeID` = '".$employeeID."';");
		$row = $query->row(); 	
        $_SESSION['Username'] = $row->Username;						
		$_SESSION['Supervisor'] = $row->Supervisor;
		$_SESSION['Lifeguard'] = $row-> Lifeguard;
		$_SESSION['Instructor'] = $row-> Instructor;
		$_SESSION['Headguard'] = $row-> Headguard;
		$_SESSION['Firstname'] = $row->Firstname;
		$_SESSION['Lastname'] = $row->Lastname;				
		
	}/*End of Function writeSession */
	
	
	
	
	
	/** Function passMeSession
			Purpose: Returns the session array.
	*/
	public function passMeSession()
	{
		session_start();
		return $_SESSION;		
	}/*End of Function passMeSession */
	
	
	
	
	/** Function passMeID 
			Purpose: Returns the $ID of the currently logged in employee.
	*/
	public function passMeID()
	{
		session_start();
		$ID = $_SESSION['EmployeeID'];	
		return $ID;
	}/*End of Function passMeID*/

}/*End of Userauth library */
?>