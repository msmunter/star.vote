<?php
if (count($this->mostPopularSurveys) > 0) {
	echo '<div class="ui-grid-a">';
	
	echo '<div class="columnOne ui-block-a"><div class="ui-bar">';
	echo '<div class="recentOrPopularBar">Popular</div>';
	foreach ($this->mostPopularSurveys as $survey) {
		echo '<a class="surveyLink" href="/';
		if ($survey->customSlug != '') {
			echo $survey->customSlug;
		} else echo $survey->surveyID;
		echo '/">';
			echo '<div class="pollInfoVotes">Votes<br />'.$survey->votes.'</div>';
			echo $survey->title;
			echo '<div class="pollInfo">Started '.$survey->created.'</div>';
		echo '</a>';
	}
	echo '</div></div>';
	
	echo '<div class="columnTwo ui-block-b"><div class="ui-bar">';
	echo '<div class="recentOrPopularBar">Recent</div>';
	foreach ($this->mostRecentSurveys as $survey) {
		echo '<a class="surveyLink" href="/';
		if ($survey->customSlug != '') {
			echo $survey->customSlug;
		} else echo $survey->surveyID;
		echo '/">';
			echo '<div class="pollInfoVotes">Votes<br />'.$survey->votes.'</div>';
			echo $survey->title;
			echo '<div class="pollInfo">Started '.$survey->created.'</div>';
		echo '</a>';
	}
	echo '</div></div>';
	
	echo '</div><!-- /grid-a -->';
} else {
	echo 'No surveys found';
}
?>