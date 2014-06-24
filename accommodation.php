<?php
include('config.php');
session_start();
if(isset( $_SESSION['user_id'] )) {
} else {
    header( "Location: ".SITEURL );
}
?>
<!DOCTYPE HTML>
<!--
	Strongly Typed 1.1 by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Accommodation</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="viewport" content="width=1040" />
        <link rel="shortcut icon" href="favicon.ico">
		<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600|Arvo:700" rel="stylesheet" type="text/css" />
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/config.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-panels.min.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-desktop.css" />
		</noscript>
	</head>
	<body class="homepage">

    <!-- Header Wrapper -->
    <div id="header-wrapper">
        <!-- Header -->
        <div id="header" class="container">
            <!-- Logo -->
            <h1 id="logo">Accommodation</h1>
            <p>Some information about local accommodation can be found here</p>
        
            <!-- Nav -->
<?php
include('navbar.php');
?>
        </div>
    </div>
<!-- Features Wrapper -->
    <div id="features-wrapper">
        <!-- Features -->
        <section id="features" class="container">
            <div class="row">
                <div class="4u">
                    <!-- Feature -->
                    <section>
                        <a href="#" class="image image-full"><img src="images/BB02.jpg" alt="" /></a>
                    </section>
                </div>
                <div class="4u">
                    <!-- Feature -->
                    <section>
                        <a href="#" class="image image-full"><img src="images/03.png" alt="" /></a>
                    </section>
                </div>
                <div class="4u">
                    <!-- Feature -->
                    <section>
                        <a href="#" class="image image-full"><img src="images/BB01.jpg" alt="" /></a>
                    </section>
                </div>
            </div>
        </section>
    </div>
		
	<!-- Banner Wrapper -->
    <div id="banner-wrapper">
        <div class="inner">
            <!-- Banner -->
            <section id="banner" class="container">
                <p>To have and to hold</p>
            </section>
        </div>
    </div>
    
<?php
    include('footer.php');
?>

	</body>
</html>