<?php if ($this->error) { ?>
	ERROR: <?php echo $this->error; ?>
<?php } else { ?>
	<input type="hidden" id="surveyID" name="sureyID" value="<?php echo $this->survey->surveyID; ?>"/>
	<div id="statusMsg"></div>
	<div class="clear"></div>
	<div class="bigContainer">
		<div class="bigContainerTitle">Finalize Survey/Election "<a href="/<?php echo $this->survey->customSlug; ?>/"><?php echo $this->survey->title; ?></a>"</div>
		<div class="bigContainerInner">
			<p>
				This can be run as many times as necessary to process all outstanding voters.	
			</p>
			<p>
				<a href="https://dev.ipo.vote/survey/votervalfinal/<?php echo $this->survey->surveyID; ?>/">Voters needing finalization</a>
			</p>
			<table>
				<tr><td>Voters Voted:</td><td class="alignright"><?php echo $this->tempVoterCount; ?></td></tr>
				<tr><td>Voters Not Yet Finalized:</td><td class="alignright"><?php echo $this->toBeFinalizedVoterCount; ?></td></tr>
				<tr><td>Voters Finalized:</td><td class="alignright"><?php echo $this->finalizedVoterCount; ?></td></tr>
				<tr><td>Voters In Results:</td><td class="alignright"><?php echo $this->resultsVoterCount; ?></td></tr>
			</table>
			<button id="finalizeSurveyButton" data-inline="inline" onclick="finalizeSurvey()">Finalize Survey</button>
		</div>
	</div>
<?php } ?>