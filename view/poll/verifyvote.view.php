<div>
	<?php if ($this->status['error']) { ?>
		Error: <?php echo $this->status['error']; ?>
	<?php } else { ?>
		Success: <?php echo $this->status['success']; ?><br /><a href="/<?php echo $this->voterKeyEntry->pollID; ?>/">View Results</a>
	<?php } ?>
</div>