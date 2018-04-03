<pre><?php //$this->debug($this->model); ?></pre>
<?php if ($this->user->userID) { ?>
	<div>Hello, <?php echo $this->user->info->firstName; ?>!</div>
	<div class="bigContainer">
		<div class="bigContainerTitle">Surveys/Elections <a href="/survey/create/" class="ui-btn ui-btn-inline ui-icon-plus ui-btn-icon-notext ui-corner-all noMargin">New Survey/Election</a></div>
		<div class="bigContainerInner">
			<?php
			if (!empty($this->surveys)) { 
				include('view/survey/usersurveys.view.php');
			} else { ?>
				No surveys/elections created yet
			<?php } ?>
		</div>
	</div>
	<div class="bigContainer">
		<div class="bigContainerTitle">Polls <a href="/poll/create/" class="ui-btn ui-btn-inline ui-icon-plus ui-btn-icon-notext ui-corner-all noMargin">New Poll</a></div>
		<div class="bigContainerInner">
			<?php
			$this->pollSet = $this->polls;
			if (!empty($this->pollSet)) {
				include('view/poll/userpolls.view.php');
			}else echo 'No polls created yet';
			?>
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