<pre><?php print_r($this->votesTogether); ?></pre>
<div id="yourVoterID">Your voter ID: <span id="yourVoterIDActual"><?php echo $this->voterID; ?></span></div>
<div class="clear"></div>
<table id="yourVoteTable">
	<tr><th>#</th><th>Option</th><th>Vote</th></tr>
	<?php foreach ($this->yourVotes as $answer) {
		$i++;
		echo '<tr><td class="orderCell">'.$i.'</td><td>'.$answer->text.'</td><td class="number">'.$answer->vote.'</td></tr>';
	} ?>
</table>