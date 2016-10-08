<?php
session_start();
if(!isset($_SESSION["manager"])){
	header("location: admin_login.php");
	exit();
}
// Be shure to check that this manager SESSION value is in fact in the database
?>
<?php
$managerID = preg_replace('#[^0-9]#i', '',$_SESSION["id"]);
$manager = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["manager"]);
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]);
include "../storescript/connect_to_mysql.php";
$sql=mysql_query("SELECT * FROM admin WHERE id='$managerID' AND username='$manager' AND password='$password' LIMIT 1");//query the person
//MAKE SURE PERSON EXISTS IN DATABASE
$existCount = mysql_num_rows($sql);//count the row nums
if($existCount == 0){//evaluate the count
	echo "Your login session data is not on record in the database";
	exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>Store Admin Area</title></head>
<link rel="stylesheet" href="../style/style.css" media="screen" /> 
     	 <body style="background-color:lightblue">
	         <?php include_once("../template_header.php");?>
			 <?php include_once("../template_menu2.php");?>
            <div id="content">
            <div id="query">
            <a href="inventory_list.php">Manage Inventory</a><br>
            Manage </div>
            </div>
			<?php include_once("../template_footer.php");?>
		</body>
</html>