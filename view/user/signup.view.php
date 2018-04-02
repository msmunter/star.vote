<div class="bigContainer">
	<div class="bigContainerTitle">New User Signup</div>
	<div class="bigContainerInner">	
		<div data-role="fieldcontain">
			<label for="email">First Name:</label>
			<input name="firstName" id="firstName" value="" />
		</div>
		
		<div data-role="fieldcontain">
			<label for="email">Last Name:</label>
			<input name="lastName" id="lastName" value="" />
		</div>
		
		<div data-role="fieldcontain">
			<label for="email">Email:</label>
			<input name="email" id="email" value="" />
		</div>
		
		<div data-role="fieldcontain">
			<label for="pass1">Password:</label>
			<input type="password" name="pass1" id="pass1" value="" />
		</div>
		<div data-role="fieldcontain">
			<label for="pass2">Verify Password:</label>
			<input type="password" name="pass2" id="pass2" value="" />
		</div>
		<!--<div class="ui-field-contain">
			<label for="adminLevel">Admin Level:</label>
			<select name="adminLevel" id="adminLevel" data-inline="true">
				<option value="0">None</option>
				<option selected value="3">Employee</option>
				<option value="2">Manager</option>
				<option value="1">Root Admin</option>
			</select>
		</div>-->
		<button type="button" data-inline="true" onclick="submitAddUserForm()">Add User</button>
	</div>
</div>