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
		<title>Add a Gift</title>
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
            <h1 id="logo">Add a Gift (Admin)</h1>        
            <!-- Nav -->
<?php
include('navbar.php');
?>
        </div>
    </div>
    <div id="main-wrapper">
        <div id="main" class="container">
<?php
    $db = new PDO('mysql:host=localhost;dbname='.DBNAME.';charset=utf8', DBUSERNAME , DBPASSWORD);

    if(isset($_POST["category_add"])) {
        $query = $db->prepare('INSERT INTO `categories` SET name="'.$_POST["category_add"].'"');
        $query->execute();
        $new_category = $_POST["category_add"];
    }
    if(isset($_POST["name"])) {
		$name = $_POST['name'];
		$price = $_POST['price'];
		$shop = $_POST['shop'];
		$url = $_POST['url'];
		$image = $_POST['image'];
		$category = $_POST['category'];
		$quantity = $_POST['quantity'];
        $unlimited = 0;
        $variable_price = 0;
        $no_get_item = 0;
        
        $options = "";
        if(isset($_POST['unlimited'])) {
            $options .= "Unlimited Quantity<br>";
            $unlimited = 1;
            $quantity = 100;
        } else {
            $options .= "<br>";
        }
        if(isset($_POST['variable_price'])) {
            $options .= "Variable Price<br>";
            $variable_price = 1;
        } else {
            $options .= "<br>";
        }
        if(isset($_POST['no_get_item'])) {
            $options .= "No 'Get this item' Option";
            $no_get_item  = 1;
        }
		
		$query = $db->prepare('INSERT INTO `presents`(`item_name`, `item_category`, `item_price`, `item_shop`, `item_url`, `item_imageurl`, `quantity`, `quantity_remaining`, `unlimited`,`variable_price`,`no_get_item`) VALUES ("'.$name.'","'.$category.'","'.$price.'","'.$shop.'","'.$url.'","'.$image.'","'.$quantity.'","'.$quantity.'","'.$unlimited.'","'.$variable_price.'","'.$no_get_item.'")');
		$query->execute();
    }
    
    $query = $db->prepare('SELECT name FROM `categories` ORDER BY name ASC');
    $query->execute();
    $categories = $query->fetchAll();
    $count = count($categories);
?>
        <table class="addpresent">
            <form method="POST" action="addgift.php" onsubmit="return checkform(this)">
<?php
    if(isset($_POST["name"])) {
        echo '<tr><td></td><td></td><td><strong>Added:</strong></td></tr>';
    }
?>
            <tr><td class="left">Name:</td><td><input type="text" name="name" required></input></td><td><?php echo $name;?></td></tr>
            <tr><td class="left">Price(£):</td><td><input type="text" name="price" id="price"></input></td><td><?php echo $price;?></td></tr>
            <tr><td class="left">Suggested Shop:</td><td><input type="text" name="shop" required></input></td><td><?php echo $shop;?></td></tr>
            <tr><td class="left">URL:</td><td><input type="text" name="url" required></input></td><td><?php echo $url;?></td></tr>
            <tr><td class="left">Image URL:</td><td><input type="text" name="image" required></input></td><td><img src='<?php echo $image;?>' width="100px"></td></tr>
            <tr><td class="left">Category:</td><td><select name="category">
<?php
    for($i = 0; $i < $count; $i++) {
?>
    <option value ="<?php echo $categories[$i]["name"];?>"><?php echo $categories[$i]["name"];?></option>
<?php
    }
?>
            </select></td><td><?php echo $category;?></td></tr>
            <tr><td class="left">Quantity:</td><td><select name="quantity">
<?php
    foreach (range(1, 100) as $number) {
        echo '<option value="'.$number.'">'.$number.'</option>';
    }
?>
            </select></td><td><?php echo $quantity;?></td></tr>
            <tr><td><b>Options:</b></td>
                <td>
                    <input type="checkbox" name="unlimited" value="unlimited">Unlimited Quantity<br>
                    <input type="checkbox" name="variable_price" value="variable_price">Variable Price<br>
                    <input type="checkbox" name="no_get_item" value="no_get_item">No "Get this item" Option<br>
                </td><td><?php echo $options;?></td></tr>
            <tr><td></td><td><button type="submit">Add Gift</button></td></tr>
            </form>
        </table>
        <table class="addpresent">
            <form method="POST" action="addgift.php" onsubmit="return checkform(this)">
<?php
    if(isset($_POST["category_add"])) {
        echo '<tr><td></td><td></td><td><strong>Added:</strong></td></tr>';
    }
?>
            <tr><td class="left">Category:</td><td><input type="text" name="category_add" required></input></td><td><?php echo $new_category;?></td></tr>
            <tr><td></td><td><button type="submit">Add Category</button></td></tr>
            
            </form>
        </table>
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

