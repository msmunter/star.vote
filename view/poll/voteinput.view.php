<?php if ($poll->numWinners > 1) echo '<div id="numWinnersContainer">Taking '.$poll->numWinners.' winners</div>'; ?>
<?php 
$answerSet = $this->poll->answers;
if ($this->poll->randomAnswerOrder > 0) shuffle($answerSet);
foreach ($answerSet as $answer) { ?>
	<form class="voteForm" id="voteForm|<?php echo $answer->answerID; ?>">
		<?php if ($answer->imgur == 1) { ?>
			<legend class="voteLegend">
				<a href="<?php echo $answer->text; ?>" target="_new">
					<img class="legendImg" src="<?php echo $answer->text; ?>" alt="" />
				</a>
			</legend>
		<?php } else { ?>
			<legend class="voteLegend"><?php echo $answer->text; ?></legend>
		<?php } ?>
		<input checked="checked" type="radio" name="radioVote|<?php echo $answer->answerID; ?>" id="radioVote|<?php echo $answer->answerID; ?>|0" value="0" data-role="none" />
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
<div class="clear"></div>