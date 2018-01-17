<div class="indexHeader">Simple, fair voting using the STAR method.</div>
<div class="indexTitle">Create Your Own</div>
<div id="indexCreatePollContainer">
	
</div>
<div class="indexTitle">Recent Polls</div>
<div id="recentPollContainer">
	<?php
	if (!empty($this->pollSet)) {
		include('view/poll/history.view.php');
	}
	?>
</div>