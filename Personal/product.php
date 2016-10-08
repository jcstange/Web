<?php
//Relatório de erro
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set('display_errors', '1');
?>

<?php
//Verificar se a variável selecionada existe no banco de dados
include "/storescript/connect_to_mysql.php";

if(isset($_GET['pid'])) {
	$targetID = $_GET['pid'];
	$sql = mysql_query("SELECT * FROM products WHERE id='$targetID' LIMIT 1");
	$productCount  = mysql_num_rows ($sql);
	if($productCount > 0) {
		//Puxar os detalhes dos produtos
		while($row=mysql_fetch_array($sql)) {
			$id = $row ["id"];
			$product_name = $row["product_name"];
			$price = $row["price"];
			$details = $row["details"];
			$category = $row["category"];
			$subcategory = $row["subcategory"];
			$date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
		}

	} else {
		echo "Esse item n&atilde;o existe";
		echo $targetID;
	    exit();
	}
	
} else {
  echo "N&atilde;o h&aacute; produto com esse nome no sistema.<a href='store.php'>Voltar</a>";
  exit();
}
mysql_close();
?>



<!DOCTYPE html>
<html>
<head><title><?php echo $product_name;?></title></head>
<link rel="stylesheet" href="style/store_style.css" media="screen" /> 

     	 <body>
	         <?php include_once("template_header_store.php");?>
            <div id="contentprod">   
                <div id="prodcontainer">
                  <div id="prphoto">
					  <a href="inventory_images/<?php echo $targetID;?>.jpg">
                       	  <img src="inventory_images/<?php echo $targetID;?>.jpg" alt="<?php echo $product_name; ?>" id="prodphoto">
                     </a>
                    </div>
                    <div id="details">
							<?php echo "$id - $product_name <br><br />
																	R$ $price <br><br />
																	$details <br><br />"; ?>
                       <form id="form1"name="form1" method="post" action="cart.php"/>
                       <input type="hidden" name="pid" id="pid" value="<?php echo $id; ?>" />
                       <input type="submit" name="button" id="button" value="Adicionar no Carrinho" />
               	  </div>
                
           	</div>
        </div>
           
		</body>
</html>