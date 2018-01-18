<div class="indexHeader">Simple, fair voting using the STAR method.</div>
<div class="indexTitle">Create Your Own Poll</div>
<div id="indexCreatePollContainer">
	<?php include('view/poll/pollinputform.view.php'); ?>
</div>
<div class="indexTitle">Recent Polls</div>
<div id="recentPollContainer">
	<?php
	if (!empty($this->pollSet)) {
		include('view/poll/pollset.view.php');
	}
	?>
</div>
<div class="center">
	<button data-inline="true" onclick="loadMoreRecentPolls()">Load More</button>
</div>
<div class="indexTitle">Popular Polls</div>
<div id="popularPollContainer">
	<?php
	$this->pollSet = $this->mostPopularPolls;
	if (!empty($this->pollSet)) {
		include('view/poll/pollset.view.php');
	}
	?>
</div>
<div class="center">
	<button data-inline="true" onclick="loadMorePopularPolls()">Load More</button>
</div>
<script><?php include('web/js/poll/create.js'); ?></script>