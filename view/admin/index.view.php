<?php if ($this->user->info->admin_level == 1) { ?>
	<div class="bigContainer">
		<div class="bigContainerTitle">Administration</div>
		<div class="bigContainerInner">
			Welcome aboard, Captain.<br />
			
		</div>
	</div>
	<div class="bigContainer">
		<div class="bigContainerTitle">Users <a class="ui-btn ui-btn-inline ui-icon-plus ui-btn-icon-notext ui-corner-all noMargin" href="/admin/admincreateuser/">Add User</a></div>
		<div class="bigContainerInner">
			<?php
			if ($this->userCount > 0) {
				foreach ($this->users as $user) {
					echo '<a href="/admin/userdetails/'.$user->userID.'/">'.$user->firstName.' '.$user->lastName.'</a><br />';
				}
			} else {
				echo 'No users other than you.';
			}
			?>
		</div>
	</div>
<?php } else { ?>
	Administrators only, please <a href="/user/login/">log in</a>.
<?php } ?>