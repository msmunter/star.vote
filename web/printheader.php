<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>
		<?php if ($_SERVER['SERVER_NAME'] == 'star.vote') { ?>
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
		<!-- jQuery Mobile CSS -->
		<link rel="stylesheet" href="/web/css/jquery.mobile-1.4.5.min.css" />
		<!-- Local CSS -->
		<link rel="stylesheet" href="/web/css/style.css" />
		<!-- Print CSS -->
		<?php if ($this->customCSS != '') { ?>
			<link rel="stylesheet" type="text/css" href="https://<?php echo $_SERVER['SERVER_NAME']; ?>/web/css/<?php echo $this->customCSS; ?>.css" />
		<?php } else { ?>
			<link rel="stylesheet" type="text/css" href="https://<?php echo $_SERVER['SERVER_NAME']; ?>/web/css/print.css" />
		<?php } ?>
		<!-- jQuery -->
		<script src="/web/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript">
			$(document).on("mobileinit", function(){
				$.mobile.ajaxEnabled = false;
			});
		</script>
	</head>
	<body>