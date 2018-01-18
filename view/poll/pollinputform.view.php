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
<div id="pollButtonContainer">
	<!--<button id="addAnswerButton" data-inline="inline" onclick="addAnswer()">Add Answer</button>-->
	<button id="createPollButton" data-inline="inline" onclick="createPoll()">Create Poll</button>
</div>