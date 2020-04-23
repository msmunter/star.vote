<?php if ($this->user->userID > 0 && $this->user->userID == $this->survey->userID) { ?>
	<?php // Nothing here if it's your poll ?>
<?php } else { ?>
	<div class="ui-field-contain">
		<div>Upload identifying images (<a href="/docs/identifyingimages/" target="_blank">help</a>):</div>
		<div id="identImageContainer">
			<input type="hidden" id="cdnHandle1" name="cdnHandle1" value="<?php echo $this->identImage->cdnHandle1; ?>" />
			<input type="hidden" id="cdnHandle2" name="cdnHandle2" value="<?php echo $this->identImage->cdnHandle2; ?>" />
			<table>
				<tr>
					<td class="identImageCell">
						<?php if ($this->identImage->cdnHandle1) { ?>
							<a id="identImageLink1" href="https://cdn.filestackcontent.com/<?php echo $this->identImage->cdnHandle1; ?>">
								<img id="identImagePreview1" class="identImagePreview large" src="https://cdn.filestackcontent.com/resize=width:200,fit:clip,align:top/<?php echo $this->identImage->cdnHandle1; ?>" alt="Identifying Image 1 Preview" />
							</a>
						<?php } else { ?>
							<img id="identImagePreview1" class="identImagePreview small" src="/web/images/img_placeholder.svg" alt="Identifying Image 1 Preview" />
						<?php } ?>
					</td>
					
				</tr>
				<tr>
					<td class="center" id="uploadButtonCell1">
						<?php if (!$this->identImage->cdnHandle1) { ?>
							<button class="uploadIdentImageButton" id="uploadIdentImageButton1" data-inline="inline" onclick="uploadIdentImage(1)">Add Image 1</button>
						<?php } else { ?>
							Image 1 saved
						<?php } ?>
					</td>
				</tr>
			</table>
			<table>
				<tr>
					<td class="identImageCell">
						<?php if ($this->identImage->cdnHandle2) { ?>
							<a id="identImageLink2" href="https://cdn.filestackcontent.com/<?php echo $this->identImage->cdnHandle2; ?>">
								<img id="identImagePreview2" class="identImagePreview large" src="https://cdn.filestackcontent.com/resize=width:200,fit:clip,align:top/<?php echo $this->identImage->cdnHandle2; ?>" alt="Identifying Image 2 Preview" />
							</a>
						<?php } else { ?>
							<img id="identImagePreview2" class="identImagePreview small" src="/web/images/img_placeholder.svg" alt="Identifying Image 2 Preview" />
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td class="center" id="uploadButtonCell2">
						<?php if (!$this->identImage->cdnHandle2) { ?>
							<button class="uploadIdentImageButton" id="uploadIdentImageButton2" data-inline="inline" onclick="uploadIdentImage(2)">Add Image 2</button>
						<?php } else { ?>
							Image 2 saved
						<?php } ?>
					</td>
				</tr>
			</table>
		</div>
	</div>
	
<?php } ?>