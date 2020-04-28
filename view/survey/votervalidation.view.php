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
								<tr><td>Birth Year: </td><td id="validationComparisonTableBirthyear">--</td></tr>
								<tr><td>Birth Date: </td><td id="validationComparisonTableBirthdate">--</td></tr>
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
						</td><td class="verticalSpacer border" rowspan="2"></td><td class="verticalSpacer"></td><td>
							<button id="lookupVoterButton" class="validateVoterButtons" data-inline="inline" onclick="lookupvoter(1, false)">Lookup</button>
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
					<ol>
						<li class="bold">Look photo ID. If ID is illegible, if data doesn't match, or if ID is missing, flag using the corresponding red button.</li>
						<li class="bold">If above is good, look up voter’s full date of birth on “My Vote” using lookup button.</li>
						<li class="bold">Click load when you are ready for the next credential.</li>
					</ol>
					<p>
						Accept: Government issued photo ID present. Date of Birth verified on My Vote using "Lookup." Name and address match.<br />
						Illegible: Can’t read name, date of birth, or address.<br />
						Data Mismatch: Name, date of birth, or address don’t match voter file info listed.<br />
						Provisional: Does not contain government issued photo ID.<br />
						Voter Not Found: Voter can not be found in My Vote.
					</p>
				</p>
			</div>
		</div>
		<a id="orestarLink" href="https://secure.sos.state.or.us/orestar/vr/showVoterSearch.do?lang=eng&source=SOS&identifier2=John&identifier3=Public&identifier8=01/01/1990">Lookup Voter</a>
	<?php } else { ?>
		No survey specified
	<?php } ?>
	<!-- <div>Users 1, 2 and the election's creator can view this page.</div> -->
<?php } else { ?>
	Not authorized to view this page
<?php } ?>