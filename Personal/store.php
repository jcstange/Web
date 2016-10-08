<?php
// Selecionar pedido para os ultimos 6 itens
include "storescript/connect_to_mysql.php";

$dynamicList="";
$sql = mysql_query("SELECT * FROM products ORDER BY price ASC LIMIT 6");
$productCount = mysql_num_rows($sql);//count the output amount

if ($productCount > 0){
	while($row=mysql_fetch_array($sql)){
		$id = $row["id"];
		$product_name=$row["product_name"];
		$price = $row["price"];
		$details = $row["details"];
		$category = $row["category"];
		$subcategory = $row["subcategory"];
		$date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
		$dynamicList .= " <div id='produto'>
										<div id='pfoto'>
												<a href='product.php?pid=$id'><img class='img' src='inventory_images/$id.jpg'></a></div>
										<div id='descr'>
											<strong>$product_name</strong><br> 
											Pre&ccedil;o: R$$price <br> </div>
									</div>";
	}
}else{
	$dynamicList = "Nós ainda n&atilde;o possuimos nenhum item listado na nossa loja";
}
mysql_close();
?>


<!DOCTYPE html>
<html>
<head><title>JCStore</title>
<!--The following script tag downloads a font from the Adobe Edge Web Fonts server for use within the web page. We recommend that you do not modify it.-->
<script>var __adobewebfontsappname__="dreamweaver"</script>
<script src="http://use.edgefonts.net/allan:n4:default.js" type="text/javascript"></script>
 <link rel="stylesheet" href="style/store_style.css" media="screen" />
</head>
 <body>
 <?php include_once("template_header_store.php");?> 
    <div id="content"> 			
		<div id="storemenu">&nbsp;<br />
                								  &nbsp;Roupas<br />
                                                  &nbsp;Eletr&ocirc;nicos<br /><br />
<br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /><br /><br /><br /><br /></div>
				
                <div id="produtos"><?php echo $dynamicList;?></div>    
            </div>
		</body>
</html>