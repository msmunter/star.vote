<?php 
if ($this->survey->customSlug == "eats") {
	echo '<a class="nextPollLink" href="/civics/">There\'s another section! Vote on Civics &rarr;</a>';
} else if ($this->survey->customSlug == "civics") {
	echo '<a class="nextPollLink" href="/eats/">There\'s another section! Vote on Eats &rarr;</a>';
} else if ($this->survey->customSlug == "spending") {
	echo '<a class="nextPollLink" href="/liveaction/">There\'s another section! Vote on Live Action &rarr;</a>';
} else if ($this->survey->customSlug == "liveaction") {
	echo '<a class="nextPollLink" href="/spending/">There\'s another section! Vote on Spending &rarr;</a>';
}
?>

<div class="clear" style="height: 10px;"></div>
<div id="yourVoterID">Your voter ID: <span id="yourVoterIDActual"><?php echo $this->voter->voterID; ?></span></div>
<div class="clear"></div>
<div id="yourVoteTime">Voted: <?php echo $this->yourVoteTime; ?></div>
<?php foreach ($this->survey->polls as $poll) { ?>
	<table class="yourVoteTable">
		<tr><th colspan="3" class="pollHeader"><?php echo $poll->question; ?></th></tr>
		<tr><th>#</th><th>Option</th><th>Vote</th></tr>
		<?php $i=0; ?>
		<?php foreach ($this->yourVotes[$poll->pollID] as $answer) {
			$i++;
			echo '<tr><td class="orderCell">'.$i.'</td><td>'.$answer->text.'</td><td class="number">'.$answer->vote.'</td></tr>';
		} ?>
	</table>
<?php } ?>

<div class="clear"></div>
<?php 
if ($this->survey->customSlug == "eats") {
	echo '<a class="nextPollLink" href="/civics/">There\'s another section! Vote on Civics &rarr;</a>';
} else if ($this->survey->customSlug == "civics") {
	echo '<a class="nextPollLink" href="/eats/">There\'s another section! Vote on Eats &rarr;</a>';
} else if ($this->survey->customSlug == "spending") {
	echo '<a class="nextPollLink" href="/liveaction/">There\'s another section! Vote on Live Action &rarr;</a>';
} else if ($this->survey->customSlug == "liveaction") {
	echo '<a class="nextPollLink" href="/spending/">There\'s another section! Vote on Spending &rarr;</a>';
}
?>