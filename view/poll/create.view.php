<p>
	Create Poll
</p>
<div id="statusMsg" class="hidden"></div>
<div class="clear"></div>
<div class="ui-field-contain">
	<label for="pollQuestion">Question: </label>
	<input type="text" id="pollQuestion" name="pollQuestion" ></input>
</div>
<form id="pollAnswers">
	<?php 
		for ($qNum = 1;$qNum <= 3;$qNum++) {
			$this->answerID = $qNum;
			include('view/poll/pollinput.view.php');
		}
	?>
</form>
<button id="addAnswerButton" data-inline="inline" onclick="addAnswer()">Add Answer</button>
<button id="createPollButton" data-inline="inline" onclick="createPoll()">Create Poll</button>