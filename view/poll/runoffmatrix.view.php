				<table id="runoffMatrix">
					<tr><th colspan="2"></th>
						<?php
							foreach ($this->poll->runoffAnswerArray as $answer) {
								echo '<th>';
								echo $answer->text;
								echo '</th>';
							}
							echo '</tr>';
							foreach ($this->poll->runoffAnswerArray as $answer) {
								$empty++;
								$z = 0;
								echo '<tr><td>'.$answer->text.'</td><td>&gt;</td>';
								foreach ($this->poll->runoffAnswerArray as $innerAnswer) {
									$z++;
									if ($z == $empty) {
										echo '<td class="borderCell filled">';
									} else {
										echo '<td class="borderCell">';
										echo $this->poll->orderedRunoff[$answer->answerID][$innerAnswer->answerID]->votes;
									}
									//echo '$z: '.$z.', $empty: '.$empty; // DEBUG ONLY!!!
									echo '</td>';
								}
								echo '</tr>';
							}
						?>
					</tr>
				</table>