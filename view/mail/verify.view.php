<?php if ($this->verified->verifydate && $this->verified->new) { ?>
	Address Verification: <span style="color: green;">Success</span>
<?php } else if ($this->verified->verifydate && !$this->verified->new) { ?>
	Address Verification: <span style="color: green;">Previously Verified (<?php echo date('Y-m-d', strtotime($this->verified->verifydate)); ?>)</span>
<?php } else { ?>
	Address Verification: <span style="color: darkred;">Error</span>
<?php } ?>

<p>
	userHash: <?php
	$userHash = hash("sha256", $_SERVER['REMOTE_ADDR']);
	echo $userHash;
	?>
</p>