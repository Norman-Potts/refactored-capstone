	<script type="text/javascript" >
	
		
	$(document).ready(function() {	
		
		//When a li is overed changed css.
		
		$( "#homeButton" ).addClass("NavNotHover");	
		$("#homeButton").hover(
			function() {
				$( "#homeButton" ).removeClass("NavNotHover");
				$( "#homeButton" ).addClass("NavOVER");
				
				
			}, function() {
				$( "#homeButton" ).removeClass("NavOVER");
				$( "#homeButton" ).addClass("NavNotHover");
			}
		);
		
		
		
		
		$( "#Schedulebutton" ).addClass("NavNotHover");	
		$("#Schedulebutton").hover(
			function() {
				$( "#Schedulebutton" ).removeClass("NavNotHover");
				$( "#Schedulebutton" ).addClass("NavOVER");
				
				
			}, function() {
				$( "#Schedulebutton" ).removeClass("NavOVER");
				$( "#Schedulebutton" ).addClass("NavNotHover");
			}
		);
		
		
		$( "#MyProfilebutton" ).addClass("NavNotHover");	
		$("#MyProfilebutton").hover(
			function() {
				$( "#MyProfilebutton" ).removeClass("NavNotHover");
				$( "#MyProfilebutton" ).addClass("NavOVER");
				
				
			}, function() {
				$( "#MyProfilebutton" ).removeClass("NavOVER");
				$( "#MyProfilebutton" ).addClass("NavNotHover");
			}
		);
		
		
				
		$( "#EditButton" ).addClass("EditButtonNotHover");	
		$("#EditButton").hover(
			function() {
				$( "#EditButton" ).removeClass("EditButtonNotHover");
				$( "#EditButton" ).addClass("EditButtonHover");
				
				
			}, function() {
				$( "#EditButton" ).removeClass("EditButtonHover");
				$( "#EditButton" ).addClass("EditButtonNotHover");
			}
		);
		
		
					
		$("#logoutButton" ).addClass("NoHoverlogoutButton");	
		$("#logoutButton").hover(
			function() {
				$( "#logoutButton" ).removeClass("NoHoverlogoutButton");
				$( "#logoutButton" ).addClass("HoverlogoutButton");
				
				
			}, function() {
				$( "#logoutButton" ).removeClass("HoverlogoutButton");
				$( "#logoutButton" ).addClass("NoHoverlogoutButton");
			}
		);
		
		
		SetNavNotificationCount();
		/** Function SetNavNotificationCount.
			Purpose: 
		*/
		function SetNavNotificationCount( )
		{						
			$.post('<?= base_url(); ?>index.php/Home/GetNewNotifications', 0, function(data)
			{	
				var NotificationCount = data;

				$('#NavNotificationCount').empty().append(NotificationCount );
			
			});;
		}/*End of Function SetNavNotificationCount*/
		
		
		
	});
	
	</script>
	<div id ="NavigationBar">		
		
		<!-- Home  link -->
		<a href ="<?= base_url(); ?>index.php?/Home" id= "homeButton"> Home </a>    				
		
		<!-- Schedule link-->
		<a href ="<?= base_url(); ?>index.php?/TheSchedulePage" id = "Schedulebutton">Schedule </a>		

		<!-- MyProfile Link -->
		<a href ="<?= base_url(); ?>index.php?/MyProfile" id = "MyProfilebutton">
				<p id = "pBlock">
					<span class = "NavTitleMyProfile"> MyProfile</span>
					<span class = "NavNameBox"><?= $_SESSION['Firstname']?>   <?= $_SESSION['Lastname'] ?></span>
					<span class = "NavNotification">New Notifications: <span id = "NavNotificationCount"></span>
				</p>
						<img src ="<?= assetUrl(); ?>img/UserProfilePics/<?= $_SESSION['EmployeeID'] ?>/<?= $_SESSION['EmployeeID'] ?>_profilepic_thumb.jpg"  class="" alt ="<?= $_SESSION['Firstname']?>   <?= $_SESSION['Lastname'] ?>" />																		
		</a>				
		
		
		<!-- Logout link -->
		 <a href = "<?= base_url()?>index.php/Home/logout"  > <div id="logoutButton"> <p>Logout</p></div></a>			
		
		
		<!-- If This is a supervisor account show theEditPage link. -->
		<? if ($_SESSION['Supervisor'] == true  ){   ?>				
			<a href ="<?= base_url(); ?>index.php?/TheEditPage"> <div id = "EditButton"><p> Supervisor's Controls </p></div></a>				
		<?}?>		
		
		 
	</div>
	<div id = "CenterBox" >