<!DOCTYPE HTML>
<html lang = "en-US">
<head>
	
	<title>Shift Manager</title>
	<link href = "<?= assetUrl(); ?>css/AppStyles.css" rel="stylesheet" type="text/css"/>
	<? session_start(); ?>	

	
	<script src	= "https://code.jquery.com/jquery.js" > </script>
	
	<script type = "text/javascript">
		
		/** Validate()
			Purpose: To validate login form before php script to reduce server load.			
					Checks username and password for valid and safe data. Returns an error message to user 
					explaining why the input was reject. Allows data to go to server if it is safe.
					
				Username standards and help message
					- must be Firstname.Lastname format.
						msg: Make sure you are using correct username format. Firstname.Lastname.
				Password standards and help message
					- uuhh not sure yet. need to determine secuerty standards for password.
		*/	
		
		function Validate() {			
			    
			var UsernameField = "";
			var passwordField = "";
			var passfail = false;
			
			UsernameField += Login.Username.value;				
			passwordField += Login.password.value;
						
			/* If UsernameField and pass is empty */
			if ( UsernameField == "" && passwordField == "" )
			{
				var str = "Username and password is empty.";
				document.getElementById("RedErrorBox").innerHTML = str;
				passfail = false;
			}
			else if ( UsernameField == "" )
			{
				var str = "Username is empty.";
				document.getElementById("RedErrorBox").innerHTML = str;
				passfail = false;				
			}
			else if ( passwordField == "")
			{	
				var str = "Password is empty.";
				document.getElementById("RedErrorBox").innerHTML = str;
				passfail = false;	
			}
			else
			{				
				/* Test login format */   				   
				var PatternName = /^[a-zA-Z]+\.[a-zA-Z]+$/;
				var answerName = PatternName.test(UsernameField);

				if (answerName == true)
				{									
					passfail = true;
				}
				else
				{
					var str = "That Username doesnt use correct format.<br> Use Firstname.Lastname";
					document.getElementById("RedErrorBox").innerHTML = str;
					passfail = false;
				}
			}			
			return passfail
		}
		
		
		/*This displays the help message when the help button is clicked.*/
		function Help()
		{		
			var modal = $('#HelpModal');	
			var close = $('#close');
			modal.css({'display':"block"}); 									
			close.click(function(){
				modal.css({'display': "none"});
			});
		}
		
	</script>
				
</head>
<body>
	<div id = "loginParentBox">
		<div id = "loginBox" >						
			
				<h1><b>Aquatic Employee<br>Schedule Website </b></h1>
								
					<h2> Please login below</h2>
		 
						<form id="myLoginForm" action="<?= base_Url(); ?>index.php/Login/loginuser" onsubmit="return Validate();" method="post" name="Login" accept-charset= "utf-8"  >
						
						Username: <input type="text" name="Username" value = "<?php echo set_value('Username', ''); ?>" maxlength="42" /> <!--Changed from  21 to 42. Should check for bugss -->
							<br><br>
						Password:	<input type="password" name ="password" value = "<?php echo set_value('password', ''); ?>" maxlength="20" />
							<br><br>
						
							<input type = "submit" value = "Login" id = "loginbutton" />
						</form>
			
					
						<div id = "RedErrorBox"> 
							<?= $msg ?>
						</div>

			<a onclick = "Help();" >
				<div id = "help">
					<p>Help! </p>
				</div>
			</a>
								
				<div id = "HelpModal" class= "modal">
					<div id ="HelpModalContent">
					<div id="close">&times;</div>
					<p class="loginhelpmessage">
						<b>Step 1:</b> Confirm with your supervisor that you are using the correct Username and Password. <br>
						<b>Step 2:</b> Enter your Username using the correct format "Firstname.Lastname". Make sure you put the period in the middle. <br>
						<b>Step 3:</b> Enter your password for your account. <br>
						<b>Step 4:</b> Click on the green login button. <br><br>
						If you are still having trouble, ask Norman for help.<br>
					</p>
					</div>
				</div>											
		</div>		
	</div>
		

	<a href = "<?= base_url()?>index.php/Documentation">
		<div id = "authorship">
		
		<p>
			<strong>
				Created by Norman Potts
				Click Here First!
			</strong>
		</p>
		</div>
	</a>
	
	
</body>
</html>