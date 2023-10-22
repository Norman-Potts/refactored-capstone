
<!--ToDo: fix navgiation bar before starting myprofile_view. -->
<script type = "text/javascript">
	$(document).ready(function() {	
		
		
		var ProfilePicUploadmsg = '<?php echo ($ProfilePicUploadmsg); ?>' ;
		
		
		if( ProfilePicUploadmsg != '' ) {
			$('#UploadProfilePic').css({'display':"block"});
			$('#ProfileErrorBox').append(ProfilePicUploadmsg);
		} else {
			$('#UploadProfilePic').css({'display':"none"});
		}
		
		
		$( "#ChangeProfiePic" ).addClass("ChangeProfiePic");
		
		$("#ChangeProfiePic").hover(
			function() {
				$( this ).removeClass("ChangeProfiePic");
				$( this ).addClass("HoverChangeProfiePic");
				
				
			}, function() {
				$( this ).removeClass("HoverChangeProfiePic");
				$( this ).addClass("ChangeProfiePic");
			}
		);
		
		$("#ChangeProfiePic").click(function(){
			$('#UploadProfilePic').css({'display':"block"});
			
		});
		$("#close").click(function(){
			$('#UploadProfilePic').css({'display': "none"});
		});
		
 
 
		

	 
		
		/** Function validateImg()			
			Purpose: To front end validate the file the user has given in upload profile pic form.
			
			variables: 
				var passOrFail boolean that controls final decision on allowing upload or cancling upload.
				var ErrorString string that holds the error message.
				var imgfile the value of the imgForm img value.
				var EmptyFile boolean that switches when there is a file to true.
				var goodMimeType boolean that switches to true when the mime type of the img provide in form is an acceptable mime type. (JPG, png or gif. )
				var toosmall boolean that switches to true when the file is too small.
				var toobig boolean that switches to true when the file is to big.
			returns passOrFail;
		*/
		function validateImg()
		{
			
			var passOrFail = false;	//passOrFail a boolean that stops the form if it is returned as false.
			var ErrorString = ""; // ErrorString will hold the error message
			var imgfile = imgForm.img.value;				
			
			/* Test 1: determine if there is no file. */
			var EmptyFile = false; // assume there is nofile.
			
			/* Test 2 variables */
			var goodMimeType = false;//assumed bad.
			
			/* Test 3 too small */
			var toosmall = true; //assumed too small
			
			/* Text 4 too big */
			var toobig = true; //assumed too big
			
			

			/* if imgForm is empty */
			if($('#img').val() == '')
			{				
				EmptyFile = false;
				ErrorString = "Please choose a img file. ";
			}
			else
			{				
				EmptyFile = true;
			}
			
			if ( EmptyFile == true)
			{	
				//Now Check mime type.
				//mimetype test.
				var splitName = imgfile.split("."); //split the img file into strings at each . period.
				var mI = splitName.length; 	//get the length of the array that gets produced
				var mimetype =""+splitName[mI-1]	//the last string is probabbly the mime type.
				//If mime type is correct...
				if( mimetype == "jpg" || mimetype == "png" || mimetype == "gif" )
				{	//mimetype is acceptable
					goodMimeType = true;
				}
				else
				{	//mimetype bad.	
					ErrorString = "<li> The file was not an acceptable image type. Use jpg, png or gif. </li>";	
					goodMimeType = false;
				}
				
				if ( goodMimeType == true   )
				{
					//Get the file size.
					var Filesize = 	$('#img')[0].files[0].size;
					Filesize = Filesize / 1000;
					
					//filesize must be greater than 30 kb.
					if( Filesize < 3 )
					{
						ErrorString += "<li> The image must be larger than 30 kilobytes. </li> ";
						toosmall = true; // file is too small
					}
					else
					{toosmall = false;}
					
					//file size too big, it must be less than 2048 kb.
					if( Filesize > 2048)
					{
						ErrorString += "<li>The image must be less than 2 Megabytes. "+Filesize+" </li>";
						toobig = true; //file is too big.
					}
					else
					{toobig = false;}
				}
			}							
					
			if ( goodMimeType == true && toobig == false && toosmall == false ) 						
			{				
				ErrorString = "Loading Image!";
				document.getElementById("ProfileErrorBox").innerHTML = ErrorString;					//writes the errorString in the error message	
				passOrFail = true;
			}
			else
			{						
				document.getElementById("ProfileErrorBox").innerHTML = ErrorString;					//writes the errorString in the error message	
				passOrFail = false;
			}				
			/* The return. */
			return passOrFail;			
		}

		
		$(document).on("mouseover", '.NewNotification', function (e) {	
			console.log(<?php echo $_SESSION['EmployeeID']; ?>);
			console.log("mouse over");
			var span = e.target.getElementsByTagName("span");	
			if(span != undefined &&   span.length == 1 ){
				console.log(  parseInt(  span[0].innerHTML ));	/// notificationID? 
				var inputarr = {};
				inputarr["notificationID"] = parseInt( span[0].innerHTML ) ;		
				inputarr["EmployeeID"] =  <?php  echo $_SESSION['EmployeeID']; ?>;
				$.post('<?= base_Url(); ?>index.php/MyProfile/updateNotificationRead', inputarr, function(data) {	
					$( e.target ).removeClass("NewNotification");
					$( e.target ).addClass("OldNotification");
				});				
			}
		});
		
		
		loadNotifications();
		/** Function loadNotifications
			Purpose: Loads the notification div with notifications.
		*/
		function loadNotifications( )
		{						
			var notifications = JSON.parse('<?php echo $Notifications; ?>');		
			var msg = "";		
			console.log(notifications);	
			for(var item in notifications)
			{			
				if(notifications[item]["readOrUnread"] == false)
				{						
					msg += "<div id=\"notificationCell\" class = \"NewNotification\"  >"; 		
						msg += "<span hidden>"+notifications[item]["notificationID"]+"</span>";							
						msg += "<h4>"+notifications[item]["message"]+"</h4>";							
					msg += "</div>";
				}
			}				
			for(var item in notifications)
			{			
				if(notifications[item]["readOrUnread"] == true)
				{
					msg += "<div id=\"notificationCell\" class = \"OldNotification\">";			
						msg += " <h4>"+notifications[item]["message"]+"</h4>";				
					msg += "</div>";
				}
			}														
			$('#NotificationBOXInner').append(msg);
		}/*End of Function loadNotifications*/
		
		
		
		
		
		displayAvailability();
		function displayAvailability()
		{
			
			var MSG = "";
			
			try {
				
				var Availability = JSON.parse('<?php echo $Availability;?>');
				
				MSG += "Notes: "+Availability['Notes']+".<br> ";
				
				var MondayMSG = "";
				if( Availability['Mondays']['ANOT'] == true )
				{
					MondayMSG += "<li>Not Available.</li>"
				}
				else if (  Availability['Mondays']['Anytime'] == true)
				{
					MondayMSG += "<li>Available Anytime.</li>";
				}else{
					if( Availability['Mondays']['A1'] == true)
					{
						MondayMSG += "<li>Before 5:30am.</li>";
					}
					if( Availability['Mondays']['A2'] == true)
					{
						MondayMSG += "<li>5:30am to 9:00am</li>";
					}
					if( Availability['Mondays']['A3'] == true)
					{
						MondayMSG += "<li>9:00am to 11:30pm</li>";
					}
					if( Availability['Mondays']['A4'] == true)
					{
						MondayMSG += "<li>11:30pm to 1:30pm</li>";
					}
					if( Availability['Mondays']['A5'] == true)
					{
						MondayMSG += "<li>1:30pm to 3:15pm</li>";
					}
					if( Availability['Mondays']['A6'] == true)
					{
						MondayMSG += "<li>3:25pm to 4:30pm</li>";
					}
					if( Availability['Mondays']['A7'] == true)
					{
						MondayMSG += "<li>4:30pm onwards</li>";
					}
				}
				MSG += "<br />Monday: <ul>"+MondayMSG+"</ul>";
				
				
				
				
				var TuesdayMSG = "";
				if( Availability['Tuesday']['ANOT'] == true )
				{
					TuesdayMSG += "<li>Not Available.</li>"
				}
				else if (  Availability['Tuesday']['Anytime'] == true)
				{
					TuesdayMSG += "<li>Available Anytime.</li>";
				}
				else
				{
					if( Availability['Tuesday']['A1'] == true)
					{
						TuesdayMSG += "<li>Before 5:30am.</li>";
					}
					if( Availability['Tuesday']['A2'] == true)
					{
						TuesdayMSG += "<li>5:30am to 9:00am</li>";
					}
					if( Availability['Tuesday']['A3'] == true)
					{
						TuesdayMSG += "<li>9:00am to 11:30pm</li>";
					}
					if( Availability['Tuesday']['A4'] == true)
					{
						TuesdayMSG += "<li>11:30pm to 1:30pm</li>";
					}
					if( Availability['Tuesday']['A5'] == true)
					{
						TuesdayMSG += "<li>1:30pm to 3:15pm</li>";
					}
					if( Availability['Tuesday']['A6'] == true)
					{
						TuesdayMSG += "<li>3:25pm to 4:30pm</li>";
					}
					if( Availability['Tuesday']['A7'] == true)
					{
						TuesdayMSG += "<li>4:30pm onwards</li>";
					}
				}
				MSG += "<br />Tuesday: <ul>"+TuesdayMSG+"</ul>";
				
				
				
				
				
				var WednesdayMSG = "";
				if( Availability['Wednesday']['ANOT'] == true )
				{
					WednesdayMSG += "<li>Not Available.</li>"
				}
				else if (  Availability['Wednesday']['Anytime'] == true)
				{
					WednesdayMSG += "<li>Available Anytime.</li>";
				}
				else
				{
					if( Availability['Wednesday']['A1'] == true)
					{
						WednesdayMSG += "<li>Before 5:30am.</li>";
					}
					if( Availability['Wednesday']['A2'] == true)
					{
						WednesdayMSG += "<li>5:30am to 9:00am</li>";
					}
					if( Availability['Wednesday']['A3'] == true)
					{
						WednesdayMSG += "<li>9:00am to 11:30pm</li>";
					}
					if( Availability['Wednesday']['A4'] == true)
					{
						WednesdayMSG += "<li>11:30pm to 1:30pm</li>";
					}
					if( Availability['Wednesday']['A5'] == true)
					{
						WednesdayMSG += "<li>1:30pm to 3:15pm</li>";
					}
					if( Availability['Wednesday']['A6'] == true)
					{
						WednesdayMSG += "<li>3:25pm to 4:30pm</li>";
					}
					if( Availability['Wednesday']['A7'] == true)
					{
						WednesdayMSG += "<li>4:30pm onwards</li>";
					}
				}
				MSG += "<br />Wednesday: <ul>"+WednesdayMSG+"</ul>";
				var ThrusdayMSG = "";
				if( Availability['Thrusday']['ANOT'] == true )
				{
					ThrusdayMSG += "<li>Not Available.</li>"
				}
				else if (  Availability['Thrusday']['Anytime'] == true)
				{
					ThrusdayMSG += "<li>Available Anytime.</li>";
				}
				else
				{
					if( Availability['Thrusday']['A1'] == true)
					{
						ThrusdayMSG += "<li>Before 5:30am.</li>";
					}
					if( Availability['Thrusday']['A2'] == true)
					{
						ThrusdayMSG += "<li>5:30am to 9:00am</li>";
					}
					if( Availability['Thrusday']['A3'] == true)
					{
						ThrusdayMSG += "<li>9:00am to 11:30pm</li>";
					}
					if( Availability['Thrusday']['A4'] == true)
					{
						ThrusdayMSG += "<li>11:30pm to 1:30pm</li>";
					}
					if( Availability['Thrusday']['A5'] == true)
					{
						ThrusdayMSG += "<li>1:30pm to 3:15pm</li>";
					}
					if( Availability['Thrusday']['A6'] == true)
					{
						ThrusdayMSG += "<li>3:25pm to 4:30pm</li>";
					}
					if( Availability['Thrusday']['A7'] == true)
					{
						ThrusdayMSG += "<li>4:30pm onwards</li>";
					}
				}
				MSG += "<br />Thrusday: <ul>"+ThrusdayMSG+"</ul>";
				var FridayMSG = "";
				if( Availability['Friday']['ANOT'] == true )
				{
					FridayMSG += "<li>Not Available.</li>"
				}
				else if (  Availability['Friday']['Anytime'] == true)
				{
					FridayMSG += "<li>Available Anytime.</li>";
				}
				else
				{
					if( Availability['Friday']['A1'] == true)
					{
						FridayMSG += "<li>Before 5:30am.</li>";
					}
					if( Availability['Friday']['A2'] == true)
					{
						FridayMSG += "<li>5:30am to 9:00am</li>";
					}
					if( Availability['Friday']['A3'] == true)
					{
						FridayMSG += "<li>9:00am to 11:30pm</li>";
					}
					if( Availability['Friday']['A4'] == true)
					{
						FridayMSG += "<li>11:30pm to 1:30pm</li>";
					}
					if( Availability['Friday']['A5'] == true)
					{
						FridayMSG += "<li>1:30pm to 3:15pm</li>";
					}
					if( Availability['Friday']['A6'] == true)
					{
						FridayMSG += "<li>3:25pm to 4:30pm</li>";
					}
					if( Availability['Friday']['A7'] == true)
					{
						FridayMSG += "<li>4:30pm onwards</li>";
					}
				}
				MSG += "<br />Friday: <ul>"+FridayMSG+"</ul>";
				var SaturdayMSG = "";
				if( Availability['Saturday']['ANOT'] == true )
				{
					SaturdayMSG += "<li>Not Available.</li>"
				}
				else if (  Availability['Saturday']['Anytime'] == true)
				{
					SaturdayMSG += "<li>Available Anytime.</li>";
				}
				else
				{
					if( Availability['Saturday']['A1'] == true)
					{
						SaturdayMSG += "<li>Before 8:00am.</li>";
					}
					if( Availability['Saturday']['A2'] == true)
					{
						SaturdayMSG += "<li>8:00am to 2:00pm</li>";
					}
					if( Availability['Saturday']['A3'] == true)
					{
						SaturdayMSG += "<li>2:00am to 4:30pm</li>";
					}
					if( Availability['Saturday']['A4'] == true)
					{
						SaturdayMSG += "<li>4:30pm to onwards</li>";
					}
				}
				MSG += "<br />Saturday: <ul>"+SaturdayMSG+"</ul>";
				var SundayMSG = "";
				if( Availability['Sundays']['ANOT'] == true )
				{
					SundayMSG += "<li>Not Available.</li>"
				}
				else if (  Availability['Sundays']['Anytime'] == true)
				{
					SundayMSG += "<li>Available Anytime.</li>";
				}
				else
				{
					if( Availability['Sundays']['A1'] == true)
					{
						SundayMSG += "<li>Before 8:00am.</li>";
					}
					if( Availability['Sundays']['A2'] == true)
					{
						SundayMSG += "<li>8:00am to 2:00pm</li>";
					}
					if( Availability['Sundays']['A3'] == true)
					{
						SundayMSG += "<li>2:00pm to 4:30pm</li>";
					}
					if( Availability['Sundays']['A4'] == true)
					{
						SundayMSG += "<li>4:30pm onwards</li>";
					}
				}
				MSG += "<br />Sunday: <ul>"+SundayMSG+"</ul>";
				
			}catch(e)
			{
				MSG = "Notes: Availability has not been set yet."
			}
			
			
			
			$('#UserAvailabilityinner').empty().append( " "+ MSG );
		}

	});
	
