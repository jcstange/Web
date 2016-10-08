<?php
session_start(); 
include "storescript/connect_to_mysql.php";
//Relatório de erro
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set('display_errors', '1');
?>
<?php
//Sessão 1 - Criar array dos itens do carrinho
if(isset($_POST['pid'])) {
	$pid = $_POST['pid'];
	$wasFound = false;
	$i = 0;
	//Se não houver sessão ou o carrinho estiver vazio
	if(!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
		$_SESSION["cart_array"] = array(0 => array ("item_id" => $pid, "quantity" =>1));
	} else {
		foreach ($_SESSION["cart_array"] as $each_item) {
			$i++;
			while (list($key, $value) = each ($each_item)) {
				if ($key == "item_id" && $value == $pid) {
					array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $pid, "quantity" => $each_item["quantity"] +1)));
					$wasFound = true;
				}
			}
		}
		if ($wasFound == false) {
			array_push ($_SESSION["cart_array"], array("item_id" => $pid, "quantity" => 1));	
		}
	}
	header("location: cart.php");
	exit();	
}
?>
<?php
//Sessão 2 - Esvaziar Carrinho
if(isset($_GET['cmd']) && $_GET['cmd'] == "emptycart") {
	unset($_SESSION["cart_array"]);
}
?>
<?php
//Sessão 3 - Ajustar quantidades da Array
if (isset($_POST['item_to_adjust']) && $_POST['item_to_adjust'] != "") {
	$item_to_adjust = $_POST['item_to_adjust'];
	$quantity = $_POST['quantity'];
	$quantity = preg_replace('#[^0-9]#i', '', $quantity);
	if($quantity >=100) {	$quantity = 99;	}
	if($quantity < 1) {	$quantity = 1;	}
	if($quantity == "") {	$quantity = 1;	}
	$i=0;
	foreach ($_SESSION["cart_array"] as $each_item) {
			$i++;
			while (list($key, $value) = each ($each_item)) {
				if ($key == "item_id" && $value == $item_to_adjust) {
					array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $item_to_adjust, "quantity" => $quantity)));
					$wasFound = true;
				}
			}
		}
		header("location: cart.php");
		exit();
}
?>
<?php
//Sessão 4 - Remover itens da Array
if(isset($_POST['index_to_remove']) && $_POST['index_to_remove'] != "") {
	$key_to_remove = $_POST['index_to_remove'];
	if (count($_SESSION["cart_array"]) <= 1) {
		unset($_SESSION["cart_array"]);
	} else {
		unset($_SESSION["cart_array"]["$key_to_remove"]);
	 	sort($_SESSION["cart_array"]);
	}
}
?>
<?php
//Sessão 5 - Tabela com itens do carrinho
$cartOutput = "";
$cartTotal = "0,00";
$pp_checkout_btn = '';
$product_id_array = '';

