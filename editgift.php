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
            <h1 id="logo">Edit Gift (Admin)</h1>        
            <!-- Nav -->
<?php
include('navbar.php');
?>
        </div>
    </div>
    <div id="main-wrapper">
        <div id="main" class="container">
            <div class="centermain">
<?php
	$db = new PDO('mysql:host=localhost;dbname='.DBNAME.';charset=utf8', DBUSERNAME , DBPASSWORD);
    
    if(isset($_POST["delete_gift"])) {
    
        $id = $_POST['id'];
        $name = $_POST['name'];
        
        $query = $db->prepare('SELECT quantity, quantity_remaining FROM `presents` WHERE item_id="'.$id.'" LIMIT 1');
        $query->execute();
        $gift = $query->fetch();
        
        if($gift['quantity_remaining'] < $gift['quantity']) {
?>
	<p class="added"><b>Warning: </b> cannot remove item already taken</p>
<?php
        } else {
            $query = $db->prepare('DELETE FROM `presents` where item_id="'.$id.'"');
            $query->execute();
?>
	<p class="added"><b>Item: </b><?php echo $name;?> permanently removed</p>
<?php         
        }
    } else if(isset($_POST["name"])) {
    
        $query = $db->prepare('SELECT quantity, quantity_remaining FROM `presents` WHERE item_id="'.$_GET['id'].'" LIMIT 1');
        $query->execute();
        $gift = $query->fetch();
        
        $old_quantity = $gift['quantity'];
        $old_quantity_remain = $gift['quantity_remaining'];
    
        $name = $_POST['name'];
		$price = $_POST['price'];
		$shop = $_POST['shop'];
		$url = $_POST['url'];
		$image = $_POST['image'];
		$category = $_POST['category'];
		$quantity = $_POST['quantity'];
        $quantity_remain = $_POST['quantity'];
        $unlimited = 0;
        $variable_price = 0;
        $no_get_item = 0;
        
        $options = "";
        if(isset($_POST['unlimited'])) {
            $unlimited = 1;
            $quantity = 100;
        }
        if(isset($_POST['variable_price'])) {
            $variable_price = 1;
        }
        if(isset($_POST['no_get_item'])) {
            $no_get_item  = 1;
        }
        
        if($quantity != $old_quantity) {
            $diff = $quantity - $old_quantity;
            $quantity_remain = $old_quantity_remain + $diff;
            
            if($quantity_remain < 0) {
                $do_not_execute = 1;
            }
        }
        if($do_not_execute != 1) {
            $query = $db->prepare('UPDATE `presents` SET item_name="'.$name.'", item_price="'.$price.'", item_shop="'.$shop.'", item_url="'.$url.'", item_imageurl="'.$image.'", item_category="'.$category.'", quantity="'.$quantity.'", quantity_remaining="'.$quantity_remain.'", unlimited="'.$unlimited.'", variable_price="'.$variable_price.'", no_get_item="'.$no_get_item.'" WHERE item_id="'.$_GET['id'].'"');
            $query->execute();
?>
	<p class="added"><b>Item: </b><?php echo $name;?> changed as below.</p>
<?php
        } else {
?>
	<p class="added"><b>Warning: </b> Invalid quantity. Item unchanged.</p>
<?php
        }
    }
    
    $query = $db->prepare('SELECT * FROM `presents` WHERE item_id="'.$_GET['id'].'" LIMIT 1');
	$query->execute();
	$gift = $query->fetch();
    if($gift['unlimited'] == 1) {
        $unlimited_check = "checked";
    }
    if($gift['variable_price'] == 1) {
        $variable_price_check = "checked";
    }
    if($gift['no_get_item'] == 1) {
        $no_get_item_check = "checked";
    }
    
?>
            <table class="addpresent">
            <form method="POST" action="editgift.php?id=<?php echo$_GET['id'];?>" onsubmit="return checkform(this)">
            <tr><td class="left">Name:</td><td><input type="text" name="name" value ="<?php echo $gift['item_name'];?>" required></input></td></tr>
            <tr><td class="left">Price(£):</td><td><input type="text" name="price" id="price" value ="<?php echo $gift['item_price'];?>" required></input></td></tr>
            <tr><td class="left">Suggested Shop:</td><td><input type="text" name="shop" value ="<?php echo $gift['item_shop'];?>" required></input></td><td></tr>
            <tr><td class="left">URL:</td><td><input type="text" name="url" value ="<?php echo $gift['item_url'];?>" required></input></td></tr>
            <tr><td class="left">Image URL:</td><td><input type="text" name="image" value ="<?php echo $gift['item_imageurl'];?>" required></input></td></tr>
            <tr><td class="left">Category:</td><td><select name="category">
<?php
    $query = $db->prepare('SELECT name FROM `categories` ORDER BY name ASC');
    $query->execute();
    $categories = $query->fetchAll();
    $count = count($categories);
    
    for($i = 0; $i < $count; $i++) {
        if($categories[$i]["name"] == $gift['item_category']) {
            echo '<option value="'.$categories[$i]["name"].'" selected>'.$categories[$i]["name"].'</option>';
        } else {
            echo '<option value="'.$categories[$i]["name"].'">'.$categories[$i]["name"].'</option>';
        }
    }
?>
            </select></td></tr>
            <tr><td class="left">Quantity:</td><td><select name="quantity">
            
<?php
    foreach (range(1, 100) as $number) {
        if($number == $gift['quantity']) {
            echo '<option value="'.$number.'" selected>'.$number.'</option>';
        } else {
            echo '<option value="'.$number.'">'.$number.'</option>';
        }
    }
?>
            </select></td></tr>
            <tr><td><b>Options:</b></td>
                <td>
                    <input type="checkbox" name="unlimited" value="unlimited" <?php echo $unlimited_check;?>>Unlimited Quantity<br>
                    <input type="checkbox" name="variable_price" value="variable_price" <?php echo $variable_price_check;?>>Variable Price<br>
                    <input type="checkbox" name="no_get_item" value="no_get_item" <?php echo $no_get_item_check;?>>No "Get this item" Option<br>
                </td></tr>
            <tr><td></td>
            <td>
                <button type="submit">Edit Gift</button>
                </form>
            </td></tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
            <tr>
            <td></td>
            <td>
                <form method="POST" action="editgift.php?id=<?php echo $_GET['id'];?>" onsubmit="return checkform(this)">
                <input type="hidden" name="delete_gift" value="delete" >
                <input type="hidden" name="id" value="<?php echo $_GET['id'];?>" >
                <input type="hidden" name="name" value="<?php echo $gift['item_name'];?>" >
                <button type="submit">Delete Gift</button>
                
                </form>
            </td></tr>
            <tr><td>&nbsp;</td><td><b>(Cannot be undone)</b></td></tr>
            
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