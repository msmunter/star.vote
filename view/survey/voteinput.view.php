<?php foreach ($this->survey->polls as $pollIndex => $poll) { ?>
	<div id="surveyPollContainer|<?php echo $pollIndex; ?>" class="surveyPollContainer<?php if ($pollIndex > 0) echo ' hidden'; ?>">
	<div class="surveyPollTitle"><?php echo ($pollIndex + 1).'. '.$poll->question; ?></div>
	<?php foreach ($poll->answers as $answer) { ?>
		<form class="voteForm">
			<legend class="voteLegend"><?php echo $answer->text; ?></legend>
			<input type="radio" name="radioVote|<?php echo $answer->answerID; ?>" id="radioVote|<?php echo $answer->answerID; ?>|0" value="0" data-role="none" />
			<label class="voteFormLabel emptystar" for="radioVote|<?php echo $answer->answerID; ?>|0">0</label>
			
			<input type="radio" name="radioVote|<?php echo $answer->answerID; ?>" id="radioVote|<?php echo $answer->answerID; ?>|1" value="1" data-role="none" />
			<label class="voteFormLabel fullstar" for="radioVote|<?php echo $answer->answerID; ?>|1">1</label>
			
			<input type="radio" name="radioVote|<?php echo $answer->answerID; ?>" id="radioVote|<?php echo $answer->answerID; ?>|2" value="2" data-role="none" />
			<label class="voteFormLabel fullstar" for="radioVote|<?php echo $answer->answerID; ?>|2">2</label>
			
			<input type="radio" name="radioVote|<?php echo $answer->answerID; ?>" id="radioVote|<?php echo $answer->answerID; ?>|3" value="3" data-role="none" />
			<label class="voteFormLabel fullstar" for="radioVote|<?php echo $answer->answerID; ?>|3">3</label>
			
			<input type="radio" name="radioVote|<?php echo $answer->answerID; ?>" id="radioVote|<?php echo $answer->answerID; ?>|4" value="4" data-role="none" />
			<label class="voteFormLabel fullstar" for="radioVote|<?php echo $answer->answerID; ?>|4">4</label>
			
			<input type="radio" name="radioVote|<?php echo $answer->answerID; ?>" id="radioVote|<?php echo $answer->answerID; ?>|5" value="5" data-role="none" />
			<label class="voteFormLabel fullstar" for="radioVote|<?php echo $answer->answerID; ?>|5">5</label>
		</form>
		<div class="clear"></div>
	<?php } ?>
	</div>
<?php } ?>
<div class="clear"></div>