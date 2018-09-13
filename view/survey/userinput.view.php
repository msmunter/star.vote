<?php if ($this->voter->userInfo->email) { ?>
	<input type="hidden" name="haveUserInfo" id="haveUserInfo" value="1" />
	<div>Welcome back, <?php echo $this->voter->userInfo->fname; ?></div>
<?php } else { ?>
	<input type="hidden" name="haveUserInfo" id="haveUserInfo" value="0" />
	<div class="ui-field-contain">
		<label for="fname">First Name:</label>
		<input type="text" data-clear-btn="true" id="fname" name="fname" placeholder=""></input>
	</div>

	<div class="ui-field-contain">
		<label for="lname">Last Name:</label>
		<input type="text" data-clear-btn="true" id="lname" name="lname" placeholder=""></input>
	</div>

	<div class="ui-field-contain">
		<label for="email">Email:</label>
		<input type="text" data-clear-btn="true" id="email" name="email" placeholder=""></input>
	</div>

	<div class="ui-field-contain">
		<label for="mailingList">Ok to email me?:</label>
		<select name="mailingList" id="mailingList" data-role="flipswitch">
			<option value="1">Yes</option>
			<option value="0">No</option>
		</select>
	</div>
<?php } ?>