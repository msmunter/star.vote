<?php if ($this->userCanValidate || ($this->user->userID == $this->survey->userID && $this->user->userID != "")) { ?>
	<?php if ($this->survey) { ?>
		<input type="hidden" id="surveyID" value="<?php echo $this->survey->surveyID; ?>" />
		<div class="bigContainer">
			<div class="bigContainerTitle">
				Voter validation for <a href="/
				<?php
				if ($this->survey->customSlug) {
					echo $this->survey->customSlug;
				} else {
					echo 'survey/'.$this->survey->surveyID;
				}
				?>
				/"><?php echo $this->survey->title; ?></a>
			</div>
			<div class="bigContainerInner">
				<p>
					<!-- <div class="ui-field-contain">
						<label for="voterID">Voter ID:</label>
						<input id="voterID" />
					</div> -->
					<div id="statusMsg" class="hidden"></div>
					<div class="clear"></div>
					<table id="validateVoterTable">
						<tr><td colspan="2" id="verifiedVoterStatCell">
							Verified <span id="voterVerifiedCount"><?php echo $this->voterVerifiedCount; ?></span> out of <span id="voterCount"><?php echo $this->voterCount; ?></span> voters
						</td></tr>
						<tr><td colspan="2" class="spacerRow"></td></tr>
						<tr><th>Address</th><th>Validation Image</th></tr>
						<tr><td id="validationInfo">
							<table id="validationComparisonTable">
								<tr><td id="validationComparisonTableName">JOHN Q PUBLIC</td></tr>
								<tr><td id="validationComparisonTableAddress">123 TEST ST</td></tr>
								<tr><td id="validationComparisonTableCSZ">EUGENE, OR 97405</td></tr>
							</table>
						</td><td id="validationComparisonImageCell"><a id="validationImgHref" href="/web/images/img_placeholder.svg" target="_blank"><img id="validationImg" src="/web/images/img_placeholder.svg" /></a></td></tr>
						</table>
						<table id="validateVoterButtonTable">
							<tr><td>
								<button id="validateVoterButton" class="validateVoterButtons" data-inline="inline" onclick="validatevoter(1, false)" style="background-color: green; color: white;">Validate</button>
							</td><td class="verticalSpacer border" rowspan="2"></td><td class="verticalSpacer" rowspan="2">

							</td><td>
								<button id="rejectVoterButton1" class="validateVoterButtons" data-inline="inline" onclick="validatevoter(0, 'unreadable')" style="background-color: darkred; color: white;">Reject - Unreadable</button><br />
							</td></tr><tr><td>
								<button id="loadValidationButton" data-inline="inline" onclick="loadvalidation()">Load</button>
							</td><td>
								<button id="rejectVoterButton2" class="validateVoterButtons" data-inline="inline" onclick="validatevoter(0, 'mismatch')" style="background-color: darkred; color: white;">Reject - Data Mismatch</button>
							</td></tr>
						</table>
				</p>
			</div>
		</div>
	<?php } else { ?>
		No survey specified
	<?php } ?>
	<!-- <div>Users 1, 2 and the election's creator can view this page.</div> -->
<?php } else { ?>
	Not authorized to view this page
<?php } ?>