</script>


<div id = "ProfileBox" >
	

	<div id = "Superprofilepic" >
		<h3> <?= $_SESSION['Firstname']?>   <?= $_SESSION['Lastname'] ?> </h3>



		<div id = "InnerProfilePic"> 
			
			
			
			<?php 		
				/* Refresh the image cache .*/
				$update = "";
				$id = $_SESSION['EmployeeID'] ;
				$path = "application/assets/img/UserProfilePics/".$id."/profilepic.jpg";
				$update = "";
				if (file_exists($path)) {
					$update = "?".filemtime($path);
				}							
			?>
			<img src ="<?= assetUrl(); ?>img/UserProfilePics/<?= $_SESSION['EmployeeID'] ?>/<?= $_SESSION['EmployeeID'] ?>_profilepic_thumb.jpg"   alt ="<?= $_SESSION['Firstname']?>   <?= $_SESSION['Lastname'] ?>" />	
			
			<div id = "ChangeProfiePic">Change Profile picture</div>
		
			
		</div>
	</div>
	
			<div id = "UploadProfilePic" class="modal" >
				<div id = "ModalContent" >		
					<div id = "close">x</div>
					<p>First choose a picture then click upload picture. Maximum size is 2MB. </p>
					<form action = "<?= base_Url(); ?>index.php/MyProfile/ProfilePicUpload" onsubmit = "return validateImg();" method="post" enctype="multipart/form-data" name = "imgForm" id = "imgForm">
						<input type = "file" name = "img" id = "img"/>
						<input type="hidden" value="<?= $_SESSION['EmployeeID'] ?>" name="ID" />
						<input type ="submit" value = "Upload Picture"  name="UploadPic" />
						
					</form>										
					<div id = "ProfileErrorBox"></div>				
				</div>
			</div>

			
			

	<table  border="1" id = "CRTtable">
		<thead>
			<tr><th><h3>Certifications</h3></th><th><h3>Yes or No</h3> </th> </tr>
		</thead>
		<tbody>
		<tr>
			<td>Lifeguard </td> <td> <?php if($_SESSION['Lifeguard']){ echo "Yes";}else{echo "No";} ?> </td>
		</tr>
		<tr>
			<td>Instructor </td> <td> <?php if($_SESSION['Instructor']){ echo "Yes";}else{echo "No";} ?> </td>
		</tr>
		<tr>
			<td>Headguard </td>  <td> <?php if($_SESSION['Headguard']){ echo "Yes";}else{echo "No";} ?> </td>
		</tr>
		<tr>
			<td>Supervisor </td> <td> <?php if($_SESSION['Supervisor']){ echo "Yes";}else{echo "No";} ?> </td>
		</tr>
		</tbody>
	</table>
	
	
	<div id = "NotificationBOX">
		<h3>Notifcations</h3>
		<div id= "NotificationBOXInner">				
			<!--<div id="notificationCell" class = "NewNotification"> Your Shift was rejected</div>
			<div id="notificationCell" class = "OldNotification"> Your Shift was accept</div>-->
		</div>
	</div>
	
	
	<div id = "UserAvailability">
	<h3>Availiability</h3>
		<p>
			<div id= "UserAvailabilityinner">	
			</div>
		</p>
	</div>
	

</div>