<div class="indexHeader">Simple, fair voting using the STAR method.</div>
<div class="indexTitle">Create Your Own Poll</div>
<div id="indexCreatePollContainer">
	<?php include('view/poll/pollinputform.view.php'); ?>
</div>
<div class="indexTitle">Recent Polls</div>
<div id="recentPollContainer">
	<?php
	if (!empty($this->pollSet)) {
		include('view/poll/history.view.php');
	}
	?>
</div>
<script><?php include('web/js/poll/create.js'); ?></script>