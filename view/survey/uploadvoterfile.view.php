<?php if ($this->user->userID == 1) { ?>
	<?php if ($this->survey->surveyID) { ?>
		<input type="hidden" id="surveyID" value="<?php echo $this->survey->surveyID; ?>" />
		<div id="statusMsg" class="hidden"></div>
		<div class="clear"></div>
		<div class="bigContainer">
			<div class="bigContainerTitle">Upload Voter File</div>
			<div class="bigContainerInner">
				<div>
					Survey: <?php echo $this->survey->title; ?>
				</div>
				<div class="ui-field-contain">
					<label for="voterFileInput">File:</label>
					<input type="file" accept=".txt" id="voterFileInput" name="voterFileInput"></input>
				</div>
				<button id="uploadButton" data-inline="inline" onclick="uploadVoterFile()">Upload</button>
			</div>
		</div>
	<?php } else { ?>
		<div class="bigContainer">
			<div class="bigContainerTitle">Upload Voter File</div>
			<div class="bigContainerInner">
				<div>
					ERROR: no survey ID provided
				</div>
			</div>
		</div>
	<?php } ?>
<?php } else { ?>
	ERROR: not authorized
<?php } ?>