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
							<span id="cellToBeReviewedCount"><?php echo ($this->tempVoterCount - $this->verifiedTwiceVoterCount - $this->rejectedOnceVoterCount - $this->rejectedTwiceVoterCount - $this->resultsVoterCount); ?></span> voters to process
						</td></tr>
					</table>
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
							<button id="validateVoterButton" class="validateVoterButtons" data-inline="inline" onclick="validatevoter(1, false)" style="background-color: green; color: white;">Validate</button>
						</td></tr>
					</table>
					<ol>
						<li class="bold">Does credential document include name and address? If not flag with corresponding red button.</li>
						<li class="bold">Look up voter’s full date of birth on “My Vote” using lookup button. Click Validate once voter is verified.</li>
						<li class="bold">Click load when you are ready for the next credential.</li>
					</ol>
					<p>
						Validate: Document contains name and address. Date of Birth verified on My Vote using "Lookup."<br />
						Illegible: Can’t read data points.<br />
						Data Mismatch: Name or address don’t match voter file info listed.<br />
						Provisional: Does not contain a valid document.<br />
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