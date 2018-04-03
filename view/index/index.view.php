<div id="indexHeaderContainer">
	<div id="indexHeader">
		Simple, fair voting using the STAR method. Learn more: <a href="http://equal.vote"><img id="equalVoteLogo" src="web/images/equalvote_logo.png" /></a>
	</div>
</div>
<div class="bigContainer">
	<div class="bigContainerTitle">Create Your Own Poll</div>
	<div id="indexCreatePollContainer">
		<?php include('view/poll/pollinputform.view.php'); ?>
	</div>
</div>

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

<script><?php include('web/js/poll/create.js'); ?></script>
<script><?php include('web/js/poll/history.js'); ?></script>