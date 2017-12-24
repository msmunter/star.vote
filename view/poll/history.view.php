<div id="errorMsg"></div>
<?php //$this->debug($this->mostRecentPolls); // DEBUG ONLY!!! ?>
<?php
if (count($this->mostRecentPolls) > 0) {
	echo '<div class="ui-grid-a">';
	$pollCount = count($this->mostRecentPolls);
	$pollHalfCount = round($pollCount / 2);
	echo '<div class="ui-block-a"><div class="ui-bar">';
	for ($i = 0; $i < $pollHalfCount; $i++) {
		echo '<a class="pollLink" href="/poll/results/'.$this->mostRecentPolls[$i]->pollID.'/">';
			echo $this->mostRecentPolls[$i]->question;
			echo '<div class="pollInfo">'.$this->mostRecentPolls[$i]->created.'</div>';
		echo '</a>';
	}
	echo '</div></div>';
	echo '<div class="ui-block-b"><div class="ui-bar">';
	for ($i = $pollHalfCount; $i < $pollCount; $i++) {
		echo '<a class="pollLink" href="/poll/results/'.$this->mostRecentPolls[$i]->pollID.'/">';
			echo $this->mostRecentPolls[$i]->question;
			echo '<div class="pollInfo">'.$this->mostRecentPolls[$i]->created.'</div>';
		echo '</a>';
	}
	echo '</div></div>';
	echo '</div><!-- /grid-a -->';
} else {
	echo 'No polls found';
}
?>
