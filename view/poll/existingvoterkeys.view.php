<div>
	Voted: <?php echo $this->usedVoterKeyCount; ?>/<?php echo $this->voterKeyCount; ?> keys<a class="ui-btn ui-mini ui-btn-inline ui-btn-corner-all" href="/poll/voterkeyscsv/<?php echo $this->poll->pollID; ?>/">CSV</a>
</div>
<?php
if ($this->voterKeyCount > 0) {
	echo '<table id="voterKeyTable">';
	echo '<tr>';
	if ($this->poll->verifiedVoting && $this->poll->verifiedVotingType == 'eml') echo '<th>Email</th>';
	echo '<th>Key</th><th>Voted</th><th>Validated</th><th>Invalid</th></tr>';
	foreach ($this->voterKeys as $key) {
		echo '<tr>';
		if ($this->poll->verifiedVoting && $this->poll->verifiedVotingType == 'eml') {
			echo '<td>'.$key->email.'</td>';
		}
		echo '<td class="voterKeyCell">'.$key->voterKey.'</td>';
		echo '<td>';
		if ($key->voteTime) echo '&#10003;';
		echo '</td>';
		echo '<td>';
		if ($key->verifyTime) echo '&#10003;';
		echo '</td>';
		echo '<td>';
		if ($key->invalid) echo '&#10003;';
		echo '</td>';
		echo '</tr>';
	}
	echo '</table>';
} else {
	echo 'No keys yet';
}
?>