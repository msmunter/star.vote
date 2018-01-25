<?php foreach ($this->poll->answers as $answer) {
	echo '<div>'.$answer->text.': '.$this->yourVote[$answer->answerID].'</div>';
} ?>