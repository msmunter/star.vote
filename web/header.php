<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="star.vote Voting">
		<meta name="keywords" content="vote, voting" />
		<meta name="robots" content="follow, index" />
		<title>
		<?php if ($_SERVER['SERVER_NAME'] == 'starvote.msmunter.com') { ?>
			Star.vote [Test Server]
		<?php } else { ?>
			Star.vote
		<?php } ?>
		<?php if (!empty($this->title)) echo ' - '.$this->title; ?>
		</title>
		<!-- Icon/Favicon -->
		
		<!-- jQuery CSS -->
		<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
		<!-- jQuery UI CSS -->
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css" />
		<!-- Local CSS -->
		<link rel="stylesheet" href="/web/css/style.css" />
		<!-- jQuery -->
		<script
			src="https://code.jquery.com/jquery-3.2.1.min.js"
			integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
			crossorigin="anonymous">
		</script>
		<script type="text/javascript">
			$(document).on("mobileinit", function(){
				$.mobile.ajaxEnabled = false;
			});
		</script>
		<!-- jQuery Mobile -->
		<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
		<!-- jQuery UI -->
		<script
			src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
			integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
			crossorigin="anonymous">
		</script>
	</head>
	<body>
		<div data-role="page">
			<div id="headerActual" data-role="header" class="ui-bar ui-bar-a">
				<!-- Header -->
				<div id="pageHeader">
					<a href="https://<?php echo $_SERVER['SERVER_NAME']; ?>/">
						<div id="headerTitle">
							<!--<img id="headerLogo" src="web/images/starvote_logo.png" alt="Logo" />-->
							<?php if ($_SERVER['SERVER_NAME'] == 'starvote.msmunter.com') { ?>
								Star.vote [Test Server]
							<?php } else { ?>
								Star.vote
							<?php } ?>
						</div>
					</a>
				</div>
				<div id="breadCrumbs">
					<a href="/poll/create/">New Poll</a> - 
					<a href="/poll/history/">Other Polls</a> - 
					<a href="/learn/">Learn</a>
				</div>
			</div>
			<!-- /Header -->
			<div role="main" class="ui-content" id="main">