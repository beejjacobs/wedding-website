<?php
include('config.php');
session_start();
if(isset($_SESSION['user_id']) && isset($_SESSION['admin'])) {
} else {
    unset($_SESSION['user_id']);
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
		<title>Gift List</title>
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
            <h1 id="logo">Gift List (Admin)</h1>        
            <!-- Nav -->
<?php
include('navbar.php');
?>
        </div>
    </div>
    <div id="main-wrapper">
        <div id="main" class="container">
            <div class="centermain">
			<h3>Gifts Taken</h3>
<?php
	$db = new PDO('mysql:host=localhost;dbname='.DBNAME.';charset=utf8', DBUSERNAME , DBPASSWORD);
    if(isset($_POST["change_person"])) {
        $query = $db->prepare('UPDATE `people` SET taken_by="'.$_POST["person"].'" WHERE id="'.$_POST["id"].'"');
        $query->execute();
?>
	<p class="added"><b>Item: </b><?php echo $_POST["name"]?> <b>Name changed to:</b> <?php echo $_POST["person"];?></p>
<?php
    } else if(isset($_POST["remove_item"])) {
        $query = $db->prepare('DELETE FROM `people` WHERE id="'.$_POST["id"].'"');
        $query->execute();
        $query = $db->prepare('UPDATE `presents` SET quantity_remaining = quantity_remaining + '. $_POST["quantity"]. ' WHERE item_id="'.$_POST["item_id"].'"');
        $query->execute();
?>
	<p class="added"><b>Item: </b><?php echo $_POST["name"]?> added back to gift list</p>
<?php
    } else if(isset($_POST["delete_item"])) {
		$query = $db->prepare('DELETE FROM `presents` WHERE id="'.$_POST["id"].'"');
        $query->execute();
?>
	<p class="added"><b>Item: </b><?php echo $_POST["name"]?> deleted from the site</p>
<?php
	}
	$query = $db->prepare('SELECT * FROM `people`');
	$query->execute();
	$people = $query->fetchAll();
	$count = count($people);
?>
    <table class="show_gifts">
    <tr><td><b>Item</b></td><td><b>Quantity</b></td><td><b>Person</b></td></tr>
<?php
	for($i = 0; $i < $count; $i++) {
    
    $query = $db->prepare('SELECT item_name FROM `presents` WHERE item_id="'.$people[$i]["item_id"].'" LIMIT 1');
	$query->execute();
	$item = $query->fetchAll();
?>
	<tr>
    <td><?php echo $item[0]["item_name"]?></td>
    <td><?php echo $people[$i]["quantity"]?></td>
    <td>
        <form method="POST" action="showgifts.php" style="display:inline;" onsubmit="return checkform(this)">
        <input type="text" name="person" value="<?php echo $people[$i]["taken_by"]?>" required>
        <input type="hidden" name="name" value="<?php echo $item[0]["item_name"]?>" >
        <input type="hidden" name="id" value="<?php echo $people[$i]["id"]?>">
        <input type="hidden" name="change_person" value="CHANGE">
        <button type="submit">Change</button></form>
        <form method="POST" action="showgifts.php" style="display:inline;">
        <input type="hidden" name="id" value="<?php echo $people[$i]["id"]?>">
        <input type="hidden" name="quantity" value="<?php echo $people[$i]["quantity"]?>">
        <input type="hidden" name="item_id" value="<?php echo $people[$i]["item_id"]?>">
        <input type="hidden" name="name" value="<?php echo $item[0]["item_name"]?>" >
        <input type="hidden" name="remove_item" value="REMOVE" >
        <button type="submit">Remove</button></form>
    </td>
    </tr>
<?php
	}
?>
	</table>
					
	<h3>Gifts Remaining</h3>
	<table class="show_gifts">
    <tr><td><b>Item</b></td><td><b>Remaining</b></td><td></td></tr>
<?php
	$query = $db->prepare('SELECT * FROM `presents` WHERE quantity_remaining > 0 ORDER BY item_name ASC');
	$query->execute();
	$items = $query->fetchAll();
	
	foreach($items as $gift) {
        if($gift["unlimited"] == 1) {
            $remaining = "Unlimited";
        } else {
            $remaining = $gift['quantity_remaining'];
        }
            
?>
	<tr>
	<td><?php echo $gift['item_name'];?></td>
    <td><?php echo $remaining;?></td>
	<td>
        <form method="GET" action="editgift.php" style="display:inline;" onsubmit="return checkform(this)">
        <input type="hidden" name="id" value="<?php echo $gift["item_id"]?>">
        <button type="submit">Edit</button>
        </form>
	</td>
	</tr>
<?php
	}
?>
    </table>
			</div>
        </div>
    </div>
<?php
    include('footer.php');
?>
	</body>
    <script>
    function showForm(id) {
        document.getElementById("content_"+id).style.display = "none";
        document.getElementById("form_"+id).style.display = "block";
    }
    function hideForm(id) {
        document.getElementById("content_"+id).style.display = "block";
        document.getElementById("form_"+id).style.display = "none";
    }
     
    function checkform(form) {
        // get all the inputs within the submitted form
        var inputs = form.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            // only validate the inputs that have the required attribute
            if(inputs[i].hasAttribute("required")){
                if(inputs[i].value == ""){
                    // found an empty field that is required
                    alert("Please fill all required fields");
                    return false;
                }
            }
        }
        return true;
    }
    </script>
</html>