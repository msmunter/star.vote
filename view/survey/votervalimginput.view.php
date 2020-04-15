<?php if ($this->user->userID > 0 && $this->user->userID == $this->survey->userID) { ?>
	<?php // Nothing here if it's your poll ?>
<?php } else { ?>
	<div class="ui-field-contain">
		<div>Upload your identifying image:</div>
		<div id="identImageContainer">
			<?php if ($this->identImage) { ?>
				<img id="identImagePreview" src="https://cdn.filestackcontent.com/<?php echo $this->identImage->cdnHandle; ?>" alt="Identifying Image Preview" />
			<?php } else { ?>
				<table><tr><td>
					<img id="identImagePreview" src="/web/images/img_placeholder.svg" alt="Identifying Image Preview" />
				</td><td>
					<button id="uploadIdentImageButton" data-inline="inline" onclick="uploadIdentImage()">Add Image</button>
				</td></tr>
			</table>
			<?php } ?>
		</div>
	</div>
	
<?php } ?>