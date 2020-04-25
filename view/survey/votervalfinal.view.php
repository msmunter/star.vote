<?php if ($this->user->userID == 1 || $this->userCanValidate == 2) { ?>
	<div class="bigContainer">
			<div class="bigContainerTitle">
				Finalize Voter <?php echo $_POST['starId']; ?>
			</div>
			<div class="bigContainerInner">
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
						Finalized: <span id="voterVerifiedCount"><?php echo $this->voterFinalizedCount; ?></span> out of <span id="voterCount"><?php echo $this->voterCount; ?></span> voters total
					</td></tr>
				</table>
				<table id="validateVoterTable">
					<tr><th>Voter</th><th>Validation Image</th></tr>
					<tr><td id="validationInfo">
						<table id="validationComparisonTable">
							<tr><td>Checkout: </td><td id="checkoutTime"><?php echo $this->voter->checkoutTime; ?></td></tr>
							<tr><td>Status: </td><td id="validationComparisonTableValidationStatus">--</td></tr>
							<tr><td>Voter ID: </td><td><?php echo $this->voterfile->stateVoterID; ?></td></tr>
							<tr><td>STAR ID: </td><td><?php echo $this->voter->voterID; ?></td></tr>
							<tr><td>Email: </td><td><?php echo $this->voter->email; ?></td></tr>
							<tr><td>IP Address: </td><td><?php echo $this->voter->ip; ?></td></tr>
							<tr><td>Name: </td><td id="validationComparisonTableName"><?php echo $this->voterfile->fname.' '.$this->voterfile->lname; ?></td></tr>
							<tr><td>Born: </td><td id="validationComparisonTableBirthyear"><?php echo $this->voterfile->birthyear; ?></td></tr>
							<tr><td>Address: </td><td id="validationComparisonTableAddress"><?php echo $this->voterfile->street; ?><br /><?php echo $this->voterfile->city; ?>, <?php echo $this->voterfile->state; ?> <?php echo $this->voterfile->zip; ?></td></tr>
							<tr><td></td><td id="validationComparisonTableCSZ"></td></tr>
						</table>
					</td><td id="validationComparisonImageCell">
						<a id="validationImgHref1" href="/web/images/img_placeholder.svg" target="_blank"><img id="validationImg1" src="https://cdn.filestackcontent.com/<?php echo $this->voterIdent->cdnHandle1; ?>" /></a>
						<a id="validationImgHref2" href="/web/images/img_placeholder.svg" target="_blank"><img id="validationImg2" src="https://cdn.filestackcontent.com/<?php echo $this->voterIdent->cdnHandle2; ?>" /></a>
					</td></tr>
				</table>
				<div class="ui-field-contain">
					<label for="ticketID">Support Ticket ID:</label>
					<input type="text" data-clear-btn="true" id="ticketID" name="ticketID" required="1"></input>
				</div>
				<table id="validateVoterButtonTable">
					<tr><td>
						<button id="validateVoterButton" class="validateVoterButtons" data-inline="inline" onclick="finalvalidatevoter(1, false)" style="background-color: green; color: white;">Validate</button>
					</td><td class="verticalSpacer border" rowspan="2"></td><td class="verticalSpacer" rowspan="2">
					</td><td>
						<button id="rejectVoterButton1" class="validateVoterButtons" data-inline="inline" onclick="finalvalidatevoter(0, 'unreadable')" style="background-color: darkred; color: white;">Reject - Unreadable</button><br />
					</td></tr><tr><td>
						<!-- <button id="loadValidationButton" data-inline="inline" onclick="loadvalidation()">Load</button> -->
					</td><td>
						<button id="rejectVoterButton2" class="validateVoterButtons" data-inline="inline" onclick="finalvalidatevoter(0, 'mismatch')" style="background-color: darkred; color: white;">Reject - Data Mismatch</button>
					</td></tr>
				</table>
			</div>
		</div>
<?php } else { ?>
	User not authorized to validate
<?php } ?>