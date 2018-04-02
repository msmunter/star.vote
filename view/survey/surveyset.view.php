<?php
if (count($this->surveys) > 0) {
	echo '<div class="ui-grid-a">';
	$itemCount = count($this->surveys);
	$itemHalfCount = round($itemCount / 2);
	echo '<div class="columnOne ui-block-a"><div class="ui-bar">';
	for ($i = 0; $i < $itemHalfCount; $i++) {
		echo '<a class="pollLink" href="/';
		if ($this->surveys[$i]->customSlug != '') {
			echo $this->surveys[$i]->customSlug;
		} else echo $this->surveys[$i]->surveyID;
		echo '/">';
			echo '<div class="pollInfoVotes">Votes<br />'.$this->surveys[$i]->votes.'</div>';
			echo $this->surveys[$i]->title;
			echo '<div class="pollInfo">Started '.$this->surveys[$i]->created.'</div>';
		echo '</a>';
	}
	echo '</div></div>';
	echo '<div class="columnTwo ui-block-b"><div class="ui-bar">';
	for ($i = $itemHalfCount; $i < $itemCount; $i++) {
		echo '<a class="pollLink" href="/';
		if ($this->surveys[$i]->customSlug != '') {
			echo $this->surveys[$i]->customSlug;
		} else echo $this->surveys[$i]->surveyID;
		echo '/">';
			echo '<div class="pollInfoVotes">Votes<br />'.$this->surveys[$i]->votes.'</div>';
			echo $this->surveys[$i]->title;
			echo '<div class="pollInfo">Started '.$this->surveys[$i]->created.'</div>';
		echo '</a>';
	}
	echo '</div></div>';
	echo '</div><!-- /grid-a -->';
} else {
	echo 'No surveys found';
}
?>
