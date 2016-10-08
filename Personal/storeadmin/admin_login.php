<?php
session_start();
if(isset($_SESSION["manager"])){
	header("location: index.php");
	exit();
	}
?><?php
if(isset($_POST["username"])&&isset($_POST["password"])){
	$manager = preg_replace('#[^A-Za-z0-9]#i', '',$_POST["username"]);
    $password = preg_replace('#[^A-Za-z0-9]#i', '',$_POST["password"]);
	include "../storescript/connect_to_mysql.php";
	$sql = mysql_query("SELECT id FROM admin WHERE username='$manager' AND password='$password' LIMIT 1");
	$existCount = mysql_num_rows($sql);
	if($existCount == 1){
		while($row = mysql_fetch_array($sql)){
			$id = $row["id"];
			}
    $_SESSION["id"] = $id;
    $_SESSION["manager"] = $manager;
    $_SESSION["password"] = $password;
    header("location: index.php");
    exit();
	}
else{
	echo 'That information is incorrect, try again <a href="admin_login.php">Click here</a>';
	exit();
	}
}
		?>
<!DOCTYPE html>
<html>
<head><title>Store Admin Log In</title></head>
<link rel="stylesheet" href="../style/style.css" media="screen" /> 
     	 <body style="background-color:lightblue">
             <?php include_once("../template_header.php");?>
			 <?php include_once("../template_menu2.php");?>
             <style>#store{backgroud-color:#38F500;margin:0;}</style>
            <div id="content">
            <div id="query">
            <br>Por favor, fa&ccedil;a o login para gerenciar a loja
            <form id="form1" name="form1" method="post" action="admin_login.php">User Name:<br/>
            <input name="username" type="text" id="username" size="40"/><br/><br/>
            Password:<br/>
            <input name="password" type="password" id="password" size="40"/><br/>
            <input type="submit" name="button" id="button" value="Log In"/><br/>
            </form><br>
            </div>
            </div>
			<?php include_once("../template_footer.php");?>
		</body>
</html>