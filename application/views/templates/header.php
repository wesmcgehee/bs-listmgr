<?php ob_start(); ?>
<?php showHtmlHeader(); ?>
<?php verifyAutorization(); ?>
<?php setTimeZone(); ?>  
<head>
    <title>prototype bs-listmgr</title>
    <!-- bootstrap3 (bs3) -To ensure proper rendering and touch zooming, add the viewport meta tag to your head. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="dukeofaustin-prototype bs-listmgr">
    <meta name="author" content="dukeofaustin">

    <!--  site styling -->
    <link rel="stylesheet" href="<?php echo base_url();?>style/styles.css" type="text/css" />
    <!-- IE6-10 -->
    <link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico">
    <!-- Everybody else -->
    <link rel="icon" href="<?php echo base_url();?>favicon.ico">

    <!--  the following line uses normalize v2 instead of a reset.css -->
    <link rel="stylesheet" href="<?php echo base_url();?>style/normalize.css" type="text/css" />
    
	<!-- Bootstrap core CSS -->
    <link href="<?php echo base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo base_url();?>bootstrap/js/html5shiv.js"></script>
      <script src="<?php echo base_url();?>bootstrap/js/respond.min.js"></script>
    <![endif]-->
	
</head> 
<body>
   <!--[if !IE]><body class="body"><![endif]-->
   <!--[if IE ]><body class="ie-body"><![endif]-->
   <!--[if IE]>
   <p>Only IE shows this paragraph.</p>
   <![endif]-->
   <?php include_once('navigate.php'); ?>
   <?php include_once('jsandcss.php'); ?>
<?php
   /**
    * set default time zone
    * @return  void
    */
    function setTimeZone($which = 'America/Chicago')
	{
	  if (function_exists( 'date_default_timezone_set' ))
	    date_default_timezone_set($which);
	}
   /**
    * verifyAutorization cookie
    * @param   none
    * @return  void
   */
    function verifyAutorization() {
        $authorized = isset($_COOKIE['authorized']) && $_COOKIE['authorized'] == "1";
        if (!$authorized) {
			header("Location: index.php?login");
        }
    }
   /**
    * showHtmlHeader
    * @param   none
    * @return  void
   */
    function showHtmlHeader() {
		header('Content-type: text/html; charset=utf-8'); 
        echo '<!DOCTYPE html xml:lang="en-us" lang="en">';
    }
    //print_r($_SERVER)    
?>