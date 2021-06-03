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
			
			var display;		
			if ( this.props.selectedEmployee == null ){
				display = (  <AvailabilityForm  d = {true} selectedEmployee = { null }  selectedAvailability = {this.props.selectedAvailability }  /> );				
			} else {
				display = ( <AvailabilityForm  submitbutton = {this.props.submitbutton} handleCheckboxChange = {this.props.handleCheckboxChange}  d = {false} selectedEmployee = { this.props.selectedEmployee }  selectedAvailability = {this.props.selectedAvailability }  cancel = {this.props.cancel} NotesChange = {this.props.NotesChange}/> );								
			}						
			return (			
				<div>			
					{display}
				</div>
			)
		}
	}
		
	

	
	
	class AvailabilityForm extends React.Component {
		render() {									
			var EmployeeCertifications = '';
			var d = ''; var cancelbutton = ''; var submitbutton = '';
			var AvailabilityDayCls = 'AvailabilityDay '; var AvailabilityNotesCls = 'AvailabilityDay ';
			var Notes = 'Write extra notes about this employees availability here.';
			var Firstname = '';  var Lastname = '';						
			
			var selectedAvailability = this.props.selectedAvailability;				
			var Mondays = selectedAvailability.Mondays;
			var Tuesdays = selectedAvailability.Tuesday;
			var Wednesdays = selectedAvailability.Wednesday;
			var Thrusdays =selectedAvailability.Thrusday;
			var Fridays = selectedAvailability.Friday;
			var Saturdays = selectedAvailability.Saturday;
			var Sundays = selectedAvailability.Sundays;
			var Notes = selectedAvailability.Notes;
					
			
			if (this.props.d == false ) {
				var selectedEmployee = this.props.selectedEmployee; 
				Firstname = selectedEmployee.Firstname;  
				Lastname = selectedEmployee.Lastname;

				AvailabilityDayCls += 'lightgrey';
				AvailabilityNotesCls += 'lightgrey';
				cancelbutton = 'red';
				submitbutton = 'green'; 										
				var lifeguard, instructor,  headguard, supervisor;
				lifeguard = (this.props.selectedEmployee.Lifeguard == true) ?  'Yes' : 'No';
				instructor = (this.props.selectedEmployee.Instructor== true) ?  'Yes' : 'No';
				headguard = (this.props.selectedEmployee.Headguard == true) ?  'Yes' : 'No';
				supervisor = (this.props.selectedEmployee.Supervisor == true) ?  'Yes' : 'No';
				EmployeeCertifications = '  Lifeguard: '+lifeguard+', Instructor: '+instructor+',  Headguard: '+headguard+',  Supervisor: '+supervisor+'.'; 	

				
			} else {		
						
				d ='disabled'; 
				AvailabilityDayCls += 'grey';
				AvailabilityNotesCls += 'grey';
				cancelbutton = 'grey';
				submitbutton = 'grey';
			}
			
			
			
			return (
			
			
			<div>
			<div id = "AvailabilityForm" >			
					<h3 id = "SelectedEmployeeBox">
						<span className = "block"> Selected Employee: <span id = "appendEmployeeName">{ Firstname} { Lastname} </span> </span>
						<span className = "block"><span id = "EmployeeCertifications">Employee Certifications: {EmployeeCertifications}</span> </span>
					</h3>					
				<div id = "innerAvailabilityForm">							
					<div className = {AvailabilityDayCls} id = "MondayAvailability"><h3>Monday  </h3>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Mondays.A1, 'Mondays', 'A1')}                       className = "A1" disabled = {d} checked = {Mondays.A1} />  Before 5:30 am  </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Mondays.A2, 'Mondays', 'A2')}                      className = "A2" disabled = {d} checked = {Mondays.A2}/>  5:30am to 9:00 am    </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Mondays.A3, 'Mondays', 'A3')}                      className = "A3" disabled = {d} checked = {Mondays.A3}/>  9:00am to 11:30 am  </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Mondays.A4, 'Mondays', 'A4')}                      className = "A4" disabled = {d} checked = {Mondays.A4}/>  11:30am to 1:30 am  </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Mondays.A5, 'Mondays', 'A5')}                      className = "A5" disabled = {d} checked = {Mondays.A5}/>  1:30am to 3:15 am   </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Mondays.A6, 'Mondays', 'A6')}                      className = "A6" disabled = {d} checked = {Mondays.A6}/>  3:25am to 4:30 am   </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Mondays.A7, 'Mondays', 'A7')}                      className = "A7" disabled = {d} checked = {Mondays.A7}/>  4:30pm onwards      </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Mondays.ANOT, 'Mondays', 'ANOT')}             className = "ANOT" disabled = {d} checked = {Mondays.ANOT}/>  Not Availabile      </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Mondays.Anytime, 'Mondays', 'Anytime')}         className = "Anytime"disabled = {d} checked = {Mondays.Anytime}/>  Anytime       </label>						
					</div> 							
					<div className = {AvailabilityDayCls} id = "TuesdayAvailability"><h3>Tuesday </h3>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Tuesdays.A1, 'Tuesday', 'A1') }                 className = "A1" disabled = {d} checked = {Tuesdays.A1}/>  Before 5:30 am  </label>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Tuesdays.A2, 'Tuesday', 'A2')}                className = "A2" disabled = {d} checked = {Tuesdays.A2}/>  5:30am to 9:00 am    </label>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Tuesdays.A3, 'Tuesday', 'A3')}                className = "A3" disabled = {d} checked = {Tuesdays.A3}/>  9:00am to 11:30 am   </label>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Tuesdays.A4, 'Tuesday', 'A4')}                className = "A4" disabled = {d} checked = {Tuesdays.A4}/>  11:30am to 1:30 am  </label>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Tuesdays.A5, 'Tuesday', 'A5')}                className = "A5" disabled = {d} checked = {Tuesdays.A5}/>  1:30am to 3:15 am    </label>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Tuesdays.A6, 'Tuesday', 'A6')}                className = "A6" disabled = {d} checked = {Tuesdays.A6}/>  3:25am to 4:30 am    </label>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Tuesdays.A7, 'Tuesday', 'A7')}                className = "A7" disabled = {d} checked = {Tuesdays.A7}/>  4:30pm onwards       </label>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Tuesdays.ANOT, 'Tuesday', 'ANOT')}       className = "ANOT" disabled = {d} checked = {Tuesdays.ANOT}/>  Not Availabile       </label>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Tuesdays.Anytime, 'Tuesday', 'Anytime')}className = "Anytime"disabled = {d} checked = {Tuesdays.Anytime} />  Anytime       </label>
					</div> 				
					<div className = {AvailabilityDayCls} id = "WednesdayAvailability"><h3>Wednesday </h3>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Wednesdays.A1, 'Wednesday', 'A1') }                 className = "A1"disabled = {d} checked = {Wednesdays.A1}/>  Before 5:30 am  </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Wednesdays.A2, 'Wednesday', 'A2')}                 className = "A2"disabled = {d} checked = {Wednesdays.A2}/>  5:30am to 9:00 am    </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Wednesdays.A3, 'Wednesday', 'A3')}                 className = "A3"disabled = {d} checked = {Wednesdays.A3}/>  9:00am to 11:30 am   </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Wednesdays.A4, 'Wednesday', 'A4')}                 className = "A4"disabled = {d} checked = {Wednesdays.A4}/>  11:30am to 1:30 am  </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Wednesdays.A5, 'Wednesday', 'A5')}                 className = "A5"disabled = {d} checked = {Wednesdays.A5}/>  1:30am to 3:15 am    </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Wednesdays.A6, 'Wednesday', 'A6')}                 className = "A6"disabled = {d} checked = {Wednesdays.A6}/>  3:25am to 4:30 am    </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Wednesdays.A7, 'Wednesday', 'A7')}                 className = "A7"disabled = {d} checked = {Wednesdays.A7}/>  4:30pm onwards       </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Wednesdays.ANOT, 'Wednesday', 'ANOT')}        className = "ANOT"disabled = {d} checked = {Wednesdays.ANOT}/>  Not Availabile       </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Wednesdays.Anytime, 'Wednesday', 'Anytime')} className = "Anytime"disabled = {d}  checked = {Wednesdays.Anytime}/>  Anytime       </label>
					</div> 		
					<div className = {AvailabilityDayCls} id = "ThrusdayAvailability"><h3>Thrusday </h3>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Thrusdays.A1, 'Thrusday', 'A1') }                  className = "A1"disabled = {d}checked = {Thrusdays.A1}/>  Before 5:30 am  </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Thrusdays.A2, 'Thrusday', 'A2')}                 className = "A2"disabled = {d}checked = {Thrusdays.A2}/>  5:30am to 9:00 am    </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Thrusdays.A3, 'Thrusday', 'A3')}                 className = "A3"disabled = {d}checked = {Thrusdays.A3}/>  9:00am to 11:30 am   </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Thrusdays.A4, 'Thrusday', 'A4')}                 className = "A4"disabled = {d}checked = {Thrusdays.A4}/>  11:30am to 1:30 am  </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Thrusdays.A5, 'Thrusday', 'A5')}                 className = "A5"disabled = {d}checked = {Thrusdays.A5}/>  1:30am to 3:15 am    </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Thrusdays.A6, 'Thrusday', 'A6')}                 className = "A6"disabled = {d}checked = {Thrusdays.A6}/>  3:25am to 4:30 am    </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Thrusdays.A7, 'Thrusday', 'A7')}                 className = "A7"disabled = {d}checked = {Thrusdays.A7}/>  4:30pm onwards       </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Thrusdays.ANOT, 'Thrusday', 'ANOT')}        className = "ANOT"disabled = {d} checked = {Thrusdays.ANOT}/>  Not Availabile       </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Thrusdays.Anytime, 'Thrusday', 'Anytime')} className = "Anytime"disabled = {d}  checked = {Thrusdays.Anytime}/>  Anytime       </label>
					</div> 		
					<div className = {AvailabilityDayCls} id = "FridayAvailability"><h3>Friday </h3>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Fridays.A1, 'Friday', 'A1')}                 className = "A1"disabled = {d}checked = {Fridays.A1}/>  Before 5:30 am  </label>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Fridays.A2, 'Friday', 'A2')}                className = "A2"disabled = {d}checked = {Fridays.A2}/>  5:30am to 9:00 am    </label>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Fridays.A3, 'Friday', 'A3')}                className = "A3"disabled = {d}checked = {Fridays.A3}/>  9:00am to 11:30 am   </label>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Fridays.A4, 'Friday', 'A4')}                className = "A4"disabled = {d}checked = {Fridays.A4}/>  11:30am to 1:30 am  </label>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Fridays.A5, 'Friday', 'A5')}                className = "A5"disabled = {d}checked = {Fridays.A5}/>  1:30am to 3:15 am    </label>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Fridays.A6, 'Friday', 'A6')}                className = "A6"disabled = {d}checked = {Fridays.A6}/>  3:25am to 4:30 am    </label>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Fridays.A7, 'Friday', 'A7')}                className = "A7"disabled = {d}checked = {Fridays.A7}/>  4:30pm onwards       </label>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Fridays.ANOT, 'Friday', 'ANOT')}       className = "ANOT"disabled = {d}  checked = {Fridays.ANOT}/>  Not Availabile       </label>
						<label><input type = "checkbox"  onChange={() => this.props.handleCheckboxChange(Fridays.Anytime, 'Friday', 'Anytime')}className = "Anytime"disabled = {d} checked = {Fridays.Anytime}/>  Anytime       </label>
					</div> 		
					<div className = {AvailabilityDayCls} id = "SaturdayAvailability"><h3>Saturday </h3>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Saturdays.A1, 'Saturday', 'A1') }   className = "A1"disabled = {d} checked = {Saturdays.A1}/>  Before 8:00 am  </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Saturdays.A2, 'Saturday', 'A2')}   className = "A2"disabled = {d} checked = {Saturdays.A2}/>  8:00 am to 2:00pm   </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Saturdays.A3, 'Saturday', 'A3')}   className = "A3"disabled = {d} checked = {Saturdays.A3}/>  2:00pm to 4:30pm    </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Saturdays.A4, 'Saturday', 'A4')}   className = "A4"disabled = {d} checked = {Saturdays.A4}/>  4:30pm onwards       </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Saturdays.ANOT, 'Saturday', 'ANOT')} className = "ANOT"disabled = {d} checked = {Saturdays.ANOT}/>  Not Availabile       </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Saturdays.Anytime, 'Saturday', 'Anytime')} className = "Anytime"disabled = {d} checked = {Saturdays.Anytime}/>  Anytime       </label>
					</div> 		
					<div className = {AvailabilityDayCls} id = "SundayAvailability">
						<h3>Sunday </h3>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Sundays.A1, 'Sundays', 'A1') } className = "A1"disabled = {d}  checked = {Sundays.A1}/>  Before 8:00 am  </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Sundays.A2, 'Sundays', 'A2')} className = "A2"disabled = {d}  checked = {Sundays.A2}/>  8:00 am to 2:00pm   </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Sundays.A3, 'Sundays', 'A3')}className = "A3"disabled = {d}  checked = {Sundays.A3}/>  2:00pm to 4:30pm    </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Sundays.A4, 'Sundays', 'A4')}className = "A4"disabled = {d}  checked = {Sundays.A4}/>  4:30pm onwards       </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Sundays.ANOT, 'Sundays', 'ANOT')}className = "ANOT"disabled = {d} checked = {Sundays.ANOT}/>  Not Availabile       </label>
						<label><input type = "checkbox" onChange={() => this.props.handleCheckboxChange(Sundays.Anytime, 'Sundays', 'Anytime')} className = "Anytime"disabled = {d}  checked = {Sundays.Anytime}/>  Anytime       </label>
					</div> 				
					<div id = "AvailabilityNotes" className ={AvailabilityNotesCls}>
						<h3> Availability Notes </h3>
						<textarea rows="5" cols="50" maxLength = "200" id = "Anotes" disabled = {d}    value ={Notes} onChange={  this.props.NotesChange }/>						
					</div>		
					
					<input type = "button" className ={cancelbutton} defaultValue = "Cancel  " id = "CancelAvailiability" onClick = {this.props.cancel} disabled = {d} />					
					<input type = "button"  className ={submitbutton} defaultValue = "Submit Availiability" id = "SumitAvailiability" disabled = {d} onClick = {this.props.submitbutton}/>
				</div>
			</div>						
		</div>				
		
		
			);
		}
	}
		
	
	
	
	
	
	
	class EmployeeCell extends React.Component {
		state = {
			h: 'DELETEnotHover',	
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

 
	
	function DEFAULTAVAILABILITY() {		
		return  JSON.parse( '{"Mondays":{"A1":true,"A2":true,"A3":true,"A4":true,"A5":true,"A6":true,"A7":true,"ANOT":false,"Anytime":true},"Tuesday":{"A1":true,"A2":true,"A3":true,"A4":true,"A5":true,"A6":true,"A7":true,"ANOT":false,"Anytime":true},"Wednesday":{"A1":true,"A2":true,"A3":true,"A4":true,"A5":true,"A6":true,"A7":true,"ANOT":false,"Anytime":true},"Thrusday":{"A1":true,"A2":true,"A3":true,"A4":true,"A5":true,"A6":true,"A7":true,"ANOT":false,"Anytime":true},"Friday":{"A1":true,"A2":true,"A3":true,"A4":true,"A5":true,"A6":true,"A7":true,"ANOT":false,"Anytime":true},"Saturday":{"A1":true,"A2":true,"A3":true,"A4":true,"A5":true,"A6":true,"A7":true,"ANOT":false,"Anytime":true},"Sundays":{"A1":true,"A2":true,"A3":true,"A4":true,"A5":true,"A6":true,"A7":true,"ANOT":false,"Anytime":true},"Notes":"Availability has yet to be set. "} ');		
	}

			
	class AvailabilityCrud extends React.Component {
		state = {
			employees: [],
			selectedEmployee: null, 
			availability: DEFAULTAVAILABILITY(),
			p:'',
		}
		componentWillMount() {
			this.loadEmployees();
		}
		handleCheckboxChange = (checked, day, period) => {			
			var a  = this.state.availability;
			if ( period == 'ANOT' ) {
					a[day]["A1"] =  false;
					a[day]["A2"] = false;
					a[day]["A3"] = false;
					a[day]["A4"] = false;
					a[day]["ANOT"] = true
					a[day]["Anytime"] = false;
				if (day != 'Saturday' || day != 'Sundays') {
					a[day]["A5"] = false;
					a[day]["A6"] = false;
					a[day]["A7"] = false;					
				} 				
			} else if (period =='Anytime' ){
					a[day]["A1"] =  true;
					a[day]["A2"] = true;
					a[day]["A3"] = true;
					a[day]["A4"] = true;
					a[day]["ANOT"] = false;
					a[day]["Anytime"] = true;
				if (day != 'Saturday' || day != 'Sundays') {
					a[day]["A5"] = true;
					a[day]["A6"] = true;
					a[day]["A7"] = true;				
				} 
			} else {
				a[day]["ANOT"] = false;
				a[day]["Anytime"] = false;
				a[day][period] = !checked;											
			}		
			this.setState({ availability : a, });					
		}
		cancel = () => {
			this.setState({selectedEmployee: null,  selectedAvailability: DEFAULTAVAILABILITY(), });			
		}
		loadEmployees() {	
			var EmList = JSON.parse('<?php echo $EmList; ?>');
			this.setState({   employees: EmList  });																				
		}	  
		NotesChange = (event ) => {
			var a  = this.state.availability;
			a["Notes"] = event.target.value;
			this.setState({ availability:a });	
		}
		employeeSelect = (employee) => {
			var inputarr= {};
			inputarr['ID'] = employee.employeeID;					
			$.post('<?= base_Url(); ?>index.php/EmployeeAvailability/GetAvailability', inputarr, function(data) {	
				var Availability = JSON.parse( data );	
				if ( Availability == 'Nothing yet' ) {								
					Availability = DEFAULTAVAILABILITY();
				}					
				this.setState({
					selectedEmployee: employee,
					availability: Availability,					
				});				
			}.bind(this));
		}	
		submitbutton = () => {
			var a = this.state.availability;
			var ID = this.state.selectedEmployee.employeeID;
			var jAvailability = JSON.stringify( a );											
			var inputarr = {};			
			inputarr['ID'] = ID;
			inputarr['AvailabilityStr'] = jAvailability; 		
			$.post('<?= base_Url(); ?>index.php/EmployeeAvailability/SetAvailability', inputarr, function(data)
			{	
				if( data == 1 )
				{
					alert( "Availability update was succesful");
					this.setState({selectedEmployee: null,  selectedAvailability: DEFAULTAVAILABILITY(), });			
				}else{
					alert("Availability update had an error");
				}
			}.bind(this));			
		}
		render() {
			
			return (
				<div>	
					<EmployeeList employees = {this.state.employees} employeeSelect = {this.employeeSelect} />					
					<ControlAvailabilityForm submitbutton = {this.submitbutton} handleCheckboxChange = {this.handleCheckboxChange}  selectedEmployee = {this.state.selectedEmployee} selectedAvailability = {this.state.availability}   cancel = {this.cancel} NotesChange = {this.NotesChange}/>										
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
			