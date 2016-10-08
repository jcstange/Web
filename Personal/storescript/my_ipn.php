<?php
//Check to see there are posted variables coming into the script
if($_SERVER['REQUEST_METHOD'] != "POST") die ("No Post Variables");
// Initialize the $req variable and add CMD key value pair
$req = 'cmd=_notify-validate';
// Read the post from Paypal
foreach ($_POST as $key => $value) {
	$value = urlencode (stripslashes($value));
	$req .= "&$key=$value";
}
// Now Post all of that back to PayPal's server using curl to validate everything with them
// fsockOpen in PHP is troblesome at times so we use curl for this
$url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
//$url = "https://www.paypal.com/cgi-bin/webscr";
$curl_result = $curl_err = '';
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL,$url);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_POST, 1);
curl_setopt ($ch, CURL_POSTFIELDS, $req);
curl_setopt ($ch, CURLOPT_httpheader, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($req) . ""));
curl_setopt ($ch, CURLOPT_HEADER, 0);
curl_setopt ($ch, CURLOPT_VERBOSE, 1);
curl_setopt ($ch, CURL_SSL_VERIFYPEER, FALSE);
curl_setopt ($ch, CURLOPT_TIMEOUT, 30);
$curl_result = @curl_exec($ch);
$curl_err = curl_error ($ch);
curl_close ($ch);

$req = str_replace ("&", "\n", $req); // Make it a nice list in case we want to email it to ourselves for reporting

// Check that the result verifies
if (strpos ($curl_result, "VERIFIED") !== false) {
	$req .= "\n\nPaypal Verified OK";
} else {
	$req .= "\n\nData NOT verified from Paypal!";
	mail("jcstange@gmail.com", "IPN interaction not verified", "$req", "From: jcstange@gmail.com");
	exit();
}

/*CHECK THESE 4 THINGS BEFORE PROCESSING THE TRANSACTION, HANDLE THEM AS YOU WISH
1. Make sure that business email returned is your business email
2. Make sure that the transaction's payment status is 'completed'
3. Make sure there are no duplcate txn_id
4. Make sure the payment amount matches what charge for items. (Defeat Price-Jacking) */

// Check Number 1------------------------------------------------------------------------------------------
$receiver_email = $_POST['receiver_email'];
if ($receiver_email != "jcstange@gmail.com") {
	$message = "Investigate why and how receiver email is wrong. Email = " . $_POST['receiver_email']. "\n\n\n$req";
	mail("jcstange@gmail.com", "Receiver Email is incorrect", $message, "From: jcstange@gmail.com" );
	exit();
}

// Check Number 2------------------------------------------------------------------------------------------
if ($_POST['payment_status'] != "Completed") {
	echo "Your payment is not completed yet";
	// Handle how you think you should if a payment is not complete yet, a few scenarios cause a transaction to be incomplete
}

// Connect to database
require_once 'connect_to-mysql.php';
// Check Number 3
$this_txn = $_POST['txn_id'];
$sql = mysql_num_rows($sql);
if ($numRows > 0) {
	$message = "Duplicate transaction ID occured so we killed the IPN script. \n\n\n$req";
	mail("jcstange@gmail.com", "Duplicate txn_id in the IPN system", $message, "From: jcstange@gmail.com");
	exit();
}

// Check Number 4
$product_id_string = $_POST['custom'];
$product_id_string = rtrim($product_id_string, ","); //remove last comma
// Explode the string, make it an array, then query all the prices out, add them up, and make sure they match the payment_gross amount
$id_str_array = explode(",", $product_id_string);// Uses Comma(,) as delimiter(break point)
$fullAmount = 0;
foreach ($id_str_array as $key => $value) {
	
	$id_quantity_pair = explode ("-", $value); // Uses hyphen(-) as delimiter to separate product ID from its quantity
	$product_id = $id_quantity_pair[0]; // Get the product ID
	$product_quantity = $id_quantity_pair[1]; // Get the quantity
	$sql = mysql_query ("SELECT price FROM products WHERE id='$product_id' LIMIT 1");
	while ($row = mysql_fetch_array ($sql)) {
		$product_price = $row["price"];
	}
	$product_price = $product_price * $product_quantity;
	$fullAmount = $fullAmount + $product_price;
}
$fullAmount = number_format ($fullAmount, 2);
$grossAmount = $_POST['mc_gross'];
if ($fullAmount != $grossAmount) {
	$message = "Possible Price Jack: " . $_POST['payment_gross'] . " != $fullAmount \n\n\n$req";
	mail("jcstange@gmail.com","Price Jack or Bad Programming", $message, "From: jcstange@gmail.com");
	exit();
}
// END ALL SECURITY CHECKS NOW IN THE DATABASE IT GOES----------------------------------

$txd_id = $_POST ['txn_id'];
$payer_email = $_POST ['payer_email'];
$custom = $_POST ['custom'];
//Place the transaction into the database
$sql = mysql_query ("INSERT INTO transactions (product_id_array, payer_email, first_name, last_name, payment_date, mc_gross, payment_currency, txn_id, receiver_email, payment_type, payment_status, txn_type, payer_status, address_street, address_city, address_state, address_zip, address_country, address_status, notify_version, verify_sign, payer_id, mc_currency, mc_fee)
VALUES ('$custom', '$payer_email', '$first_name', '$last_name', '$payment_date', '$mc_gross', '$payment_currency', '$txd_id', '$receiver_email', '$payment_type', '$payment_status' , '$txn_type', '$payer_status', '$address_street', '$address_state', '$address_zip', '$address_country', '$address_status', '$notify_version', '$verify_sign', '$payer_id', '$mc_currency', '$mc_fee')") or die ("unable to execute the query");

mysql_close();
mail("jcstange@gmail.com", "NORMAL IPN RESULT YAY MONEY!", $req, "From: jcstange@gmail.com");
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