<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Error</title>
<style type="text/css">


body {
	background-color: rgb(153,217,234);	
	
	text-align: center;	
}

#ErrorBlock {
	display: inline-block;
	border: 3px solid black;
	padding: 1em;
	text-align: left;
	position: relative;			
	width: 50%;		
	height: auto;	
	font-size: 1.5rem;
}



</style>
</head>
<body>
	
<div class = "ErrorBox" >


	<?php
		echo debug_print_backtrace();

	?>
	
	
		
	<div>
	Severity:    <?php echo $severity, "\n"; ?>
	Message:     <?php echo $message, "\n"; ?>
	Filename:    <?php echo $filepath, "\n"; ?>
	Line Number: <?php echo $line; ?>

	<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

	Backtrace:
	<?php	foreach (debug_backtrace() as $error): ?>
	<?php		if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>
		File: <?php echo $error['file'], "\n"; ?>
		Line: <?php echo $error['line'], "\n"; ?>
		Function: <?php echo $error['function'], "\n\n"; ?>
	<?php		endif ?>
	<?php	endforeach ?>

	<?php endif ?>
	</div>



</div>
</body>
</html>