<html>
<head>
	<title>Documentation</title>
	<style type="text/css">


	body {
		background-color: rgb(153,217,234);	
		font-family: "Comic Sans MS", "Comic Sans";
		text-align: center;	
	}

	#CenterBlock {
		display: inline-block;
		border: 3px solid black;
		padding: 2em;
		text-align: left;
		position: relative;			
		width: 60%;		
		height: auto;	
		background-color: white;
		border-radius: 15%;
	}
	
	p {
		text-indent: 5em;
		font-size: 1.3rem;
	}
	td {
		padding-right: 1em;
		
	}
	


	</style>
</head>
	<body>
	
	<div id = "CenterBlock">
		<h1>About this Web Application, and Authorship. </h1>
		<p>
			This web application was created by Norman Potts.
			It is uses a CodeIgniter version 3.1.4 PHP framework.
			This application does not use any other plugins or third party services. 
			It uses MySQL to manage four tables. One CSS file manages the stiles for most of the application.
			Html, Javascript and Jquery are used to manage the front end of the application.
			This project was started April 18th 2017.
		</p>

		<p>
			The main purpose of this web application is to allow employees to be able to see
			their shifts online, trade shifts online, and maintain an updated schedule.
		</p>
		<p>
			The application has user login/logout functionality.
			The supervisor account type acts as an administrator. 
			A supervisor can manage users, and manage the shifts.
			All other user types can trade the shifts they have with other employees who have the certification
			for the type of shift. Shifts are displayed on the schedule page. Employees have access to a 
			profile page which presents all relevant information. On the profile page they can upload a profile 
			picture if they want too. The home page allows user write in a message box, Submit subslip, and
			take subslips.
		</p>
		
		<p>	
			<h4>Accounts that allow you to access the web application.</h4>
			<table>
			<thead>
			<tr>
			<th>Username</th>
			<th>password</th>
			</tr>
			</thead>
			<tbody>
				<tr><td>Norman.Potts</td><td> 	roflPlz</td></tr>
				<tr><td>Lifeguard.Only</td><td>	password</td></tr>
				<tr><td>Instruct.Only</td><td> 	password</td></tr>
				<tr><td>Headguard.Only</td><td>	password</td></tr>
				<tr><td>Supervisor.Only</td><td> password</td></tr>
				<tr><td>John.Doe</td><td>	AG2dEx1mp1e</td></tr>
				</tbody>
			</table>
			
		
		</p>
		
		<p>
			StAuth10065: I Norman Potts, 000344657 certify that this material is my original work.
			No other person's work has been used without due acknowledgement.  I have not made my work available to anyone else.
		</p>

		<br> 
		<p>
			Date: 2018-January-05
		</p>
		
		<p><strong><a href = "<?= base_Url(); ?>index.php/Login" > Back to Login </a></strong></p>
		</div>
	</body>
</html>