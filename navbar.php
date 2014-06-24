<nav id="nav">
    <ul>
        <li><a class="fa fa-home" href="index.php"><span>Home</span></a></li>
        <li><a class="fa fa-building" href="accommodation.php"><span>Accommodation</span></a></li>
        <li><a class="fa fa-university" href="church.php"><span>The Church</span></a></li>
        <li><a class="fa fa-gift" href="giftlist.php"><span>Gift List</span></a></li>
        <li><a class="fa fa-envelope" href="#contact"><span>Contact</span></a></li>
    </ul>
<?php
if(isset( $_SESSION['admin'] )) {
?>
    <ul>
        <li><a class="fa fa-plus" href="addgift.php"><span>Add a Gift</span></a></li>
        <li><a class="fa fa-tasks" href="showgifts.php"><span>Show Gifts</span></a></li>
    </ul>
<?php
}
?>
</nav>