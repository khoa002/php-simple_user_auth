<?php
define("allow_inc", true);
require_once("_func.global.php");
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Welcome to YZM!</title>
		<link href="/yzm.css" rel="stylesheet" type="text/css" />
		<link href="http://fonts.googleapis.com/css?family=Signika:400,300,700,600" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="/scr/uploadify/uploadify.css" />
		<link href="/tree_256x256.ico" rel="icon" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script type="text/javascript">
		function set_page_title(title) { top.document.title = title + " | YZM"; }
		</script>
	</head>
	<body>
		<div id="overall_wrapper">
			<div id="inner_wrapper">
				<div id="logo">Welcome to YZM...</div>
				<div id="top_menu"><a href="/home/">Home</a><?php if ($user !== false) { ?> | <a href="/profile/">Profile</a> | <a href="/logout/">Logout</a><?php } ?></div>
				<div id="page_content"><?php include($page); ?></div>
				<div id="page_footer">&copy; Khoa Nguyen | 2014</div>
			</div>
		</div>
	</body>
</html>