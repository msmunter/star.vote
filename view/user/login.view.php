<a href="/"><- Home</a><br />
<h4>Login</h4>
<form method="post" action="/user/dologin/" data-ajax="false">
	<input type="hidden" name="ajax" value="1" />

	<div class="ui-field-contain">
		<label for="email">Email:</label>
		<div class="timePicker">
			<input type="text" name="email" id="email" placeholder="user@mail.tld" />
		</div>
	</div>
	
	<div class="ui-field-contain">
		<label for="pass">Pass:</label>
		<div class="timePicker">
			<input type="password" name="pass" id="pass" />
		</div>
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
	
	<button type="submit" data-inline="true">Login</button>
</form>