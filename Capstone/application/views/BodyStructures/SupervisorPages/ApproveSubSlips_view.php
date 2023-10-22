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
	
		
		
		getAllFutureSubslips();
	
		
		
		/*	function getAllFutureSubslips
			purpose: gets all the future subslips that have been submitted.
		*/
		function getAllFutureSubslips()
		{
			var inputarr = {};
			$.post('<?= base_Url(); ?>index.php/ApproveSubslips/GetALLSubslips', inputarr, function(data)
			{	
				var AllSubslips = JSON.parse( data );			
				loadSubmittedSubslips(AllSubslips);
			});					
		}//End of getAllFuturesSubslips
	
	
	
	
	
		/* function loadSubmittedSubslips
			purpose: loads the submitted subslips into the InnerBoxOfAvialiableSubslips div.		
		*/
		function loadSubmittedSubslips( AllSubslips )
		{
			var MSG = "";		
			for(var Slip in AllSubslips )
			{
				var startTime = AllSubslips[Slip]["startTime"];
				startTime = convert24HourTo12hr(startTime);				
				var endTime =  AllSubslips[Slip]["endTime"];				
				endTime = convert24HourTo12hr(endTime);
				var Firstname = AllSubslips[Slip]["Firstname"];
				var Lastname = AllSubslips[Slip]["Lastname"];
				var ownerID =  AllSubslips[Slip]["CreatorID"];
				var ShiftID =  AllSubslips[Slip]["ShiftID"];
				var TakerID = AllSubslips[Slip]["TakerID"];
				var personTakingShift = AllSubslips[Slip]["personTakingShift"];
				var OwnerOfShift = Firstname+" "+Lastname ;
				var ShiftDate = AllSubslips[Slip]["ShiftDate"];
				ShiftDate = formatDate(ShiftDate);
				var Position = AllSubslips[Slip]["Position"];
				Position = ConvertNumberPosition(Position);
				var cdt = AllSubslips[Slip]["CreatedDateAndTime"];
				var CreatedTime = cdt.substring(11,19);
				var CreatedDate = formatDate( cdt.substring(0,10) );
				var subslipID =  AllSubslips[Slip]["subslipID"];
				
				var Reason = AllSubslips[Slip]["Reason"];
				
				var YesNo = AllSubslips[Slip]["TakenTrueorFalse"];
				if(YesNo == 0){	YesNo = "No";	}else{	YesNo = "Yes";	}
				
				MSG += "<div id = \"SubmittedSubslip\">";			
				MSG += "<div id = \"ApproveSubslipID\"  >"+subslipID+"</div>";
				MSG += "<div id = \"ApproveOwnerID\"  >"+ownerID+"</div>";
				MSG += "<div id = \"ApproveShiftID\"  >"+ShiftID+"</div>";
				MSG += "<div id = \"ApproveTakerID\"  >"+TakerID+"</div>";
				MSG += "Owner of Shift: "+OwnerOfShift+" <br>";
				MSG += "Person taking the shift: "+personTakingShift+" <br>";
				MSG += "Shift Date: "+ShiftDate+"<br>";
				MSG += "Position: "+Position+"<br>";
				MSG += "StartTime: "+startTime+" <br>";
				MSG += "EndTime: "+endTime+"<br>";
				MSG += "Created Date: "+CreatedDate+"<br>";
				MSG += "Reason: "+Reason+"<br>";
				MSG += "Taken? "+YesNo+" <br>";
				MSG += "<input type = \"button\"  value = \"Reject Sub Slip\" class=\"SSrejectButton\" >"
				MSG += "<input type = \"button\"  value = \"Accept Sub Slip\" class=\"SSacceptButton\" >"
				/*
					If sub slip is taken Print Yes and display
					the information appropriate for a taken sub slip					
				*/
				MSG += "</div>";		
			}							
			$('#InnerBoxOfAvialiableSubslips').empty();
			$('#InnerBoxOfAvialiableSubslips').append(MSG);
		}//end of function loadSubmittedSubslips
				
				
				
		/* When a reject button gets clicked 
				determine the id of the subslip.
				and reject the subslip.
		*/
		$('#InnerBoxOfAvialiableSubslips').on('click','.SSrejectButton', function(){
			var subslipID = $(this).siblings('#ApproveSubslipID').text();
			var TakerID = $(this).siblings('#ApproveTakerID').text();
			var ownerID = $(this).siblings('#ApproveOwnerID').text();
			var ShiftID = $(this).siblings('#ApproveShiftID').text();
			subSlipReject(subslipID,ownerID);//Delete the shift.
		});
	
		/*When the accept button gets clicked.*/
		$('#InnerBoxOfAvialiableSubslips').on('click','.SSacceptButton', function(){
			//When accpet button get clicked. 
			var subslipID = $(this).siblings('#ApproveSubslipID').text();			
			var TakerID = $(this).siblings('#ApproveTakerID').text();
			var ownerID = $(this).siblings('#ApproveOwnerID').text();
			var ShiftID = $(this).siblings('#ApproveShiftID').text();
			ShiftSwitch( subslipID, TakerID, ownerID, ShiftID );
			// Re assign the shift for this subslip
		});
	
	
	
	
	
	
	
	
	
		/** Function ShiftSwitch
			Purpose: runs an ajax call which switches shifts based on the given subslip contract.
		*/
		function ShiftSwitch( subslipID, TakerID, ownerID, ShiftID )
		{
			var inputarr = {};
			inputarr["subslipID"] = subslipID;
			inputarr["ownerID"] = ownerID;
			inputarr["TakerID"] = TakerID;
			inputarr["ShiftID"] = ShiftID;
			
			$.post('<?= base_Url(); ?>index.php/ApproveSubslips/DoSubslipSwitch', inputarr, function(data)
			{		
				//$('#Print').append(data);

				var instructions = JSON.parse( data );
				if( instructions == 1)
				{
					getAllFutureSubslips();		
					alert("The shift was switched and the employee was notified.");
					
				}
				else
				{
					alert("The shift was Not switched. There was an error doing the switch.");
					//Throw error page.
				}
				
			});		
			
		}/*End of Function ShiftSwitch */
	
	
	
	
	
	
	
	
	
	
		/** function subSlipReject
			purpose: this function manages the process of rejecting a subslip.
			what this function does:
				Opens up a dialog box/form to record the reason why the subslip has been rejected.				
				When form button confrim gets clicked the subslip is deleted and a message is sent to the employee. diaglog closes.
				When button cancel is clicked the subslip doesnt get deleted and no message is sent. diaglog closes.
			parameters: subslipID
		*/
		function subSlipReject(subslipID,ownerID)
		{
			//alert( subslipID+" "+ownerID );			
			var modal = $('#RejectSubslipModal');			
			var close = $('#close');			
			var cancel = $('#btnRejectCancel');
			var confirm = $('#btnRejectConfirm');
			modal.css({'display':"block"}); 									
			close.click(function(){
				modal.css({'display': "none"});
			});
			cancel.click(function(){
				modal.css({'display': "none"});
			});
			confirm.click(function(){
				var Rejectreason = "";
				Rejectreason = $('#Rejectreason').val();				
				if( Rejectreason == "") 
				{
					$('#NeedReason').append("You must have a reason for rejecting subslip.");
				}
				else
				{
					RunReject(subslipID,ownerID, Rejectreason );
				}
			});
			
			
		}/*End of Reject subslip function*/
		
		function RunReject(subslipID,ownerID,Rejectreason)
		{
			var inputarr = {};
			inputarr["subslipID"] = subslipID;
			inputarr["ownerID"] = ownerID;
			inputarr["Rejectreason"] = Rejectreason;
			
			$.post('<?= base_Url(); ?>index.php/ApproveSubslips/RejectSubslip', inputarr, function(data)
			{	
				
				var instructions = JSON.parse( data );
				if( instructions == 1)
				{
					$('#RejectSubslipModal').css({'display': "none"});
					getAllFutureSubslips();					
					alert("Subslip was deleted and the employee was notified.");
				}
				else
				{
					//Throw error page.
				}
				
			});		
			
		}/*End of function RunReject*/
		
		
		checkAutoApprove();			
		/** Function checkAutoApprove
				purpose: Checks the libary for the value of autoApprove. Sets check box accordinly.
		*/		
		function checkAutoApprove()
		{
			var inputarr = {};
			$.post('<?= base_Url(); ?>index.php/ApproveSubslips/checkAutoApprove', inputarr, function(data)
			{	
				var OnOff =  data ;		
				if (OnOff == 1)
				{					
					$('#AutoApproveSwitch').prop('checked', true);
				}
				else
				{
					$('#AutoApproveSwitch').prop('checked', false);
				}							
			});	
		}/*End of function checkAutoApprove*/		
		
		//When AutoApproveSwitch get switched
		$('#AutoApproveSwitch').click(function(){			
			chked = $('#AutoApproveSwitch').is(':checked');			
			if(chked == false)
			{
				var inputarr = {};
				$.post('<?= base_Url(); ?>index.php/ApproveSubslips/offAutoApprove', inputarr, function(data)
				{ });				
			}	
			else
			{				
				var inputarr = {};
				$.post('<?= base_Url(); ?>index.php/ApproveSubslips/onAutoApprove', inputarr, function(data)
				{ });	
			}									
			var inputarr = {};
			$.post('<?= base_Url(); ?>index.php/ApproveSubslips/checkAutoApprove', inputarr, function(data)
			{	
				var OnOff =  data;
			});						
		});
		
		
	});	
</script>

<div id = "ApproveSubslippage">
	<div id="RejectSubslipModal" class="modal">
		<div id ="RSModalcontent">
			<div id="close">&times;</div>
						
			<h3> Tell Employee why Subslip is being rejected. </h3>
			<form>
			<p id="NeedReason"></p>
			<textarea id = "Rejectreason" name="Rejectreason"  maxlength = "200" cols ="40" rows ="4" ></textarea>
			
			<input type = "button" id = "btnRejectConfirm" value = "Confirm"/>
			<input type = "button" id = "btnRejectCancel" value = "Cancel"/>
			<form>
		</div>
	</div>


	
	<h1>Approve Sub Slips</h1>
	
	<form method = "post" action = "" >
		<label for = "AutoApproveSwitch"> Auto Approve?</label>
		<input type = "checkbox" id = "AutoApproveSwitch" name = "AutoApproveSwitch" >
	</form>
			
	
	<div id = "BoxOfAvialiableSubslips" >
		<h3> Submitted Subslips That Have Been Taken. </h3>
		<div id = "InnerBoxOfAvialiableSubslips" >
		
		</div>
	</div>
			
			
			
			
</div><!--End of id ApproveSubslippage -->