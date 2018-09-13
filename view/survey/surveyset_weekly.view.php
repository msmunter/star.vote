<?php
echo '<div class="ui-grid-a">';

echo '<div class="columnOne ui-block-a"><div class="ui-bar">';
echo '<div class="recentOrPopularBar">Round One: 9/13 - 9/26</div>';

echo '<a class="surveyLink" href="/eats/">';
	echo '<div class="pollInfoVotes">Votes<br />'.$this->surveys->eats->votes.'</div>';
	echo 'Eats';
	//echo '<div class="pollInfo">Started '.$survey->created.'</div>';
echo '</a>';

echo '<a class="surveyLink" href="/civics/">';
	echo '<div class="pollInfoVotes">Votes<br />'.$this->surveys->civics->votes.'</div>';
	echo 'Civics';
echo '</a>';

echo '</div></div>';

echo '<div class="columnTwo ui-block-b"><div class="ui-bar">';
echo '<div class="recentOrPopularBar">Round Two: 9/27 - 10/10</div>';

echo '<a class="surveyLink" href="/spending/">';
	echo '<div class="pollInfoVotes">Votes<br />'.$this->surveys->spending->votes.'</div>';
	echo 'Spending';
echo '</a>';

echo '<a class="surveyLink" href="/liveaction/">';
	echo '<div class="pollInfoVotes">Votes<br />'.$this->surveys->liveaction->votes.'</div>';
	echo 'Live Action';
echo '</a>';

echo '</div></div>';

echo '</div><!-- /grid-a -->';

//echo '<pre>';print_r($this->mostPopularSurveys);echo '</pre>'; // DEBUG ONLY!!!
?>