<div class="bgbox">
	<h4><a href="/admin/">Admin</a> &#8594; Create User</h4>
	
	<div data-role="fieldcontain">
		<label for="firstName">First Name:</label>
			<input name="firstName" id="firstName" value="" />
	</div>
	
	<div data-role="fieldcontain">
		<label for="lastName">Last Name:</label>
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
	<button data-inline="true" onclick="adminCreateUser()">Create User</button>
</div>