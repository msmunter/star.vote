<div id="pollResultsTitle">Results for "<?php echo $this->poll->question; ?>"</div>
<?php foreach ($this->poll->answers as $answer) { ?>
	<div><?php echo $answer->text; ?>: <?php echo $answer->points; ?> points, <?php echo $answer->votes; ?> votes</div>
<?php } ?>