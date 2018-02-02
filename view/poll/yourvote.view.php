<table id="yourVoteTable">
	<tr><th>#</th><th>Option</th><th>Vote</th></tr>
	<?php foreach ($this->yourVote as $answer) {
		$i++;
		echo '<tr><td class="orderCell">'.$i.'</td><td>'.$answer->text.'</td><td class="number">'.$answer->vote.'</td></tr>';
	} ?>
</table>