<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Green Health<?php if (!empty($this->title)) echo ' - '.$this->title; ?></title>
		<!-- jQuery CSS -->
		<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.css" />
		<!-- jQuery UI CSS -->
		<link rel="stylesheet" href="https://<?php echo $this->staticServer; ?>/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
		<!-- Local CSS -->
		<link rel="stylesheet" href="https://<?php echo $_SERVER['SERVER_NAME']; ?>/web/css/style.css" />
		<!-- Print CSS -->
		<?php if ($this->customCSS != '') { ?>
			<link rel="stylesheet" type="text/css" href="https://<?php echo $_SERVER['SERVER_NAME']; ?>/web/css/<?php echo $this->customCSS; ?>.css" />
		<?php } else { ?>
			<link rel="stylesheet" type="text/css" href="https://<?php echo $_SERVER['SERVER_NAME']; ?>/web/css/print.css" />
		<?php } ?>
		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
		<script type="text/javascript">
			$(document).on("mobileinit", function(){
				$.mobile.ajaxEnabled = false;
			});
		</script>
		<!-- Google JS API -->
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	</head>
	<body>