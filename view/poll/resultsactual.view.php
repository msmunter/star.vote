<div id="pollResultsTitle">Results for "<?php echo $this->poll->question; ?>"</div>
<?php foreach ($this->poll->answers as $answer) { ?>
	<div><?php echo $answer->text; ?>: <?php echo $answer->points; ?> points</div>
<?php } ?>
<div>
	Runoff (top two by points):<br />
	<?php 
	if ($this->poll->runoffResults['first']['votes'] == $this->poll->runoffResults['second']['votes']) {
		echo 'Tie between '. $this->poll->runoffResults['first']['question'].' and '.$this->poll->runoffResults['second']['question'].' with '.$this->poll->runoffResults['first']['votes'].' votes each';
	} else {
		?>
		1st: <?php echo $this->poll->runoffResults['first']['question']; ?>, preferred by <?php echo $this->poll->runoffResults['first']['votes']; ?><br />
		2nd: <?php echo $this->poll->runoffResults['second']['question']; ?>, preferred by <?php echo $this->poll->runoffResults['second']['votes']; ?>
		<?php
	}
	?>
</div>
<?php
//echo '<pre>Top Two: ';print_r($this->poll->topTwo);echo '<br />Runoff Results: ';print_r($this->poll->runoffResults);echo '</pre>';
?>