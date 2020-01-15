<?php if ($this->user->userID > 0 && $this->user->userID == $this->survey->userID) { ?>
	<?php // Nothing here if it's your poll ?>
<?php } else { ?>
	<div class="ui-field-contain">
		<label for="fname">First Name:</label>
		<!-- <input type="text" data-clear-btn="true" id="fname" name="fname" required="1" value="Dude"></input> -->
		<input type="text" data-clear-btn="true" id="fname" name="fname" required="1"></input>
	</div>
	<div class="ui-field-contain">
		<label for="lname">Last Name:</label>
		<!-- <input type="text" data-clear-btn="true" id="lname" name="lname" required="1" value="Guy"></input> -->
		<input type="text" data-clear-btn="true" id="lname" name="lname" required="1"></input>
	</div>
	<div class="ui-field-contain">
		<label for="street">Street:</label>
		<!-- <input type="text" data-clear-btn="true" id="street" name="street" required="1" value="123 Street Ave"></input> -->
		<input type="text" data-clear-btn="true" id="street" name="street" required="1"></input>
	</div>
	<div class="ui-field-contain">
		<label for="city">City:</label>
		<!-- <input type="text" data-clear-btn="true" id="city" name="city" required="1" value="Eugene"></input> -->
		<input type="text" data-clear-btn="true" id="city" name="city" required="1"></input>
	</div>
	<div class="ui-field-contain">
		<label for="state">State:</label>
		<!-- <input type="text" data-clear-btn="true" id="state" name="state" required="1" value="OR"></input> -->
		<input type="text" data-clear-btn="true" id="state" name="state" required="1"></input>
	</div>
	<div class="ui-field-contain">
		<label for="zip">Zip:</label>
		<!-- <input type="number" data-clear-btn="true" id="zip" name="zip" required="1" value="97405" min="10000" max="99999"></input> -->
		<input type="number" data-clear-btn="true" id="zip" name="zip" required="1" min="10000" max="99999"></input>
	</div>
	<div class="ui-field-contain">
		<label for="birthyear">Birthyear:</label>
		<!-- <input type="number" data-clear-btn="true" id="birthyear" name="birthyear" required="1" value="1999" min="1900" max="2002"></input> -->
		<input type="number" data-clear-btn="true" id="birthyear" name="birthyear" required="1" min="1900" max="2002"></input>
	</div>
	<div class="ui-field-contain">
		<label for="phone">Phone:</label>
		<!-- <input type="tel" data-clear-btn="true" id="phone" name="phone" required="1" value="541-555-1212"></input> -->
		<input type="tel" data-clear-btn="true" id="phone" name="phone" required="1"></input>
	</div>
	<div class="ui-field-contain">
		<label for="email">Email:</label>
		<!-- <input type="email" data-clear-btn="true" id="email" name="email" required="1" value="dude@mail.com"></input> -->
		<input type="email" data-clear-btn="true" id="email" name="email" required="1"></input>
	</div>
<?php } ?>