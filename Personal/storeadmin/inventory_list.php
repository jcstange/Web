<?php
session_start();
if(!isset($_SESSION["manager"]))
{header("location:admin_login.php");
exit();}
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
<?php
//Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php
//Pergunta para deletar itens e deletar imagens
if(isset($_GET['deleteid'])){
	echo 'Voc&ecirc; realmente quer deletar o item '.$_GET['deleteid']. '? <a href="inventory_list.php?yesdelete=' .$_GET['deleteid']. '">Sim</a> | <a href="inventory_list.php">N&atilde;o</a>';
	exit();
}
if(isset($_GET['yesdelete'])){
	// Remover item do sistema e deletar imagens
	
	// Deletar do banco de dados
	$id_to_delete = $_GET['yesdelete'];
	$sql = mysql_query("DELETE FROM products WHERE id='$id_to_delete' LIMIT 1") or die (mysql_error);
	// Deslinkar imagem do servidor
		//Remover Imagem
			$pictodelete = ("../inventory_images/$id_to_delete.jpg");
			if(file_exists($pictodelete)){
				unlink($pictodelete);
			}
	header ("location: inventory_list.php");
}
?>
<?php
//Salva no banco de dados
if(isset($_POST['product_name'])){
	$product_name = mysql_real_escape_string($_POST['product_name']);
	$price = mysql_real_escape_string($_POST['price']);
	$category = mysql_real_escape_string($_POST['category']);
	$subcategory = mysql_real_escape_string($_POST['subcategory']);
	$details = mysql_real_escape_string($_POST['details']);
	//See if that product name is an identical match to another product in the System
	$sql = mysql_query ("SELECT id FROM products WHERE product_name='$product_name' LIMIT 1");
	$productMatch = mysql_num_rows($sql); //count the output amount
	if ($productMatch > 0){
		echo "Desculpe, infelizmente já existe esse produto no seu banco de dados, <a href='inventory_list.php'>Voltar</a>";
		exit();
	}
	//Add this product into the database now
	$sql = mysql_query("INSERT INTO products (product_name, price, details, category, subcategory, date_added) VALUES ('$product_name','$price','$details','$category','$subcategory',now())") or die (mysql_error());
	$pid = mysql_insert_id();
	$newname = "$pid.jpg";
	
    if(isset($_POST['button']))	{
			
   			$path = "../inventory_images/$newname";
			move_uploaded_file($_FILES['fileField']['tmp_name'], $path);
	}	
}
?>	
<?php
//This block grabs the hole list for viewing
$product_list="";
$sql = mysql_query("SELECT * FROM products ORDER BY id ASC");
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
		$product_list .= "<table width='90%' margin='10px' cellpadding='6' border='1'>
										<tr>
											<td width='30%'>
												<label><img src='../inventory_images/$id.jpg' width='100%'></label></td>
											<td width='70%'>	ID do produto:  $id &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='inventory_edit.php?pid=$id'>edit</a> &nbsp;&bull; <a href= 'inventory_list.php?deleteid=$id'>delete</a><br/>Nome:<strong>$product_name</strong><br> Pre&ccedil;o: R$$price <br> Categoria: $category <br> Subcategoria: $subcategory<br> Detalhes: $details <br> <em>Adicionado em:  $date_added</em><br><br>
											</td>
										</tr>
								</table>";
	}
}else{
	$product_list = "Voc&ecirc; ainda n&atilde;o possui nenhum item listado na sua loja";
}
?>

<!DOCTYPE html>
<html>
<head><title>Inventory List</title></head>
<link rel="stylesheet" href="../style/style.css" media="screen" /> 
     	 <body style="background-color:lightblue">
	         <?php include_once("../template_header.php");?>
			 <?php include_once("../template_menu2.php");?>
            <div id="content">
           		 <div id="newitem" align="right" margin='15px'>
           			 <a href="inventory_list.php#inventoryForm">+ Adicione um novo item na loja</a></div>
            	<h2>Inventory List</h2>
       		  <?php echo $product_list; ?>
           	  <h3>&darr;Adicione um novo item na loja&darr;</h3>
            <form action="inventory_list.php" enctype="multipart/form-data" name="myForm" id="myForm" method="post">
           		 <a name= "inventoryForm" id= "inventoryForm"></a>
  
           			 <table width="100%" margin="2px" border="1" align="center" cellspacing="0" cellpadding="6">
           				 <tr>
                                <td width="20%">Nome</td>
                                <td width="80%"><label><input name="product_name" type="text" id="product_name"size="50"/></label></td>
                       </tr> 
                           <tr>
                        <td width="20%">Pre&ccedil;o</td>
                        <td width="80%">
               				 <label>R$<input name="price" type="text" id="price"size="12"/>
          					 </label></td>
        			   </tr> 
         			  <tr>
            	<td width="20%">Categoria</td>
                <td width="80%">
                	<label>
                    	<select name="category" id="category"/>                 
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
                            <option value=""></option>
                            <option value="Chap&eacute;u">Chap&eacute;u</option>
                            <option value="Camisas">Camisas</option>
                            <option value="Cal&ccedil;as">Cal&ccedil;as</option>
                	</label>
                </td>
           </tr> 
              <tr>
            	<td width="20%">Detalhes do Produto</td>
                <td width="80%"><label><textarea name="details" id="details"cols="50" rows= "5"></textarea>
                </label></td>
           </tr> 
              <tr>
            	<td width="20%">Imagem do Produto</td>
                <td width="80%"><input name="fileField" type="file" id="fileField" /></td>
                
              </tr> 
               <tr>
            	<td width="20%">&nbsp;</td>
                <td width="80%"><label><input name="button" type="submit" id="button" value="Clique para adicionar esse item"/></label></td>
              </tr> 
           </table>
           </form>
            </div>
			<?php include_once("../template_footer.php");?>
		</body>
</html>