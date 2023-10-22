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
		$( ".DELETEEmployeeCell" ).addClass("DELETEnotHover");		
		$(".DELETEEmployeeCell").hover(
			function() {
				$( this ).removeClass("DELETEnotHover");
				$( this ).addClass("DELETEliOVER");								
			}, function() {
				$( this ).removeClass("DELETEliOVER");
				$( this ).addClass("DELETEnotHover");
			}
		);										
});
</script>


<div id= "DeleteEmployeepage" >

<div id = "DELETEEmployeeList" >
<h3> Employees </h3>
	<div id = "DELETEEmployeeListInner">
		<?php	//for each employee in list display firstname and lastname.			
			foreach( $EmList as $row ) { 	
		?>
			<a href ="<?= base_url(); ?>index.php?/DeleteEmployee/LoadForm/<?= $row ['employeeID'];?>">
				<div class = "DELETEEmployeeCell">
				<span class = "MakeLeft" > <?= $row['Firstname']; ?> <?= $row['Lastname']; ?> </span>
				</div>
			</a>  
		<?php	
			}	
		?>
		</div>
</div>


	

	<form id = "DeleteForm" >
		<h1>Delete an Employee</h1>
		
		Username: <input type = "text" name = "username" value = "<?php if( !empty($EmForm) ) { echo $EmForm['Username']; }?>";  disabled /> <br> 
		Firstname: <input type = "text" name = "Firstname" value = "<?php  if( !empty($EmForm) ) { echo $EmForm['Firstname']; } ?>" disabled /> <br> 
		Lastname: <input type = "text" name = "Lastname"   value = "<?php  if( !empty($EmForm) ) { echo $EmForm['Lastname']; } ?>" 	disabled /> <br> 
		<br>
		<?php if( !empty($EmForm) ){ ?>
		Lifeguard <input type = "checkbox"  name = "Lifeguard" 			<?php if ($EmForm['Lifeguard']){ echo "checked";} ?>	disabled /><br> 
		Instructor <input type = "checkbox"  name = "Instructor" 		<?php if ($EmForm['Instructor']){ echo "checked";} ?>	disabled /><br> 
		Headguard <input type = "checkbox" name = "Headguard" 			<?php if ($EmForm['Headguard']){ echo "checked";} ?>	disabled /><br> 
		Supervisor <input type = "checkbox" name = "Supervisor" 		<?php if ($EmForm['Supervisor']){ echo "checked";} ?>	disabled /><br> 
		<?php }?>
		<br>
		<br>
		
		
		<?php if( !empty($EmForm) ){ ?>		
			<div id = "confirmDelete">
				<p> If you are sure you wish to delete this employee click the red delete button. <strong> Their shifts will still exist but be owned by nobody.</strong></p>
				<br> 
				<a href = "<?= base_url(); ?>index.php?/DeleteEmployee/DeleteThisEmployee/<?= $EmForm['employeeID']; ?>" id = "DeleteButton"> <p> DELETE </p></a>
				<a href = "<?= base_url(); ?>index.php?/DeleteEmployee" id = "CancelDeleteButton"> <p> CANCEL </p></a>
			</div>	
		<?php }?>
		
		<?php if (!empty($msg)){?>
			<div id= "DeleteSuccessORfailbox" >
				<p><?= $msg ?></p>
			</div>
		<?php }?>
	
	</form>
	
	


	
	
	<?php if( !empty($EmForm) ){ ?>				
		<div id = "ThisEmployeezShiftsBox"> 
			<div id = "ThisEmployeezShiftsList"> 
				<h4>This employee's shifts</h4>
			<?php	if (!empty($ShiftsThisEmployeeHas) )
				{
					//for each in $ShiftsThisEmployeeHas display its data in a cell.
					foreach( $ShiftsThisEmployeeHas as $Shift => $Shifts )
					{
						
						$Position = $ShiftsThisEmployeeHas[$Shift]["Position"];
						$startTime = $ShiftsThisEmployeeHas[$Shift]["startTime"];
						$endTime = $ShiftsThisEmployeeHas[$Shift]["endTime"];
						$date = $ShiftsThisEmployeeHas[$Shift]["DATE"];
						
						
						$ShiftTypeS = ["null", "Lifeguard", "Instructor", "Headguard", "Supervisor" ];
						$ShiftType = $ShiftTypeS[$Position];
						
						$MSG = "<p> Position: ".$ShiftType." </br>       Date: ".$date." </br> StartTime: ".$startTime." </br> EndTime: ".$endTime." ";
						
						?>
							<div id = "DeleteShiftCell"  > 	<?php echo $MSG; ?>	 </div>		
						<?php
					}
				}
				else
				{
					?>
						<div id = "DeleteShiftCell"  > They have no shifts. </div>		
					<?php
				}?>
			</div>
		</div>
	<?php }?>
	
	
	
	
</div>