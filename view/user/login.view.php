<div id="statusMsg"></div>
<div class="clear"></div>
<div class="bigContainer">
	<div class="bigContainerTitle">Login</div>
	<div class="bigContainerInner">
		<div class="ui-field-contain">
			<label for="email">User:</label>
			<input type="text" name="email" id="email" />
		</div>
		
		<div class="ui-field-contain">
			<label for="pass">Pass:</label>
			<input type="password" name="pass" id="pass" />
		</div>
		
		<div class="ui-field-contain">
			<label for="authLength">Persistent Login:</label>
			<select name="authLength" id="authLength" data-inline="true">
				<option selected value="0">Session Only</option>
				<option value="1">One Day</option>
				<!-- <option value="7">One Week</option>
				<option value="30">One Month</option>
				<option value="365">One Year</option> -->
			</select>
		</div>
		<div>
			By clicking "Accept & Login" below you agree to the following terms and conditions:
			<ul>
				<li>I understand and agree that all data and personal information provided or displayed to me while logged in to the STAR Elections Credentialing Committee application is private and personal data which is not to be shared, saved, displayed, sold, or used for any other purposes other than the credentialing of voters and the verifying of ballots cast in the Independent Party of Oregon primary and presidential preference poll.</li>
				<li>I understand and agree that all data and personal information provided or displayed to me while verifying voters using STAR Elections voter data in the Oregon Secretary of State’s “My Vote” application, is private and personal data which is not to be shared, saved, displayed, sold, or used for any other purposes other than the credentialing of voters and the verifying of ballots cast in the Independent Party of Oregon primary and presidential preference poll.</li>
			</ul>
			STAR Elections takes the privacy of voter data very seriously. Thank you for your cooperation and thank you for your help.
		</div>
		<div class="center">
			<button id="loginButton" data-inline="true" onclick="login()">Accept & Login</button>
		</div>
	</div>
</div>
