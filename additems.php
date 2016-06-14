<!DOCTYPE HTML> 
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<h>Add item to sell</h>
<div style='margin-right:auto; margin-left:auto; border:1px solid'>
<form method="post" action="additems.php" enctype="multipart/form-data"> 
   Item Image:<input type='file' name='image'>
   <br><br>
   Item Category:<select name="itemcategory">
   <option value="Games">Games</option>
   <option value="Mobile">Mobile</option>
   <option value="Laptop">Laptop</option>
   <option value="Cameras">Cameras</option>
   <option value="Books">Books</option>
   <option value="Clothing">Clothing</option>
   <option value="Other">Other</option>
   </select>
   <br><br>
   <input type='submit' value='Add to Basket'>
   </p>
</form>
<form method="post" action="selection.php">
	<input type="submit" value="Go Back">
</form>
</div> 
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors','On');
require 'aws-autoloader.php';
use Aws\Common\Enum\Region;
use Aws\DynamoDb\Enum\Type;
use Aws\S3\S3Client;

use  Aws\DynamoDb\DynamoDbClient;
ini_set('display_errors', 'on');
$client = DynamoDbClient::factory(array(
   'credentials' => array(
        'key'    => 'AKIAJF62AA5TD6HXRTXQ',
        'secret' => '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',
    ),
         'region' => 'us-west-2'
));

if((isset($_POST["itemname"])) and (isset($_POST["itemdesc"]))){
$iname = $_POST["itemname"];
$idesc = $_POST["itemdesc"];
$icat = @$_POST["itemcategory"];
$email = @$_SESSION["email"];
$itemid = uniqid();
$image_name = @$_FILES['image']['name'];
$image_temp = @$_FILES['image']['tmp_name'];
$image_type = pathinfo($image_name,PATHINFO_EXTENSION);
 

$response = $client->putItem(array(
                               "TableName" => 'cloud_shop',
								"Item" => (array(
								"itemid" => array('S' => "$itemid"),
                                "itemname" => array('S' => "$iname"),
                                "itemdesc" => array('S' => "$idesc"),
								"itemcategory" => array('S' => "$icat"),
								"itemimage" => array('S' => "$image_name"),
								"email" => array('S' => "$email")
												)
											)
									)
							);

$bucket = "cloudprojectwebshreekanth";
$s3 = S3Client::factory(array(
	'key'    => 'AKIAJF62AA5TD6HXRTXQ',
        'secret' => '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',
	'region' => 'us-west-2'
	));

$result= $s3->upload(
	$bucket,
	$image_name,
	fopen($image_temp,'rb'),'public-read'
);

}

?>


</body>
</html>