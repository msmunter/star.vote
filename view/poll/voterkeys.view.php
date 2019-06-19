<?php if ($this->user->userID != '' && $this->user->userID == $this->poll->userID) { ?>
	<input type="hidden" id="pollID" value="<?php echo $this->pollID; ?>" />
	<div id="statusMsg"></div>
	<div class="clear"></div>
	<div class="bigContainer">
		<?php if ($this->poll->verifiedVotingType == "gkc") { ?>
			<div class="bigContainerTitle">Generate Voter Keys for "<a href="/<?php
				if ($this->poll->customSlug) {
					echo $this->poll->customSlug;
				} else echo 'poll/'.$this->poll->pollID;
			?>/"><?php echo $this->poll->question; ?></a>"</div>
			<div class="bigContainerInner">
				<input type="number" id="numKeys" value="1" min="1" max="999999" />
				<button id="generateKeysButton" data-inline="inline" data-mini="mini" onclick="generateVoterKeys()">Generate Keys</button>
			</div>
		<?php } ?>
	</div>
	<div class="bigContainer">
		<div class="bigContainerTitle">Existing Voter Keys for "<a href="/<?php
			if ($this->poll->customSlug) {
				echo $this->poll->customSlug;
			} else echo 'poll/'.$this->poll->pollID;
		?>/"><?php echo $this->poll->question; ?></a>"</div>
		<div class="bigContainerInner" id="existingVoterKeys">
			<?php include('view/poll/existingvoterkeys.view.php'); ?>
		</div>
	</div>
<?php } else { ?>
	ERROR: not authorized to edit poll "<a href="/
	<?php
	if ($this->poll->customSlug) {
		echo $this->poll->customSlug;
	} else {
		echo 'poll/'.$this->poll->pollID;
	}
	?>
	/"><?php echo $this->poll->question; ?></a>"
<?php } ?>