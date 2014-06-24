<?php
include('config.php');
session_start();

//if(isset($_POST['username']) && isset($_POST['password'])) {
if(isset($_POST['password'])) {
    //$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    $password = sha1( $password );
    
    /*** connect to database ***/
    try {
        $db = new PDO('mysql:host=localhost;dbname='.DBNAME.';charset=utf8', DBUSERNAME , DBPASSWORD);

        /*** set the error mode to excptions ***/
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /*** prepare the select statement ***/
        $query = $db->prepare("SELECT user_id, username, password FROM users 
                    WHERE password = :password");
        //$query = $db->prepare("SELECT user_id, username, password FROM users 
        //            WHERE username = :username AND password = :password");
        //$query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR, 40);
        $query->execute();

        /*** check for a result ***/
        $user_id = $query->fetchColumn();
		
        if($user_id == false) {
            $message = 'Login Failed';
        } else {
            $_SESSION['user_id'] = $user_id;
            
            if($user_id == "3") {
                $_SESSION['admin'] = $user_id;
            }
            $message = 'You are now logged in';
        }


    } catch(Exception $e) {
        /*** if we are here, something has gone wrong with the database ***/
        $message = 'We are unable to process your request. Please try again later"';
    }
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
		<title>Wedding Website</title>
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
            <h1 id="logo">Our Wedding Website</h1>
            <p>Welcome to our wedding website. Here you'll find a bit about the Church and some local accommodation. You'll also find our gift list where you can browse and choose what to get, so two people don't end up buying the same!</p>
        
            <!-- Nav -->
<?php
include('navbar.php');
?>
        </div>
    </div>
<?php
if(isset( $_SESSION['user_id'] )) {
?>
<!-- Features Wrapper -->
    <div id="features-wrapper">
        <!-- Features -->
        <section id="features" class="container">
            <header>
                <h2>We're getting<strong> married</strong>!</h2>
            </header>
            <div class="row">
                <div class="4u">
                    <!-- Feature -->
                    <section>
                        <a href="#" class="image image-full"><img src="images/01.png" alt="" /></a>
                    </section>
                </div>
                <div class="4u">
                    <!-- Feature -->
                    <section>
                        <a href="#" class="image image-full"><img src="images/04.png" alt="" /></a>
                    </section>
                </div>
                <div class="4u">
                    <!-- Feature -->
                    <section>
                        <a href="#" class="image image-full"><img src="images/03.png" alt="" /></a>
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
} else {
?>
    <div id="main-wrapper">
        <div id="main" class="container">
            <form action="index.php" method="post">
            <table class="login">
                <tr><td class="left">Password:</td><td><input type="password" id="password" name="password" value="" maxlength="20" /></td></tr>
                <tr><td class="left"></td><td><button type="submit">Submit</button></td></tr>
            </table>
        </div>
    </div>
<?php
}
?>
    
<?php
    include('footer.php');
?>
	</body>
</html>