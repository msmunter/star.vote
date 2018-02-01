<div id="statusMsg" class="hidden"></div>
<div class="clear"></div>
<div class="ui-field-contain">
	<label for="pollQuestion">Poll Question:</label>
	<input type="text" data-clear-btn="true" id="pollQuestion" name="pollQuestion" placeholder="What is this poll about?"></input>
</div>
<form id="pollAnswers">
	<?php 
		for ($qNum = 1;$qNum <= 3;$qNum++) {
			$this->answerID = $qNum;
			include('view/poll/pollinput.view.php');
		}
	?>
</form>
<div class="ui-field-contain">
	<label for="fsPrivate">Make Private:</label>
	<select name="fsPrivate" id="fsPrivate" data-role="flipswitch">
		<option value="0">No</option>
		<option value="1">Yes</option>
	</select>
</div>
<div class="ui-field-contain">
	<label for="fsRandomOrder">Randomize Answers:</label>
	<select name="fsRandomOrder" id="fsRandomOrder" data-role="flipswitch">
		<option value="0">No</option>
		<option selected value="1">Yes</option>
	</select>
</div>
<div class="ui-field-contain">
	<label for="fsCustomSlugSwitch">Custom URL Slug:</label>
	<select name="fsCustomSlugSwitch" id="fsCustomSlugSwitch" data-role="flipswitch">
		<option selected value="0">No</option>
		<option value="1">Yes</option>
	</select>
</div>
<div class="ui-field-contain" id="customSlugInputContainer">
	<label for="fsCustomSlugInput">URL Slug (a-z, 0-9):</label>
	<input type="text" data-clear-btn="true" class="pollAnswer" id="fsCustomSlugInput" name="fsCustomSlugInput" placeholder="abcd1234"></input><br />
</div>
<div id="pollButtonContainer">
	<button id="createPollButton" data-inline="inline" onclick="createPoll()">Create Poll</button>
</div>