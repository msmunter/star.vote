<div class="bigContainer">
	<div class="bigContainerTitle">Login</div>
	<div class="bigContainerInner">
		<input type="hidden" name="ajax" value="1" />

		<div class="ui-field-contain">
			<label for="email">Email:</label>
			<input type="text" name="email" id="email" placeholder="user@yourmail.domain" />
		</div>
		
		<div class="ui-field-contain">
			<label for="pass">Pass:</label>
			<input type="password" name="pass" id="pass" />
		</div>
		
		<div class="ui-field-contain">
			<label for="authLength">Persistent Login:</label>
			<select name="authLength" id="authLength" data-inline="true">
				<option value="0">Session Only</option>
				<option selected value="1">One Day</option>
				<option value="7">One Week</option>
				<option value="30">One Month</option>
				<option value="365">One Year</option>
			</select>
		</div>
		<div class="center">
			<button data-inline="true" onclick="ajaxLogin()">Login</button>
		</div>
	</div>
</div>
