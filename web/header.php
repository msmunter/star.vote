<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="star.vote Voting">
		<meta name="keywords" content="vote, voting" />
		<meta name="robots" content="follow, index" />
		<title>
		<?php if ($_SERVER['SERVER_NAME'] == 'classic.star.vote') { ?>
			&#9733;.&#10003;
		<?php } else { ?>
			&#9733;.&#10003; [Dev]
		<?php } ?>
		<?php if (!empty($this->title)) echo ' - '.$this->title; ?>
		</title>
		<!-- Icon/Favicon -->
		<link rel="apple-touch-icon" sizes="57x57" href="/web/images/ico/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/web/images/ico/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/web/images/ico/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/web/images/ico/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/web/images/ico/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/web/images/ico/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/web/images/ico/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/web/images/ico/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/web/images/ico/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="/web/images/ico/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/web/images/ico/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/web/images/ico/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/web/images/ico/favicon-16x16.png">
		<link rel="manifest" href="/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/web/images/ico/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		<!-- jQuery CSS -->
		<!--<link rel="stylesheet" href="/web/css/starvote.min.css" />-->
		<!-- jQuery Mobile CSS -->
		<link rel="stylesheet" href="/web/css/jquery.mobile-1.4.5.min.css" />
		<!-- Local CSS -->
		<link rel="stylesheet" href="/web/css/style.css" />
		<!-- jQuery -->
		<script src="/web/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript">
			$(document).on("mobileinit", function(){
				$.mobile.ajaxEnabled = false;
			});
		</script>
		<!-- jQuery Mobile -->
		<script src="/web/js/jquery.mobile-1.4.5.min.js"></script>
		<?php if ($_SERVER['SERVER_NAME'] == 'classic.star.vote') { ?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113220345-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'UA-113220345-1');
		</script>
		<?php } ?>
	</head>
	<body>
		<div id="page" data-role="page">
			<div id="headerActual" data-role="header" class="ui-bar ui-header ui-bar-inherit" role="banner" style="background-color: #282828; border-bottom-color: black;">
				<div style="display: flex; justify-content: center; color: white; margin-top: 10px; margin-bottom: 10px; font-family: Verdana, sans-serif; align-items: center; margin-left: auto; margin-right: auto; width: 80%">
					<a href="https://bettervoting.com" style="color: white; text-decoration: none; font-family: sans-serif; width: 500px; text-align: center; font-weight: 400; text-shadow: unset; font-size: 20px">Try the new voting experience at BetterVoting.com !</a>
					<a href="https://bettervoting.com" style="color: white; text-decoration: none"><img src="https://bettervoting.com/logo.png" style="height: 40px"/></a>
				</div>
			</div>
			<div id="headerActual" data-role="header" class="ui-bar">
				<!-- Header -->
				<div id="pageHeader">
					<a href="https://<?php echo $_SERVER['SERVER_NAME']; ?>/">
						<img id="headerLogo" src="https://<?php echo $_SERVER['SERVER_NAME']; ?>/web/images/starvote_logo.png" alt="Logo" />
					</a>
					<a id="headerTitle" href="https://<?php echo $_SERVER['SERVER_NAME']; ?>/">
						<?php if ($_SERVER['SERVER_NAME'] == 'classic.star.vote') { ?>
							<!-- Previously there was a .vote here, but that's removed with the new logo -->
						<?php } else { ?>
							&raquo; Dev &laquo;
						<?php } ?>
					</a>
				</div>
				<?php if ($this->kioskMode && empty($this->user->userID)) {
					?><div id="breadCrumbsTitle"><?php
					if ($this->survey->surveyID) { 
						echo $this->survey->title;
					} else if ($this->poll->pollID) {
						echo $this->poll->question;
					}
					?></div><?php
				} else { ?>
					<div id="breadCrumbs" data-role="controlgroup" data-type="horizontal" class="ui-mini ui-btn-right">
					<!--<a href="/poll/create/" class="ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-plus">New</a>-->
					<?php if ($this->user->info->admin_level == 1) { ?>
						<a href="/user/" class="ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-user"><?php echo $this->user->info->initials; ?></a>
						<a href="/admin/" class="ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-gear">Admin</a>
						<a href="/user/logout/" class="ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-delete">Logout</a>
					<?php } else if ($this->user->userID > 0) { ?>
						<a href="/user/" class="ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-user"><?php echo $this->user->info->initials; ?></a>
						<a href="/poll/create/" class="ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-plus">New</a>
						<a href="/user/logout/" class="ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-delete">Logout</a>
					<?php } else { ?>
						<a href="/poll/history/" class="ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bullets">More</a>
						<a href="https://www.starvoting.us/" class="ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-info">Learn</a>
						<a href="/user/login/" class="ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-user">Login</a>
					<?php } ?>
					</div>
				<?php } ?>
			</div>
			<!-- /Header -->
			<div role="main" class="ui-content" id="main">
