<h4>Users (<a href="/user/add/">Add</a>)</h4>
<?php if (!empty($this->users)) { 
	foreach ($this->users as $user) {
		echo '<a href="/user/details/'.$user->user_id.'/">'.$user->firstName.' '.$user->lastName;
		echo '</a> - <a href="/user/passadmin/'.$user->user_id.'/">Pass</a><br />';
	}
} else {
	echo 'No users found.';
}
?>