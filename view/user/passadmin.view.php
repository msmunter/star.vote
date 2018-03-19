<a href="/user/"><- Users</a><br />
<h4>Update Password</h4>
<form method="post" action="/user/insertpassadmin/" data-ajax="false">
	<input type="hidden" name="userToUpdateID" value="<?php echo $this->userToUpdateID; ?>" />
	<input type="hidden" name="ajax" value="1" />
	
	<div data-role="fieldcontain">
		<label for="pass1">* Password:</label>
		<input type="password" name="pass1" id="pass1" value="" />
	</div>
	<div data-role="fieldcontain">
		<label for="pass2">* Verify Password:</label>
		<input type="password" name="pass2" id="pass2" value="" />
	</div>
	<p>* Required Fields</p>
	<button type="submit" data-inline="true">Update Password</button>
</form>