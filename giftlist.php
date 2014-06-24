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
            <h1 id="logo">Gift List</h1>
            <p>Here you'll find our gift list. If you have any issues please email our gift list coordinator, <a href="">click here</a>.</p>
        
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
    //Add person to "gift taken" list, and take quantity off "quantity_remaining"
	if(isset($_POST["id"])) {
		$id = $_POST['id'];
		$person = $_POST['person'];
		
		$db = new PDO('mysql:host=localhost;dbname='.DBNAME.';charset=utf8', DBUSERNAME , DBPASSWORD);
        if(isset($_POST["quantity"])) {
            $quantity = $_POST["quantity"];
            
            $query = $db->prepare('SELECT quantity_remaining, unlimited FROM `presents` WHERE item_id="'.$id.'"');
            $query->execute();
            $data = $query->fetchAll();  
            $unlimited = $data[0]["unlimited"];
            
            //if its unlimited don't change the quantity remaining
            if($unlimited == 0) {
                $new_remaining = $data[0]["quantity_remaining"] - $quantity;
                $query = $db->prepare('UPDATE `presents` SET quantity_remaining="'.$new_remaining.'" WHERE item_id="'.$id.'"');
                $query->execute();
            }
            
            $query = $db->prepare('INSERT INTO `people` (`taken_by`,`item_id`, `quantity`) VALUES ("'.$person.'","'.$id.'","'.$quantity.'")');
            $query->execute();
            
        } else { //only 1 left
            $query = $db->prepare('UPDATE `presents` SET quantity_remaining=0 WHERE item_id="'.$id.'"');
            $query->execute();
            $query = $db->prepare('INSERT INTO `people` (`taken_by`,`item_id`, `quantity`) VALUES ("'.$person.'","'.$id.'", "1")');
            $query->execute();
        }
        $last_id = $db->lastInsertId();
?>
	<p class="added"><b>Item: </b><?php echo $_POST["item_name"]?> <b>Your Name: </b> <?php echo $_POST["person"];?></p>
    <form method="POST" action="giftlist.php" style="display:inline;" >
	<input type="hidden" name="id_remove" value="<?php echo $last_id; ?>">
	<input type="hidden" name="item_id" value="<?php echo $id; ?>">
	<span><button type="submit">Wrong? Undo</button></span>
	</form>
	
<?php
	} else if(isset($_POST["id_remove"])) {
		$id = $_POST["id_remove"];
		$item_id = $_POST["item_id"];
		
		$db = new PDO('mysql:host=localhost;dbname='.DBNAME.';charset=utf8', DBUSERNAME , DBPASSWORD);
        
        $query = $db->prepare('SELECT quantity FROM `people` WHERE id="'.$id.'"');
        $query->execute();
        $quantity = $query->fetchColumn(); 
        $query = $db->prepare('SELECT quantity_remaining, unlimited FROM `presents` WHERE item_id="'.$item_id.'"');
        $query->execute();
        $data = $query->fetch();
        $unlimited = $data["unlimited"];
        
        if($unlimited == 0) {
            $new_remaining = $data["quantity_remaining"] + $quantity;
            $query = $db->prepare('UPDATE `presents` SET quantity_remaining="'.$new_remaining.'" WHERE item_id="'.$id.'"');
            $query->execute();
        }
        $query = $db->prepare('DELETE FROM `people` WHERE id="'.$id.'"');
        $query->execute();
?>
	<p class="removed">Changes undone</p>
<?php
	}
	
    $db = new PDO('mysql:host=localhost;dbname='.DBNAME.';charset=utf8', DBUSERNAME , DBPASSWORD);
    $query = $db->prepare('SELECT name FROM categories ORDER BY name');
    $query->execute();
    $categories = $query->fetchAll();    
    $count_categories = count($categories);
    
    for($j = 0; $j < $count_categories; $j++) {
    
        $query = $db->prepare('SELECT * FROM presents WHERE quantity_remaining > 0 AND item_category="'.$categories[$j]["name"].'" ORDER BY item_price, item_name ASC');
        $query->execute();
        $presents = $query->fetchAll();    
        $count_presents = count($presents);
        
        if($count_presents > 0) {
?>
    <h3><?php echo $categories[$j]["name"]; ?></h3>
    
<?php  
    for($i = 0; $i < $count_presents; $i++) {
        if($presents[$i]["unlimited"] == 1) {
            $quantity_remaining = "";
        } else if($presents[$i]["quantity_remaining"] > 1) {
            $quantity_remaining = " (x" . $presents[$i]["quantity_remaining"] . ")";
        } else {
            $quantity_remaining = "";
        }
        
        if($presents[$i]["variable_price"] == 1) {
            $price = "<i>variable</i>";
        } else {
            $price = "£" . $presents[$i]["item_price"];
        }
?>
    <div class="item">
        <div id="content_<?php echo $presents[$i]["item_id"];?>">
            <img class="item_image" src="<?php echo $presents[$i]["item_imageurl"]; ?>">
            <span class="item_info">
                <span class="item_span"><b><?php echo $presents[$i]["item_name"] . $quantity_remaining;?></b></span>
                <span class="item_span">Suggested Link: <a href="<?php echo $presents[$i]["item_url"];?>" target="_blank">Click Here</a></span>
                <span class="item_span">Price: <?php echo $price;?> (<?php echo $presents[$i]["item_shop"];?>)</span>
                <span class="item_span">
<?php
    if($presents[$i]["no_get_item"] == 0) {
?>
                <button onclick="showForm(<?php echo $presents[$i]["item_id"];?>)">Get this item</button>
<?php
    }
?>
                </span>
            </span>
        </div>
<?php
    if($presents[$i]["no_get_item"] == 0) {
?>
        <div id="form_<?php echo $presents[$i]["item_id"];?>" style="display:none;">
            <form method="POST" action="giftlist.php" onsubmit="return checkform(this)">
            <input type="hidden" name="id" value="<?php echo $presents[$i]["item_id"];?>">
            <input type="hidden" name="item_name" value="<?php echo $presents[$i]["item_name"];?>">
            <span class="item_span2"><b><?php echo $presents[$i]["item_name"];?></b></span>
            <span class="item_span"><b>Your Name:</b></span>
            <span class="item_span"><input type="text" name="person" style="width:200px;" required></span>
<?php
        if($presents[$i]["quantity_remaining"] > 1) {
?>
			<span class="item_span"><b>Quantity:</b></span>
			<span class="item_span"><select name="quantity" id="quantity">
<?php
            for($k=0;$k < $presents[$i]["quantity_remaining"]; $k++) {
?>
            <option value="<?php echo $k+1;?>"><?php echo $k+1;?></option>
<?php
            } //end for loop
?>
            </select></span>
<?php
        } //end if
?>
            <span class="item_span"><button type="submit">Confirm</button></span>
            </form>
            <span class="item_info">
            <span class="item_span"><button onclick="hideForm(<?php echo $presents[$i]["item_id"];?>)">Back to item</button></span>
            </span>
        </div>
<?php
   } //end "no_get_item" if
?>       
    </div>
<?php
    } //end presents loop
?>
    <div style="clear:both;"></div>
<?php
        } //end $count_presents if
    } //end categories loop
?>
            </div>
        </div>
    </div>
<?php
    include('footer.php');
?>
	</body>
</html>

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
