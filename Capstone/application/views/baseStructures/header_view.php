<!DOCTYPE HTML>
<html lang = "en-US">
<head>

	<!--Start User Session-->
	<? session_start(); ?>

	<?php		
		/* Determine if a valid use sessions exist already. */
	    $this->Vert = $this->userauth->validSessionExists();		
		if ( $this->Vert == false)
		{
			$page = base_url() . "index.php?/Login";
			$this->userauth->redirect($page);
		} else {
			echo $this->userauth->ReFreshSession();
		}
	?>
	
	<title>Shift Manager</title> 
	
	<script src="https://code.jquery.com/jquery.js"> </script>		
	<script type="text/javascript" src="<?= assetUrl(); ?>js/ConvertPosition.js"></script>
	<script type="text/javascript" src="<?= assetUrl(); ?>js/TimeConverter.js"></script>
	<script type="text/javascript" src="<?= assetUrl(); ?>js/getdateFromSelect.js"></script>
	
	
	<!--My CSS-->
	<link href = "<?= assetUrl(); ?>css/AppStyles.css" rel="stylesheet" type="text/css"/>
		
	<!--For boot strap.. do later.-->
	<!-- Latest compiled and minified CSS -->
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
	<!--END For boot strap.. do later.-->
	
</head>
<body>
	
	
