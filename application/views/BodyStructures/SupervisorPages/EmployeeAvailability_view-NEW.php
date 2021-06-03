<?php
	/// If the currently logged in user doesn't have Supervisor certification they shouldn't be allowed to view this page.
	if ( $_SESSION['Supervisor'] == false ) {
		$page = base_url() . "index.php?/Home";
		$this->userauth->redirect($page);
	}			
?>
 

<script src="https://unpkg.com/react@17/umd/react.development.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js" crossorigin></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>



<script type="text/babel"> 	 
	
 
	class ControlAvailabilityForm extends React.Component {
		state = {
			
		}
		render() {
			console.log(this.props.selectedAvailability); 
			var display;		
			if ( this.props.selectedAvailability == null ){
				display = (  <AvailabilityForm  d = {true} selectedEmployee = { null }  selectedAvailability = {null } /> );				
			} else {
				display = ( <AvailabilityForm  d = {false} selectedEmployee = { this.props.selectedEmployee }  selectedAvailability = {this.props.selectedAvailability } /> );								
			}						
			return (			
				<div>			
					{display}
				</div>
			)
		}
	}
		
	
	
	
	
	class AvailabilityForm extends React.Component {
		state = {
			
		}
		render() {
			var selectedEmployee =  '';
			var selectedAvailability = '';
		
			var EmployeeCertifications = '';
			if (this.props.d == false ) {
				var selectedEmployee = this.props.selectedEmployee;
				var selectedAvailability = this.props.selectedAvailability;				
				var lifeguard, instructor,  headguard, supervisor;
				lifeguard = (this.props.selectedEmployee.Lifeguard == true) ?  'Yes' : 'No';
				instructor = (this.props.selectedEmployee.Instructor== true) ?  'Yes' : 'No';
				headguard = (this.props.selectedEmployee.Headguard == true) ?  'Yes' : 'No';
				supervisor = (this.props.selectedEmployee.Supervisor == true) ?  'Yes' : 'No';
				EmployeeCertifications = '  LG: '+lifeguard+' INST: '+instructor+'  HG: '+headguard+'  Sup: '+supervisor+'';
				
			} else {
				var selectedEmployee = '';
				var selectedAvailability = '';
				
			}
			
			return (
			
			
			<div>
			<div id = "AvailabilityForm" >			
					<h3 id = "SelectedEmployeeBox">
						<span className = "block"> Selected Employee: <span id = "appendEmployeeName">{selectedEmployee.Firstname} {selectedEmployee.Lastname} </span><span id ="SshiftID">{selectedEmployee.employeeID}</span> </span>
						<span className = "block"><span id = "EmployeeCertifications">Employee Certifications: {EmployeeCertifications}</span> </span>
					</h3>					
				<div id = "innerAvailabilityForm">		
					<div className = "AvailabilityDay" id = "MondayAvailability"><h3>Monday  </h3>
						<label><input type = "checkbox" className = "A1"/>  Before 5:30 am  </label>
						<label><input type = "checkbox" className = "A2"/>  5:30am to 9:00 am    </label>
						<label><input type = "checkbox" className = "A3"/>  9:00am to 11:30 am  </label>
						<label><input type = "checkbox" className = "A4"/>  11:30am to 1:30 am  </label>
						<label><input type = "checkbox" className = "A5"/>  1:30am to 3:15 am   </label>
						<label><input type = "checkbox" className = "A6"/>  3:25am to 4:30 am   </label>
						<label><input type = "checkbox" className = "A7"/>  4:30pm onwards      </label>
						<label><input type = "checkbox" className = "ANOT"/>  Not Availabile      </label>
						<label><input type = "checkbox" className = "Anytime"/>  Anytime       </label>						
					</div> 							
					<div className = "AvailabilityDay" id = "TuesdayAvailability"><h3>Tuesday </h3>
						<label><input type = "checkbox" className = "A1"/>  Before 5:30 am  </label>
						<label><input type = "checkbox" className = "A2"/>  5:30am to 9:00 am    </label>
						<label><input type = "checkbox" className = "A3"/>  9:00am to 11:30 am   </label>
						<label><input type = "checkbox" className = "A4"/>  11:30am to 1:30 am  </label>
						<label><input type = "checkbox" className = "A5"/>  1:30am to 3:15 am    </label>
						<label><input type = "checkbox" className = "A6"/>  3:25am to 4:30 am    </label>
						<label><input type = "checkbox" className = "A7"/>  4:30pm onwards       </label>
						<label><input type = "checkbox" className = "ANOT"/>  Not Availabile       </label>
						<label><input type = "checkbox" className = "Anytime"/>  Anytime       </label>
					</div> 				
					<div className = "AvailabilityDay" id = "WednesdayAvailability"><h3>Wednesday </h3>
						<label><input type = "checkbox" className = "A1"/>  Before 5:30 am  </label>
						<label><input type = "checkbox" className = "A2"/>  5:30am to 9:00 am    </label>
						<label><input type = "checkbox" className = "A3"/>  9:00am to 11:30 am   </label>
						<label><input type = "checkbox" className = "A4"/>  11:30am to 1:30 am  </label>
						<label><input type = "checkbox" className = "A5"/>  1:30am to 3:15 am    </label>
						<label><input type = "checkbox" className = "A6"/>  3:25am to 4:30 am    </label>
						<label><input type = "checkbox" className = "A7"/>  4:30pm onwards       </label>
						<label><input type = "checkbox" className = "ANOT"/>  Not Availabile       </label>
						<label><input type = "checkbox" className = "Anytime"/>  Anytime       </label>
					</div> 		
					<div className = "AvailabilityDay" id = "ThrusdayAvailability"><h3>Thrusday </h3>
						<label><input type = "checkbox" className = "A1"/>  Before 5:30 am  </label>
						<label><input type = "checkbox" className = "A2"/>  5:30am to 9:00 am    </label>
						<label><input type = "checkbox" className = "A3"/>  9:00am to 11:30 am   </label>
						<label><input type = "checkbox" className = "A4"/>  11:30am to 1:30 am  </label>
						<label><input type = "checkbox" className = "A5"/>  1:30am to 3:15 am    </label>
						<label><input type = "checkbox" className = "A6"/>  3:25am to 4:30 am    </label>
						<label><input type = "checkbox" className = "A7"/>  4:30pm onwards       </label>
						<label><input type = "checkbox" className = "ANOT"/>  Not Availabile       </label>
						<label><input type = "checkbox" className = "Anytime"/>  Anytime       </label>
					</div> 		
					<div className = "AvailabilityDay" id = "FridayAvailability"><h3>Friday </h3>
						<label><input type = "checkbox" className = "A1"/>  Before 5:30 am  </label>
						<label><input type = "checkbox" className = "A2"/>  5:30am to 9:00 am    </label>
						<label><input type = "checkbox" className = "A3"/>  9:00am to 11:30 am   </label>
						<label><input type = "checkbox" className = "A4"/>  11:30am to 1:30 am  </label>
						<label><input type = "checkbox" className = "A5"/>  1:30am to 3:15 am    </label>
						<label><input type = "checkbox" className = "A6"/>  3:25am to 4:30 am    </label>
						<label><input type = "checkbox" className = "A7"/>  4:30pm onwards       </label>
						<label><input type = "checkbox" className = "ANOT"/>  Not Availabile       </label>
						<label><input type = "checkbox" className = "Anytime"/>  Anytime       </label>
					</div> 		
					<div className = "AvailabilityDay" id = "SaturdayAvailability"><h3>Saturday </h3>
						<label><input type = "checkbox" className = "A1"/>  Before 8:00 am  </label>
						<label><input type = "checkbox" className = "A2"/>  8:00 am to 2:00pm   </label>
						<label><input type = "checkbox" className = "A3"/>  2:00pm to 4:30pm    </label>
						<label><input type = "checkbox" className = "A4"/>  4:30pm onwards       </label>
						<label><input type = "checkbox" className = "ANOT"/>  Not Availabile       </label>
						<label><input type = "checkbox" className = "Anytime"/>  Anytime       </label>
					</div> 		
					<div className = "AvailabilityDay" id = "SundayAvailability">
						<h3>Sunday </h3>
						<label><input type = "checkbox" className = "A1"/>  Before 8:00 am  </label>
						<label><input type = "checkbox" className = "A2"/>  8:00 am to 2:00pm   </label>
						<label><input type = "checkbox" className = "A3"/>  2:00pm to 4:30pm    </label>
						<label><input type = "checkbox" className = "A4"/>  4:30pm onwards       </label>
						<label><input type = "checkbox" className = "ANOT"/>  Not Availabile       </label>
						<label><input type = "checkbox" className = "Anytime"/>  Anytime       </label>
					</div> 				
					<div id = "AvailabilityNotes" className ="AvailabilityDay">
						<h3> Availability Notes </h3>
						<textarea rows="5" cols="50" maxlength = "200" id = "Anotes" >
						Write extra notes about this employees availability here.
						</textarea>		
					</div>		
					<input type = "button" value = "SubmitAvailiability" id = "SumitAvailiability" /> 
				</div>
			</div>						
		</div>				
		
		
			);
		}
	}
		
	
	
	
	
	
	
	class EmployeeCell extends React.Component {
		state = {
			h: 'DELETEnotHover'	,	
		}			
		mouseenter = () => {
			this.setState({ h: 'DELETEliOVER' });			
		}
		mouseleave  = () => {
			this.setState({ h: 'DELETEnotHover' });		
		}
		render() { 			
			var cls = 'DELETEEmployeeCell '+this.state.h;					
			return ( 							
				<div className = {cls}  onClick = {  () =>  this.props.employeeSelect(this.props.employee)}  onMouseEnter = {this.mouseenter} onMouseLeave = {this.mouseleave}  > 						 
					<span className = "MakeLeft" > {this.props.employee.Firstname} {this.props.employee.Lastname} </span>				 
				</div>
			);
		}
	}


		
	//// Begining of list component
	class EmployeeList extends React.Component {
		render() { 			
			return ( 		
			<div id = "DELETEEmployeeList" >
				<h3> Employees </h3>
				<div id = "DELETEEmployeeListInner">		
					{this.props.employees.map((employee) => (  								
				  	  <EmployeeCell  employee = {employee} employeeSelect = {this.props.employeeSelect}  key ={employee.employeeID} />
				  	))}									
				</div>		
			</div>								
			);
		}
	}
	//// End of employeeList.




			
	class AvailabilityCrud extends React.Component {
		state = {
			employees: [],
			selectedEmployee: null, 
			selectedAvailability: null,
		}
		componentWillMount() {
			this.loadEmployees();
		}
		loadEmployees() {	
			var EmList = JSON.parse('<?php echo $EmList; ?>');
			this.setState({   employees: EmList  });																				
		}	   
		employeeSelect = (employee) => {
			var inputarr= {};
			inputarr['ID'] = employee.employeeID;		
			
			$.post('<?= base_Url(); ?>index.php/EmployeeAvailability/GetAvailability', inputarr, function(data) {	
				var Availability = JSON.parse( data );					
				this.setState({
					selectedEmployee: employee,
					selectedAvailability: Availability,					
				});				
			}.bind(this));
		}		
		render() {
			return (
				<div id="TodoPage">	
					<ControlAvailabilityForm selectedEmployee = {this.state.selectedEmployee} selectedAvailability = {this.state.selectedAvailability} />
					<EmployeeList employees = {this.state.employees} employeeSelect = {this.employeeSelect} />					
				</div> 
			);
	  }
	}

 
	

    ReactDOM.render ( <AvailabilityCrud />, document.getElementById("app") );
	
	
</script>
<div id ="AvailabilityCRUDPage">
	<h1>Availability Manager </h1>
	<div id = 'app' >
	
	
	</div>
</div>
			