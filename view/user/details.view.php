<a href="/user/"><- Users</a>
<h4>User Details for <?php echo $this->detailUser->firstname; ?> <?php echo $this->detailUser->lastname; ?> (ID# <?php echo $this->detailUserID; ?>)</h4>
<?php
// Logo
/*echo '<div>';
if (file_exists($this->photoPath)) {
	// Cache bust if the image is newly uploaded.
	echo '<img class="clientLogoMedium" src="'.$this->photoURL.'?m=' . filemtime($this->photoPath).'" />';
	echo '<span class="bold">Org Logo: </span><a href="/orgs/addlogo/'.$this->detailUser->org_id.'/">Change</a>';
} else {
	echo '<span class="bold">Org Logo: </span><a href="/orgs/addlogo/'.$this->detailUser->org_id.'/">Add</a>';
}
echo '</div>';*/
?>
<p>
	
	<?php if ($this->detailUser->type == 'retailer') { ?>
		<a href="/retailers/details/<?php echo $this->detailUser->seo_id; ?>/" class="ui-btn ui-btn-inline ui-corner-all ui-shadow">Retailer Details</a><br />
	<?php } ?>
	<span class="bold">Email: </span><a href="mailto:<?php echo $this->detailUser->email; ?>"><?php echo $this->detailUser->email; ?></a><br />
</p>

<!--<a href="/persons/details/<?php echo $this->detailPerson->person_id; ?>/" class="ui-btn ui-btn-inline ui-corner-all ui-shadow">Person Details</a>-->
<a href="/user/passadmin/<?php echo $this->detailUser->user_id; ?>/" class="ui-btn ui-btn-inline ui-corner-all ui-shadow">Change Password</a>