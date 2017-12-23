<div>
<?php
if (count($this->mostRecentPolls) > 0) {
	foreach ($this->mostRecentPolls as $poll) {
		echo '<a class="pollLink" href="/poll/results/'.$poll->pollID.'/">';
			echo $poll->question;
			echo '<div class="pollInfo">Created: '.$poll->created.'</div>';
		echo '</a>';
	}
} else {
	echo 'No polls found';
}
?>
</div>