Poll Results
<?php $this->debug($this->poll); ?>
<p>
	<?php
	if ($this->poll) {
		?>
		Results for "<?php echo $this->poll->question; ?>"
		<?php foreach ($this->poll->answers as $answer) { ?>
			<div><?php echo $answer->text; ?>: <?php echo $answer->tally; ?> votes</div>
		<?php }
	} else {
		echo 'ERROR: '.$this->error;
	}
	?>
</p>