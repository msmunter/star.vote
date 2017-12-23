<p>
	Create Poll
</p>
<div id="statusMsg" class="hidden"></div>
<div class="clear"></div>
<p>
	Question: <input type="text" id="pollQuestion" name="pollQuestion" ></input>
</p>
<form id="pollAnswers">
	<?php 
		for ($qNum = 1;$qNum <= 3;$qNum++) {
			$this->answerID = $qNum;
			include('view/poll/pollinput.view.php');
		}
	?>
</form>
<button id="addAnswerButton" onclick="addAnswer()">Add Answer</button>
<button id="createPollButton" onclick="createPoll()">Create Poll</button>