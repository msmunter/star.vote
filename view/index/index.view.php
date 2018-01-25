<div class="indexHeader">Simple, fair voting using the STAR method.</div>
<div class="bigContainer">
	<div class="bigContainerTitle">Create Your Own Poll</div>
	<div id="indexCreatePollContainer">
		<?php include('view/poll/pollinputform.view.php'); ?>
	</div>
</div>

<div class="bigContainer">
	<div class="bigContainerTitle">Recent Polls</div>
	<div id="recentPollContainer">
		<?php
		if (!empty($this->pollSet)) {
			include('view/poll/pollset.view.php');
		}
		?>
		<div class="center">
		<button data-inline="true" onclick="loadMoreRecentPolls()">Load More</button>
		</div>
	</div>
</div>

<div class="bigContainer">
	<div class="bigContainerTitle">Popular Polls</div>
	<div id="popularPollContainer">
		<?php
		$this->pollSet = $this->mostPopularPolls;
		if (!empty($this->pollSet)) {
			include('view/poll/pollset.view.php');
		}
		?>
		<div class="center">
			<button data-inline="true" onclick="loadMorePopularPolls()">Load More</button>
		</div>
	</div>
</div>
<script><?php include('web/js/poll/create.js'); ?></script>