<div>
	There are <?php echo $this->voterKeyCount; ?> voter keys <a class="ui-btn ui-mini ui-btn-inline ui-btn-corner-all" href="/poll/voterkeyscsv/<?php echo $this->poll->pollID; ?>/">CSV</a>
</div>
<?php
if ($this->voterKeyCount > 0) {
	echo '<table id="voterKeyTable">';
	echo '<tr><th>Key</th><th>Voted</th><th>Invalid</th></tr>';
	foreach ($this->voterKeys as $key) {
		echo '<tr>';
		echo '<td class="voterKeyCell">'.$key->voterKey.'</td>';
		echo '<td>';
		if ($key->votedTime) echo '&#10003;';
		echo '</td>';
		echo '<td>';
		if ($key->invalid) echo 'X';
		echo '</td>';
		echo '</tr>';
	}
	echo '</table>';
} else {
	echo 'No keys yet';
}
?>