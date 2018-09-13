<div id="errorMsg"></div>
<div class="bigContainer">
	<div class="bigContainerTitle">Polls</div>
	<div class="bigContainerInner">
		<div id="pollContainer">
			<?php include('view/poll/pollset.view.php'); ?>
		</div>
		<?php if (!empty($this->mostPopularPolls)) { ?>
			<div class="center">
				<button data-inline="true" onclick="loadMorePolls()">Load More</button>
			</div>
		<?php } ?>
	</div>
</div>

<div class="bigContainer">
	<div class="bigContainerTitle">Surveys</div>
	<div class="bigContainerInner">
		<div id="surveyContainer">
			<?php include('view/survey/surveyset.view.php'); ?>
		</div>
		<?php if (!empty($this->mostPopularSurveys)) { ?>
			<div class="center">
				<button data-inline="true" onclick="loadMoreSurveys()">Load More</button>
			</div>
		<?php } ?>
	</div>
</div>