<?php if ($this->userCanValidate > 0 || ($this->user->userID == $this->survey->userID && $this->user->userID != "")) { ?>
	<?php if ($this->survey) { ?>
		<input type="hidden" id="surveyID" value="<?php echo $this->survey->surveyID; ?>" />
		<input type="hidden" id="voterID" value="" />
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
					<div id="statusMsg" class="hidden"></div>
					<div class="clear"></div>
					<table id="verifierInfo">
						<tr>
							<td>
								Verifier Role: 
								<?php if ($this->user->userID == 1) { ?>
									Site Admin
								<?php } else if ($this->userCanValidate == 2) { ?>
									Verifier L2
								<?php } else { ?>
									Verifier L1
								<?php } ?>
							</td>
							<td>&nbsp;--&nbsp;</td>
							<td id="verifiedVoterStatCell">
							Verified: <span id="voterVerifiedCount"><?php echo $this->verifiedVoterCount; ?></span> out of <span id="voterCount"><?php echo $this->voterCount; ?></span> voters total
						</td></tr>
					</table>
					<table id="validateVoterTable">
						<tr><th>Voter</th><th>Validation Images</th></tr>
						<tr><td id="validationInfo">
							<table id="validationComparisonTable">
								<tr><td>Checkout: </td><td id="checkoutTime">--:--:--</td></tr>
								<tr><td>Status: </td><td id="validationComparisonTableValidationStatus">--</td></tr>
								<tr><td>Name: </td><td id="validationComparisonTableName">--</td></tr>
								<tr><td>Born: </td><td id="validationComparisonTableBirthyear">--</td></tr>
								<tr><td>Address: </td><td id="validationComparisonTableAddress">--</td></tr>
								<tr><td></td><td id="validationComparisonTableCSZ"></td></tr>
							</table>
						</td><td id="validationComparisonImageCell">
							<a id="validationImgHref1" href="/web/images/img_placeholder.svg" target="_blank"><img id="validationImg1" src="/web/images/img_placeholder.svg" /></a>
							<a id="validationImgHref2" href="/web/images/img_placeholder.svg" target="_blank"><img id="validationImg2" src="/web/images/img_placeholder.svg" /></a>
						</td></tr>
					</table>
					<table id="validateVoterButtonTable">
						<tr><td>
							<button id="loadValidationButton" data-inline="inline" onclick="loadvalidation()">Load</button>
						</td><td class="verticalSpacer border" rowspan="2"></td><td class="verticalSpacer" rowspan="2">
						</td><td>
							<button id="rejectVoterButton4" class="validateVoterButtons" data-inline="inline" onclick="validatevoter(0, 'notFound')" style="background-color: darkred; color: white;">Voter Not Found</button>
						</td><td>
							<button id="rejectVoterButton1" class="validateVoterButtons" data-inline="inline" onclick="validatevoter(0, 'illegible')" style="background-color: darkred; color: white;">Illegible</button><br />
						</td><td>
							<button id="rejectVoterButton2" class="validateVoterButtons" data-inline="inline" onclick="validatevoter(0, 'mismatch')" style="background-color: darkred; color: white;">Data Mismatch</button>
						</td><td>
							<button id="rejectVoterButton3" class="validateVoterButtons" data-inline="inline" onclick="validatevoter(0, 'provisional')" style="background-color: darkred; color: white;">Provisional</button>
						</td><td>
							<button id="validateVoterButton" class="validateVoterButtons" data-inline="inline" onclick="validatevoter(1, false)" style="background-color: green; color: white;">Accept</button>
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