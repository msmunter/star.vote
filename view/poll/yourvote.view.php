<div id="yourVoterID">Your voter ID: <span id="yourVoterIDActual"><?php echo $this->voterID; ?></span></div>
<div class="clear"></div>
<table id="yourVoteTable">
	<tr><th>#</th><th>Option</th><th>Vote</th></tr>
	<?php foreach ($this->yourVote as $answer) {
		$i++;
		if ($answer->imgur == 1) {
			// Image
			echo '<tr><td class="orderCell">'.$i.'</td><td>';
			echo '<a href="'.$answer->text.'" target="_new"><img class="legendImg" src="'.$answer->text.'" alt="" /></a>';
			echo '</td><td class="number">'.$answer->vote.'</td></tr>';
		} else {
			echo '<tr><td class="orderCell">'.$i.'</td><td>'.$answer->text.'</td><td class="number">'.$answer->vote.'</td></tr>';
		}
	} ?>
</table>