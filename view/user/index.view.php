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
	<div class="bigContainer">
		<div class="bigContainerTitle">Survey/Election Instructions</div>
		<div class="bigContainerInner">
			* For the purposes of these instructions both surveys and elections will be referred to as surveys.
			<ul>
				<li>Click the plus sign next to Surveys/Elections above to create a new survey.</li>
				<li>Provide a concise, distinct title. For example: "<?php echo date('Y'); ?> Springfield City Council Election".</li>
				<li>Indicating survey is an election changes the verbiage.</li>
				<li>A private survey is not listed on the home or history pages.</li>
				<li>Randomizing answers is recommended for fairness.</li>
				<li>If you would prefer a custom "address" for your survey, choose a Custom URL Slug. For instance, "cats" would place your survey at "star.vote/cats".</li>
				<li>To restrict voting access choose Verified Voting -> Generate CSV w/ Keys. Keys may be generated/monitored from a link near the top of that survey's page. Currently they must be downloaded in a CSV text file or copied from the site and emailed to particpants.</li>
				<li>Responses can be set to a particular voting window using Start/End Times.</li>
			</ul>
		</div>
	</div>
<?php } else { ?>
	No user logged in. <a href="/user/login/">Log in here</a>.
<?php } ?>