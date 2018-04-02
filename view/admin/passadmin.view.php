<a href="/admin/userdetails/<?php echo $this->userToUpdateID; ?>/"><- User</a><br />
<div id="statusMsg"></div>
<div class="clear"></div>
<h4>Update Password For "<?php echo $this->userDetails->firstName; ?> <?php echo $this->userDetails->lastName; ?>"</h4>
<input type="hidden" name="userToUpdateID" id="userToUpdateID" value="<?php echo $this->userToUpdateID; ?>" />

<div data-role="fieldcontain">
	<label for="pass1">Password:</label>
	<input type="password" name="pass1" id="pass1" value="" />
</div>
<div data-role="fieldcontain">
	<label for="pass2">Verify Password:</label>
	<input type="password" name="pass2" id="pass2" value="" />
</div>
<button type="button" id="changePassButton" data-inline="true" onclick="changePassAdmin()">Update Password</button>