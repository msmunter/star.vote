<?php if ($this->user->userID) { ?>
	<div>Hello, <?php echo $this->user->info->firstName; ?>!</div>
	<div class="bigContainer">
		<div class="bigContainerTitle">Surveys</div>
		<div class="bigContainerInner">
			<?php
			if (!empty($this->user->surveys)) { 
				foreach ($this->user->surveys as $survey) {
					echo $survey->title.'<br />';
				}
			} else { ?>
				No surveys created yet
			<?php } ?>
		</div>
	</div>
	<div class="bigContainer">
		<div class="bigContainerTitle">Polls</div>
		<div class="bigContainerInner">
			<?php
			if (!empty($this->user->polls)) { 
				foreach ($this->user->polls as $poll) {
					echo $poll->title.'<br />';
				}
			} else { ?>
				No polls created yet
			<?php } ?>
		</div>
	</div>
	<div class="bigContainer">
		<div class="bigContainerTitle">User Settings</div>
		<div class="bigContainerInner">
			<a href="/user/changepass/">Change password</a>
		</div>
	</div>
<?php } else { ?>
	No user logged in. <a href="/user/login/">Log in here</a>.
<?php } ?>