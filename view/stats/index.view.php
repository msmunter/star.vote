<?php if ($this->user->info->admin_level == 1) { ?>
	<div class="bigContainer">
		<div class="bigContainerTitle">Statistics</div>
		<!-- <div class="bigContainerInner">
			Welcome aboard, Captain.<br />
			
		</div> -->
	</div>
	<div class="bigContainer">
		<div class="bigContainerTitle">Vote Stats</div>
		<div class="bigContainerInner">
			<table>
				<tr><td>All zero:</td><td><?php echo $this->voteStats->allZero;?></td></tr>
				<tr><td>Full range:</td><td><?php echo $this->voteStats->fullRange;?></td></tr>
				<tr><td>Other:</td><td><?php echo $this->voteStats->other;?></td></tr>
				<tr><td colspan="2" style="border-top: solid 1px black;"></td></tr>
				<tr><td>Total:</td><td><?php echo $this->voteStats->total;?></td></tr>
			</table>
			<div style="height: 10px; border-bottom: dashed 1px #cccccc;"></div>
			<?php
			echo 'Polls: '.count($this->polls);
			if ($this->polls) {
				//echo '<pre>'.print_r($this->polls).'</pre>';
				foreach ($this->polls as $poll) {
					if ($poll->allvoters) {
						echo '<div>pollID: '.$poll->pollID.', voters: '.count($poll->allvoters).'</div>';
					}
				}
			} else {
				echo 'No votes found.';
			}
			?>
		</div>
	</div>
<?php } else { ?>
	Administrators only, please <a href="/user/login/">log in</a>.
<?php } ?>