if (!isset ($_SESSION ["cart_array"]) || count($_SESSION ["cart_array"]) < 1) {
	$cartOutput = "<div id='vazio'>Seu carrinho de compras est&aacute; vazio!</div>";
} else {
	$pp_checkout_btn = 
	'<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_cart">
			<input type="hidden" name="upload" value="1">
			 <input type="hidden" name="business" value="jcstange@gmail.com">';
	//Start For Each loop
	$i = 0;
	foreach ($_SESSION ["cart_array"] as $each_item) {
		$item_id = $each_item['item_id'];
		$sql = mysql_query ("SELECT * FROM products WHERE id='$item_id' LIMIT 1");		
		while ($row = mysql_fetch_array($sql)) {
			$id = $row['id'];
			$product_name = $row ["product_name"];
			$price = $row["price"];
		}
		$totalprice = $price * $each_item['quantity'];
		$cartTotal = $totalprice + $cartTotal;
		$price = number_format($price, 2, ",", ".");
		$totalprice = number_format($totalprice, 2, ",", ".");
		
		//Dynamic Checkout Button Assembly
		$x = $i + 1;
		$pp_checkout_btn .= 
				'<input type="hidden" name="item_name_'. $x . '" value="'. $product_name . '">
                 <input type="hidden" name="amount_'. $x . '" value="'. $totalprice . '">
                 <input type="hidden" name="quantity_'. $x . '" value="'. $each_item['quantity'] . '">';
		
		// Create the product array variable
		$product_id_array .= "$item_id-".$each_item['quantity'].",";
		
		
		//Dynamic table row assembly
		$cartOutput .= " <tr>
			    <td align='center'>&nbsp;&nbsp;<a href='product.php?pid=$id'><img src='inventory_images/$item_id.jpg' alt='$product_name' width='40' height='52'><br>" .$product_name. "</a></td>";
		$cartOutput .=	   " <td>&nbsp;&nbsp;&nbsp;R$ " .$price. "</td>";		
		$cartOutput .=	   " <td align='center'><form action='cart.php' method='post'>
		<input name='quantity' type='text' value='" .$each_item['quantity']. "' size='1' maxlength='2' />
		<input name='adjustBtn" . $id . "' type='submit' value='Alterar'/>
		<input name='item_to_adjust' type='hidden' value='" . $id ."'/ >
		</form> </td>";
		$cartOutput .=	   "<td>&nbsp;&nbsp;&nbsp;R$ " .$totalprice. "</td>";
		$cartOutput .=	  "<td align='center'><form action='cart.php' method='post'><input name='deleteBtn" . $id . "' type='submit' value='X'/><input name='index_to_remove' type='hidden' value='" . $i ."'/ ></form> </td></tr>";
			  $i++;
	}
	$cartTotal = number_format($cartTotal, 2, ",", ".");
	$cartTotal = "<div align='right'> Total do Carrinho: R$ $cartTotal </div>";

	//Finish the Paypal Checkout Button
		$pp_checkout_btn .=	'<div align="right">
				 <input type="hidden" name="custom" value="' . $product_id_array . '">
				 <input type="hidden" name="notify_url" value="https://www,yourstore.com/storescripts/my_ipn.php">
                 <input type="hidden" name="return" value="https://www,yourstore.com/checkout_complete.php">
                 <input type="hidden" name="rm" value="2">
                 <input type="hidden" name="cbt" value="Return to The Store">
                 <input type="hidden" name="cancel_return" value="https://www.yousite.com/paypal_cancel.php">
                 <input type="hidden" name="lc" value="BR">
                 <input type="hidden" name="currency_code" value="USD">
                 <input type="image" src="http://www.paypal.com/en_US/i/btn/x-click-but01.gif" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!">
                 </form></div>';
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
 <link rel="stylesheet" href="style/store_style.css" media="screen" />
<title>Seu Carrinho de Compras</title>
<!--The following script tag downloads a font from the Adobe Edge Web Fonts server for use within the web page. We recommend that you do not modify it.-->
<script>var __adobewebfontsappname__="dreamweaver"</script>
<script language="JavaScript" src="http://use.edgefonts.net/bubblegum-sans:n4:default;fascinate-inline:n4:default.js" type="text/javascript">
</script>

</head>
<body>
    <div id="tudo">
	 	   <?php include_once ("template_header_store.php");?>
    
        <div id="content2">
          <table id='table1' width="100%" border="1">
            <tr>
              <th width="219" bgcolor="#43B332" style="font-family:Palatino; font-style:bold;" scope="col">Produto</th>
              <th width="100" bgcolor="#43B332" style="font-family:Palatino; font-style:bold;" scope="col">Preço Unit.</th>
              <th width="46"  bgcolor="#43B332" style="font-family:Palatino; font-style:bold;" scope="col">Quant.</th>
              <th width="100" bgcolor="#43B332" style="font-family:Palatino; font-style:bold;" scope="col">Preço Total</th>
              <th width="100" bgcolor="#43B332" style="font-family:Palatino; font-style:bold;" scope="col">Remover</th>
            </tr>
            <?php echo $cartOutput;?>
          </table>
          <p><br />
             <?php echo $cartTotal;?>
             <br />
             <?php echo $pp_checkout_btn;?>
  			<br />	
			  <a href="cart.php?cmd=emptycart">Clique aqui para esvaziar seu carrinho de compra</a></p>

        </div>
</div>
</body>
</html>