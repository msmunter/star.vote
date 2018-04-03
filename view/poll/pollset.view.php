<?php
if (count($this->mostPopularPolls) > 0) {
	echo '<div class="ui-grid-a">';
	
	echo '<div class="columnOne ui-block-a"><div class="ui-bar">';
	echo '<div class="recentOrPopularBar">Popular</div>';
	foreach ($this->mostPopularPolls as $poll) {
		echo '<a class="pollLink" href="/';
		if ($poll->customSlug != '') {
			echo $poll->customSlug;
		} else echo $poll->pollID;
		echo '/">';
			echo '<div class="pollInfoVotes">Votes<br />'.$poll->votes.'</div>';
			echo $poll->question;
			echo '<div class="pollInfo">Started '.$poll->created.'</div>';
		echo '</a>';
	}
	echo '</div></div>';
	
	echo '<div class="columnTwo ui-block-b"><div class="ui-bar">';
	echo '<div class="recentOrPopularBar">Recent</div>';
	foreach ($this->mostRecentPolls as $poll) {
		echo '<a class="pollLink" href="/';
		if ($poll->customSlug != '') {
			echo $poll->customSlug;
		} else echo $poll->pollID;
		echo '/">';
			echo '<div class="pollInfoVotes">Votes<br />'.$poll->votes.'</div>';
			echo $poll->question;
			echo '<div class="pollInfo">Started '.$poll->created.'</div>';
		echo '</a>';
	}
	echo '</div></div>';
	
	echo '</div><!-- /grid-a -->';
} else {
	echo 'No polls found';
}
?>