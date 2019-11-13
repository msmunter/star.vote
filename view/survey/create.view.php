<div id="statusMsg" class="hidden"></div>
<div class="clear"></div>
<div class="bigContainer">
	<div class="bigContainerTitle">Create Survey/Election</div>
	<div class="bigContainerInner">
		<div class="ui-field-contain">
			<label for="surveyTitle">Title:</label>
			<input type="text" data-clear-btn="true" id="surveyTitle" name="surveyTitle" placeholder="What is this about?"></input>
		</div>
		<div class="ui-field-contain">
			<label for="fsVerbage">Is Election:</label>
			<select name="fsVerbage" id="fsVerbage" data-role="flipswitch">
				<option value="su">No</option>
				<option value="el">Yes</option>
			</select>
		</div>
		<div class="ui-field-contain">
			<label for="fsKioskMode">Kiosk Mode:</label>
			<select name="fsKioskMode" id="fsKioskMode" data-role="flipswitch">
				<option value="0">No</option>
				<option value="1">Yes</option>
			</select>
			<div>Reset for a new voter after each vote</div>
		</div>
		<div class="ui-field-contain">
			<label for="fsPrintVote">Print Vote:</label>
			<select name="fsPrintVote" id="fsPrintVote" data-role="flipswitch">
				<option value="0">No</option>
				<option value="1">Yes</option>
			</select>
			<div>Display a printable record after each vote</div>
		</div>
		<div class="ui-field-contain">
			<label for="fsPrivate">Make Private:</label>
			<select name="fsPrivate" id="fsPrivate" data-role="flipswitch">
				<option value="0">No</option>
				<option value="1">Yes</option>
			</select>
		</div>
		<div class="ui-field-contain">
			<label for="fsRandomOrder">Randomize Answers:</label>
			<select name="fsRandomOrder" id="fsRandomOrder" data-role="flipswitch">
				<option value="0">No</option>
				<option selected value="1">Yes</option>
			</select>
		</div>
		<div class="ui-field-contain">
			<label for="fsCustomSlugSwitch">Custom URL Slug:</label>
			<select name="fsCustomSlugSwitch" id="fsCustomSlugSwitch" data-role="flipswitch">
				<option selected value="0">No</option>
				<option value="1">Yes</option>
			</select>
		</div>
		<div class="ui-field-contain" id="customSlugInputContainer">
			<label for="fsCustomSlugInput">URL Slug (a-z, 0-9, 4-16 chars):</label>
			<input type="text" data-clear-btn="true" class="pollAnswer" id="fsCustomSlugInput" name="fsCustomSlugInput" placeholder="abcd1234"></input><br />
		</div>
		<?php if ($this->user->userID > 0) { ?>
		<div class="ui-field-contain">
			<label for="fsVerifiedVoting">Verified Voting:</label>
			<select name="fsVerifiedVoting" id="fsVerifiedVoting" data-role="flipswitch">
				<option selected value="0">No</option>
				<option value="1">Yes</option>
			</select>
		</div>
		<div class="ui-field-contain" id="verifiedVotingSelectContainer">
			<label for="fsVerifiedVotingType">Verification Type:</label>
			<select name="fsVerifiedVotingType" id="fsVerifiedVotingType">
				<option selected value="gkc">Generate CSV w/ Keys</option>
				<option disabled value="eml">Email Keys/Link</option>
				<option disabled value="gau">Google Auth</option>
			</select>
		</div>
		<?php } ?>
		<div class="ui-field-contain">
			<label for="fsStartEndSwitch">Start/End Times:</label>
			<select name="fsStartEndSwitch" id="fsStartEndSwitch" data-role="flipswitch">
				<option selected value="0">No</option>
				<option value="1">Yes</option>
			</select>
		</div>
		<div class="ui-field-contain" id="startEndInputContainer">
			<label for="fsStartDate">Start:</label>
			<input id="fsStartDate" type="date" data-inline="true" data-mini="true" value="<?php $oDate = new DateTime();echo $oDate->format('Y-m-d'); ?>" />
			<input id="fsStartTime" type="time" data-inline="true" data-mini="true" value="00:00" />
			<div class="clear"></div>
			<label for="fsEndDate">End:</label>
			<input id="fsEndDate" type="date" data-inline="true" data-mini="true" value="<?php echo $oDate->format('Y-m-d'); ?>" />
			<input id="fsEndTime" type="time" data-inline="true" data-mini="true" value="00:00" />
		</div>
		<div id="pollButtonContainer">
			<button id="createSurveyButton" data-inline="inline" onclick="createSurvey()">Create Survey</button>
		</div>
	</div>
</div>