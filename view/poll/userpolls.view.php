<?php
if (count($this->polls) > 0) {
	echo '<div class="ui-grid-a">';
	$itemCount = count($this->polls);
	$itemHalfCount = round($itemCount / 2);
	echo '<div class="columnOne ui-block-a"><div class="ui-bar">';
	for ($i = 0; $i < $itemHalfCount; $i++) {
		echo '<a class="pollLink" href="/';
		if ($this->polls[$i]->customSlug != '') {
			echo $this->polls[$i]->customSlug;
		} else echo $this->polls[$i]->surveyID;
		echo '/">';
			echo '<div class="pollInfoVotes">Votes<br />'.$this->polls[$i]->votes.'</div>';
			echo $this->polls[$i]->question;
			echo '<div class="pollInfo">Started '.$this->polls[$i]->created.'</div>';
		echo '</a>';
	}
	echo '</div></div>';
	echo '<div class="columnTwo ui-block-b"><div class="ui-bar">';
	for ($i = $itemHalfCount; $i < $itemCount; $i++) {
		echo '<a class="pollLink" href="/';
		if ($this->polls[$i]->customSlug != '') {
			echo $this->polls[$i]->customSlug;
		} else echo $this->polls[$i]->surveyID;
		echo '/">';
			echo '<div class="pollInfoVotes">Votes<br />'.$this->polls[$i]->votes.'</div>';
			echo $this->polls[$i]->question;
			echo '<div class="pollInfo">Started '.$this->polls[$i]->created.'</div>';
		echo '</a>';
	}
	echo '</div></div>';
	echo '</div><!-- /grid-a -->';
} else {
	echo 'No polls found';
}
?>