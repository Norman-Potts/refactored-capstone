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
		//When a li is overed changed css.	
		$( "li" ).addClass("notHover");		
		$("li").hover(
			function() {
				$( this ).removeClass("notHover");
				$( this ).addClass("liOVER");								
			}, function() {
				$( this ).removeClass("liOVER");
				$( this ).addClass("notHover");
			}
		);		
	});
</script>


			<div id ="SupervisorControls" >	
				<h1> Supervisor's Controls</h1>
				
				<nav id = "ManageShiftsNav"> <h2>Manage Shifts</h2>
					<a href = "<?= base_url();?>index.php?/CreateAShift"><li>Create a shift    </li></a>
					<a href = "<?= base_url();?>index.php?/DeleteAShift"><li>Delete a shift    </li></a>					
					<a href = "<?= base_url();?>index.php?/ApproveSubSlips"><li>Approve sub slips   </li></a>					
				</nav>						
					
				<nav id = "ManageEmployeesNav"> <h2>Manage Employees</h2>
					<a href = "<?= base_url();?>index.php?/CreateNewEmployee"><li>Create a new employee    </li></a>					
					<a href = "<?= base_url();?>index.php?/UpdateEmployee"><li>Update Employee    </li></a>
					<a href = "<?= base_url();?>index.php?/DeleteEmployee"><li>Delete employee    </li></a>
					<a href = "<?= base_url();?>index.php?/EmployeeAvailability"><li>Change an employee's availability    </li></a>
				</nav>

			</div>