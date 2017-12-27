<div id="voteTitle">
	Your vote for "<?php echo $this->poll->question; ?>"
</div>
<?php foreach ($this->poll->answers as $answer) {
	echo '<div>'.$answer->text.': '.$this->yourVote[$answer->answerID].'</div>';
} ?>
<?php //echo '<pre>';print_r($this->poll);echo '</pre>';?>