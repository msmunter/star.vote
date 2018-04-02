<?php //$this->debug($this->userDetails); // DEBUG ONLY!!! ?>
<div class="bigContainer">
	<div class="bigContainerTitle">Details for "<?php echo $this->userDetails->firstName; ?> <?php echo $this->userDetails->lastName; ?>"</div>
	<div class="bigContainerInner">
		<a href="/admin/passadmin/<?php echo $this->userDetails->userID; ?>/">Change Password</a>
		<div>Added: <?php $oAdded = new DateTime('@'.$this->userDetails->added); echo $oAdded->format('Y-m-d H:i:s'); ?></div>
		<div>Enabled: <?php if ($this->userDetails->disabled == false) {echo 'Yes';} else echo 'No'; ?></div>
		<div>Surveys: </div>
		<div>Polls: </div>
	</div>
</div>