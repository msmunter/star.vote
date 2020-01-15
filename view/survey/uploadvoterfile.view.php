<input type="hidden" id="surveyID" value="<?php echo $this->survey->surveyID; ?>" />
<div id="statusMsg" class="hidden"></div>
<div class="clear"></div>
<div class="bigContainer">
	<div class="bigContainerTitle">Upload Voter File</div>
	<div class="bigContainerInner">
		<div class="ui-field-contain">
			<label for="voterFileInput">File:</label>
			<input type="file" accept=".txt" id="voterFileInput" name="voterFileInput"></input>
		</div>
		<button id="uploadButton" data-inline="inline" onclick="uploadVoterFile()">Upload</button>
	</div>
</div>