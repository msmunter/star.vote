<div id="voteTitle">
	Vote on "<?php echo $this->poll->question; ?>"
</div>

<?php foreach ($this->poll->answers as $answer) { ?>
	<form class="answerForm" id="voteForm|<?php echo $answer->answerID; ?>">
		<fieldset data-role="controlgroup" data-type="horizontal">
			<legend><?php echo $answer->text; ?></legend>
			<input type="radio" name="radioVote|<?php echo $answer->answerID; ?>" id="radioVote|<?php echo $answer->answerID; ?>|0" value="0" checked="checked">
			<label for="radioVote|<?php echo $answer->answerID; ?>|0">0</label>
			<input type="radio" name="radioVote|<?php echo $answer->answerID; ?>" id="radioVote|<?php echo $answer->answerID; ?>|1" value="1">
			<label for="radioVote|<?php echo $answer->answerID; ?>|1">1</label>
			<input type="radio" name="radioVote|<?php echo $answer->answerID; ?>" id="radioVote|<?php echo $answer->answerID; ?>|2" value="2">
			<label for="radioVote|<?php echo $answer->answerID; ?>|2">2</label>
			<input type="radio" name="radioVote|<?php echo $answer->answerID; ?>" id="radioVote|<?php echo $answer->answerID; ?>|3" value="3">
			<label for="radioVote|<?php echo $answer->answerID; ?>|3">3</label>
			<input type="radio" name="radioVote|<?php echo $answer->answerID; ?>" id="radioVote|<?php echo $answer->answerID; ?>|4" value="4">
			<label for="radioVote|<?php echo $answer->answerID; ?>|4">4</label>
			<input type="radio" name="radioVote|<?php echo $answer->answerID; ?>" id="radioVote|<?php echo $answer->answerID; ?>|5" value="5">
			<label for="radioVote|<?php echo $answer->answerID; ?>|5">5</label>
	    </fieldset>
	</form>
	<div class="clear"></div>
<?php } ?>
