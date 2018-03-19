<?php if ($this->user->info->admin_level == 1) { ?>
	<div class="bigContainer">
		<div class="bigContainerTitle">Administration</div>
		<div class="bigContainerInner">
			Welcome aboard, Captain.<br />
			
		</div>
	</div>
	<div class="bigContainer">
		<div class="bigContainerTitle">Users (<a href="/admin/admincreateuser/">Add User</a>)</div>
		<div class="bigContainerInner">
			<?php
			foreach ($this->admin->users as $user) {
				echo '<a href="/admin/userdetails/'.$user->userID.'/">'.$user->firstName.' '.$user->lastName.'</a><br />';
			}
			?>
		</div>
	</div>
<?php } else { ?>
	Administrators only, please <a href="/user/login/">log in</a>.
<?php } ?>