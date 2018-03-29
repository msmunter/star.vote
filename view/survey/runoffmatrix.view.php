				<?php foreach ($this->survey->polls as $zPoll) { ?>
					<?php //$this->debug($zPoll); ?>
					<div class="runoffLegend">
						Legend: <span class="runoffFor">For</span>, <span class="runoffAgainst">Against</span>, <span class="runoffNopref">No Preference</span>
					</div>
					<div class="clear"></div>
					<table class="runoffMatrix">
						<tr><th colspan="2"></th>
							<?php
								foreach ($zPoll->runoffAnswerArray as $answer) {
									echo '<th colspan="5">';
									echo $answer->text;
									echo '</th>';
								}
								echo '</tr>';
								$empty = 0;
								foreach ($zPoll->runoffAnswerArray as $answer) {
									$empty++;
									$z = 0;
									echo '<tr><td>'.$answer->text.'</td><td>&gt;</td>';
									foreach ($zPoll->runoffAnswerArray as $innerAnswer) {
										$z++;
										if ($z == $empty) {
											echo '<td colspan="5" class="borderCell filled">';
											if ($z == 1) {
												//echo '<span class="yeaNay">Yea, Nay, NoPref</span>';
											}
											echo '</td>';
										} else {
											if ($zPoll->orderedRunoff[$answer->answerID][$innerAnswer->answerID]->votes > $zPoll->orderedRunoff[$innerAnswer->answerID][$answer->answerID]->votes) {
												$winStatus = 'winCell';
											} else if ($zPoll->orderedRunoff[$answer->answerID][$innerAnswer->answerID]->votes == $zPoll->orderedRunoff[$innerAnswer->answerID][$answer->answerID]->votes) {
												$winStatus = 'tieCell';
											} else {
												$winStatus = 'loseCell';
											}
											echo '<td class="number leftBorderCell padded '.$winStatus.'"><span class="runoffFor">'.$zPoll->orderedRunoff[$answer->answerID][$innerAnswer->answerID]->votes.'</span></td>';
											echo '<td class="number centerBorderCell '.$winStatus.'">-</td>';
											echo '<td class="number centerBorderCell padded '.$winStatus.'"><span class="runoffAgainst">'.$zPoll->orderedRunoff[$innerAnswer->answerID][$answer->answerID]->votes.'</span></td>';
											echo '<td class="number centerBorderCell '.$winStatus.'">-</td>';
											$noPref = $zPoll->voterCount - ($zPoll->orderedRunoff[$answer->answerID][$innerAnswer->answerID]->votes + $zPoll->orderedRunoff[$innerAnswer->answerID][$answer->answerID]->votes);
											echo '<td class="number rightBorderCell padded '.$winStatus.'"><span class="runoffNopref">'.$noPref.'</span></td>';
										}
										//echo '$z: '.$z.', $empty: '.$empty; // DEBUG ONLY!!!
									}
									echo '</tr>';
								}
							?>
						</tr>
					</table>
				<?php } ?>