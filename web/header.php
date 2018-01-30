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
			Star.vote [ Dev ]
		<?php } else { ?>
			Star.vote Beta
		<?php } ?>
		<?php if (!empty($this->title)) echo ' - '.$this->title; ?>
		</title>
		<!-- Icon/Favicon -->
		
		<!-- jQuery CSS -->
		<!--<link rel="stylesheet" href="/web/css/starvote.min.css" />-->
		<!-- jQuery Mobile CSS -->
		<!--<link rel="stylesheet" href="/web/css/jquery.mobile.icons.min.css" />-->
		<link rel="stylesheet" href="/web/css/jquery.mobile-1.4.5.min.css" />
		<!-- jQuery UI CSS -->
		<!--<link rel="stylesheet" href="/web/css/jquery-ui-1.12.1.css" />-->
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
		<!-- jQuery UI -->
		<!--<script src="/web/js/jquery.ui-1.12.1.min.js"></script>-->
		<?php if ($_SERVER['SERVER_NAME'] == 'star.vote') { ?>
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
			<div id="headerActual" data-role="header" class="ui-bar">
				<!-- Header -->
				<div id="pageHeader">
					<a href="https://<?php echo $_SERVER['SERVER_NAME']; ?>/">
						<img id="headerLogo" src="https://<?php echo $_SERVER['SERVER_NAME']; ?>/web/images/starvote_logo.png" alt="Logo" />
					</a>
					<a id="headerTitle" href="https://<?php echo $_SERVER['SERVER_NAME']; ?>/">
						<?php if ($_SERVER['SERVER_NAME'] == 'starvote.msmunter.com') { ?>
							[ Dev ]
						<?php } else { ?>
							Beta
						<?php } ?>
					</a>
				</div>
				<div id="breadCrumbs" data-role="controlgroup" data-type="horizontal" class="ui-mini ui-btn-right">
					<a href="/poll/create/" class="ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-plus">New</a>
					<a href="/poll/history/" class="ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bullets">More</a>
					<a href="http://equal.vote/" class="ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-info">Learn</a>
				</div>
			</div>
			<!-- /Header -->
			<div role="main" class="ui-content" id="main">