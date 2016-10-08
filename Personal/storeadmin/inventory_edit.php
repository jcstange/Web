<?php
session_start();
if(!isset($_SESSION["manager"]))
{header("location:admin_login.php");
exit();}
// Be shure to check that this manager SESSION value is in fact in the database
$managerID = preg_replace('#[^0-9]#i', '',$_SESSION["id"]);
$manager = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["manager"]);
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]);
include "../storescript/connect_to_mysql.php";
$sql=mysql_query("SELECT * FROM admin WHERE id='$managerID' AND username='$manager' AND password='$password' LIMIT 1");//query the person
//MAKE SURE PERSON EXISTS IN DATABASE
$existCount = mysql_num_rows($sql);//count the row nums
if($existCount == 0){//evaluate the count
	echo "Sua sess&atilde;o de login n&atilde;o está gravada no banco de dados";
	exit();
}
?>
<?php
//Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php
//Atualiza no banco de dados
if(isset($_POST['product_name'])){
	$id = mysql_real_escape_string($_POST['thisID']);
	$product_name = mysql_real_escape_string($_POST['product_name']);
	$price = mysql_real_escape_string($_POST['price']);
	$details = mysql_real_escape_string($_POST['details']);
	$category = mysql_real_escape_string($_POST['category']);
	$subcategory = mysql_real_escape_string($_POST['subcategory']);

	$sql = "UPDATE products 
				SET product_name='$product_name', price='$price', details='$details', category='$category', subcategory='$subcategory' 
				WHERE id='$id'";
	$result = mysql_query($sql) or die (mysql_error());
//	UPDATE `products` SET `id`=[value-1],`product_name`=[value-2],`price`=[value-3],`details`=[value-4],`category`=[value-5],`subcategory`=[value-6],`date_added`=[value-7] WHERE 1
//	exit();
	if ($_FILES['fileField']['tmp_name'] != "") {
		
		$updatename = "$id.jpg";
		$path = "../inventory_images/$updatename";
		if(file_exists($path))	{
		unlink("../inventory_images/$updatename");
		}
		move_uploaded_file($_FILES['fileField']['tmp_name'], $path);
		print "foto $updatename salva!";
		header("location: inventory_list.php");
	}
	else{ 
		print "$id, $product_name, $price, $details, $category, $subcategory";
		header("location: inventory_list.php");	
	}	    		
}
?>
<?php
//Pegar todas as informações e inserir no formulário de edição abaixo da pagina
if(isset($_GET['pid'])) {
	$targetID = $_GET['pid'];
	$sql = mysql_query("SELECT * FROM products WHERE id='$targetID' LIMIT 1");
	$productCount  = mysql_num_rows ($sql);
	if($productCount > 0){
		while ($row = mysql_fetch_array($sql)){
			$id = $row['id'];
			$product_name = $row ['product_name'];
			$price = $row ['price'];
			$category = $row ['category'];
			$subcategory = $row ['subcategory'];
			$details = $row ["details"];
			$date_added = strftime("%b %d, %Y", strtotime($row['date_added']));
			//echo "$id $product_name $price $category $subcategory $details $date_added";
			//exit();
	}
}else{
	echo  "Esse produto n&atilde;o existe <a href='inventory_list.php'>Voltar</a>";
	exit();}
}
?>



<!DOCTYPE html>
<html>
<head><title>Edit Item</title></head>
<link rel="stylesheet" href="../style/style.css" media="screen" /> 
     	 <body style="background-color:lightblue">
	         <?php include_once("../template_header_store.php");?>
			 <?php include_once("../template_menu2.php");?>
            <div id="content">
            <div id="newitem" align="right">
            <a href="inventory_list.php">Voltar para a lista de itens</a></div>
            <h2>Edit Item</h2>
            <?php // echo $product_list; ?>
            <form action="inventory_edit.php" enctype="multipart/form-data" name="myForm" id="myForm" method="post">
            <a name= "inventoryForm" id= "inventoryForm"></a>
  
            <table width="90%" margin="10px" border="1" cellspacing="0" cellpadding="6">
            <tr>
            <td width="20%">ID</td>
                <td width="80%"><?php echo $id;?></td>
           </tr> 
            <tr>
            	<td width="20%">Nome</td>
                <td width="80%"><label><input name="product_name" type="text" id="product_name"size="64" value="<?php echo $product_name;?>"/></label></td>
           </tr> 
           <tr>
            	<td width="20%">Pre&ccedil;o</td>
                <td width="80%">
                <label>R$<input name="price" type="text" id="price"size="12" value="<?php echo $price;?>"/>
           </label></td>
           </tr> 
           <tr>
            	<td width="20%">Categoria</td>
                <td width="80%">
                	<label>
                    	<select name="category" id="category" />                 
                        	<option value="<?php echo $category;?>"><?php echo $category;?></option>
                            <option value=""></option>
                            <option value="Eletr&ocirc;nicos">Eletr&ocirc;nicos</option>
                            <option value="Roupas">Roupas</option>
           		  </label>
             </td>
           </tr> 
              <tr>
            	<td width="20%">Subcategoria</td>
                <td width="80%">
                	<label>
                    	<select name="subcategory"  id="subcategory"/>
                        	<option value="<?php echo $subcategory;?>"><?php echo $subcategory;?></option>
                            <option value=""></option>
                            <option value="Chap&eacute;u">Chap&eacute;u</option>
                            <option value="Camisas">Camisas</option>
                            <option value="Cal&ccedil;as">Cal&ccedil;as</option>
                	</label>
                  </td>
           </tr> 
              <tr>
            	<td width="20%">Detalhes do Produto</td>
                <td width="80%"><label><textarea name="details" id="details"cols="50" rows= "5" ><?php echo $details;?></textarea>
                </label></td>
           </tr> 
              <tr>
            	<td width="20%">Imagem do Produto</td>
                <td width="80%">
                	<img src='../inventory_images/<?php echo $id;?>.jpg' width='20%'/>
                    <label for="file">
                    	<input name="fileField" type="file" id="fileField" value="Upload Foto" />
                     </label>
                    </td>
              </tr> 
               <tr>
            	<td width="20%">&nbsp;</td>
                <td width="80%"><label>
                <input name="thisID" type="hidden" value="<?php echo $targetID;?>"/>
                <input name="button" type="submit" id="button" value="Alterar esse item"/></label></td>
              </tr> 
           </table>
           </form>
            </div>
			<?php include_once("../template_footer.php");?>
		</body>
</html>