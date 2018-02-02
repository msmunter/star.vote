<table id="yourVoteTable">
	<tr><th>#</th><th>Option</th><th>Vote</th></tr>
	<?php foreach ($this->poll->answers as $answer) {
		$i++;
		echo '<tr><td class="orderCell">'.$i.'</td><td>'.$answer->text.'</td><td class="number">'.$this->yourVote[$answer->answerID].'</td></tr>';
	} ?>
</table>