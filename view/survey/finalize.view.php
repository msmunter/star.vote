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
				<a href="/survey/votervalfinal/<?php echo $this->survey->surveyID; ?>/">Voters needing finalization</a>
			</p>
			<table>
				<tr><td>Voted:</td><td class="alignright" id="cellTempVoterCount"><?php echo $this->tempVoterCount; ?></td></tr>
				<tr><td colspan="2" class="bottomBorder"></td></tr>
				<tr><td>New:</td><td class="alignright" id="cellNewVoterCount"><?php echo ($this->tempVoterCount - $this->verifiedOnceVoterCount - $this->verifiedTwiceVoterCount - $this->rejectedOnceVoterCount - $this->rejectedTwiceVoterCount - $this->resultsVoterCount); ?></td></tr>
				<tr><td colspan="5" class="bottomBorder"></td></tr>
				<tr>
					<td>Val Once:</td><td class="alignright" id="cellValOnceCount"><?php echo $this->verifiedOnceVoterCount; ?></td><td style="width: 5px;"/>
					<td>Rej Once:</td><td class="alignright" id="cellRejOnceCount"><?php echo $this->rejectedOnceVoterCount; ?></td><td style="width: 5px;"/>
				</tr>
				<tr>
					<td>Val Twice:</td><td class="alignright" id="cellValTwiceCount"><?php echo $this->verifiedTwiceVoterCount; ?></td><td style="width: 5px;"/>
					<td>Rej Twice:</td><td class="alignright" id="cellRejTwiceCount"><?php echo $this->rejectedTwiceVoterCount; ?></td><td style="width: 5px;"/>
				</tr>
				<tr><td colspan="5" class="bottomBorder"></td></tr>
				<!-- <tr><td>Voters To Be Finalized:</td><td class="alignright"><?php //echo $this->toBeFinalizedVoterCount; ?></td></tr> -->
				<tr><td>Finalized:</td><td class="alignright" id="cellFinalizedCount"><?php echo $this->verifiedTwiceVoterCount + $this->rejectedTwiceVoterCount; ?></td></tr>
				<tr><td>In Results:</td><td class="alignright" id="cellInResultsCount"><?php echo $this->resultsVoterCount; ?></td></tr>
				<tr><td colspan="2" class="bottomBorder"></td></tr>
				<tr><td>Reporting:</td><td class="alignright"><span id="cellPercentReporting"><?php echo $this->percentReporting; ?></span>%</td></tr>
			</table>
			<button id="finalizeSurveyButton" data-inline="inline" onclick="finalizeSurvey()">Finalize Survey</button>
		</div>
	</div>
<?php } ?>