<!DOCTYPE HTML>
<html lang = "en-US">
<head>
	<title>Shift Manager</title>
	<link href = "<?= assetUrl(); ?>css/AppStyles.css" rel="stylesheet" type="text/css"/>
	
<? session_start(); ?>
</head>
<body>
	<div id= "loginParentBox" >
		<div id = "loginBox" >
		
			
			<h1><b>Aquatic Employee<br>Schedule Website </b></h1>
			<br>
			<h2>You have logged out. </h2>
			<br>
			<h3>Click <a href = "<?= base_url()?>">here</a> to log in</h3>
				
				<?  if ( !empty($msg) ){ ?>
				<div id = "errorBox">
					<p>
						<?= $msg ?>				
					</p>
				</div>
				<?}?>
		</div>
	</div>

	<!--
<p>Debug data <p>
<br><br>

	
<pre>
Session:
<? print_r($_SESSION); ?>
</pre>

<pre>
Cookie:
<? print_r($_COOKIE); ?>
</pre>
			

<br><br>
<a href = "<?= base_url()?>index.php/Home/logout" >Log out. </a>			
-->			
	
	<div id = "authorship">
	<p>Created by <br> Norman Potts </p>
	</div>
	
</body>
</html>