<html>
<head>
<h2>Add items to your basket</h2>
</head>
<body>
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors','On');
$userid = @$_SESSION['username'];
echo "<form method='post' action='additem.php' enctype='multipart/form-data'>";
echo "<label>Item Name       : <input type='text' name='itemname'></label><br>";
echo "<label>comment:  <textarea name='comment'>Enter text here...</textarea>  </label></br>";
echo "<label>Upload Image:</label>";
echo "<input type='file' name='image'><br>";
echo "<input type='submit' value='Add to basket'>";
echo "</form>";
echo "<form action='sellsure.php' method='post'>";
echo "<input type='submit' value='Go back to search page'>";
echo "</form>";

require 'aws-autoloader.php';
use Aws\DynamoDb\DynamoDbClient;
use Aws\Common\Enum\Region;
use Aws\DynamoDb\Enum\Type;
use Aws\S3\S3Client;
$client = DynamoDbClient::factory(array(
   'credentials' => array(
         'key'    => 'AKIAJX227GQBIAGGOK2Q',
        'secret' => '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'
    ),
         'region' => 'us-west-2'
));


if((isset($_POST['itemname']))&&(isset($_POST['comment']))){

$cby =  @$_SESSION['username'];
$itemid = uniqid();
$itemname = $_POST['itemname'];
$comment1 = $_POST['comment'];
$image_name = @$_FILES['image']['name'];
$image_temp = @$_FILES['image']['tmp_name'];
$image_size = @$_FILES['image']['size'];
$image_type = pathinfo($image_name,PATHINFO_EXTENSION); 
$error = @$_FILES['image']['error'];
echo "Comment:";
echo $comment1;
echo "<br>Name:";
echo $image_name;
echo "<br>Itemname:";
echo $itemname;
echo "<br>commented by:";
echo "$cby";
echo "<br>UserID:";
echo $userid;
echo "<br>ItemId:";
echo $itemid;
echo "<br>Size:";
echo $image_size;
echo "<br>";
echo $error;
echo "<br>";

if ($image_size >= 1073741824) {
        $size= number_format($image_size / 1073741824, 2);
    } elseif ($image_size >= 1048576) {
        $size= number_format($image_size / 1048576, 2);
    } elseif ($image_size >= 1024) {
        $size= number_format($image_size / 1024, 2);
    } elseif ($image_size > 1) {
        $size= $image_size;
    } elseif ($image_size == 1) {
        $size= '1 byte';
    } else {
        $size= '0 bytes';
    }
echo "In size:";
echo $size;

if(($error==1)||($size==0)){
#echo "The size is $size <br>";
echo "cannot upload";
}else{
echo "Uploaded";
}



$response = $client->putItem(array(
                               "TableName" => 'cloudp3',
		"Item" => (array(
				     "userid"=> array('S'=> "$userid"),
                     "itemid" => array('S' => "$itemid"),
				     "comment1" => array('S' => "$comment1"),
				     "itemname" => array('S' => "$itemname"),
				     "image" => array('S' => "$image_name"),
				     "cby"=> array('S' => "$cby")               
                              )
)
)
);

$bucket='mycloudutaproject2';
$s3 = S3Client::factory(array(
         'key'    => 'AKIAJX227GQBIAGGOK2Q',
        'secret' => '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',
	
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
