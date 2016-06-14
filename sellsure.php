<html>
<head><h2>Sell Sure</h2></head>
<body>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
session_start();
echo "Welcome ".@$_SESSION['username']."..!";
echo "<form action='sellsure.php' method='get'>";
echo "<input type='hidden' name='logout' value='logout'>";
echo "<input type='submit' value='Logout'>";
echo "</form>";

echo "<form action='sellsure.php' method='get'>";
//echo "<input type='text' name='search'>";
echo "<input type='submit' value='Search for items'>";
echo "</form>";
echo "<br><br>";
/*echo "<div>";
echo "<form action='sellsure.php' method='get'>";
echo "<input type='text' name='searchcat'>";
echo "<input type='submit' value='Search Via Category'>";
echo "</form>";
echo "</div>";*/
// add another ITEM
echo "<div style='border:1px solid'>";
echo "<form action='additem.php' method='post'>";
echo "<h2>Add items to your basket</h2>";
echo "<input type='submit' value='Add to basket'>";
echo "</form>";
echo "</div>";



require 'aws-autoloader.php';
use  Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Enum\Type;
use Aws\S3\S3Client;
$client = DynamoDbClient::factory(array(
   'credentials' => array(
         'key'    => 'AKIAII5C65X6WEBVPBVA',
        'secret' => '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',
    ),
         'region' => 'us-west-2'
));

/*
//SEARCH VIA CATEGORY

if(isset($_GET['searchcat'])){
$searchcat = $_GET['searchcat'];

$response = $client->scan(array(
        'TableName' => 'cloudp3',
        'ProjectionExpression' => 'itemname,itemid,category,image,itemdesc,userid',
        'ExpressionAttributeValues' =>  array (
            ':a1' => array('S' => $searchcat )) ,
        'FilterExpression' => 'category = :a1' ,
));

echo "<h2>Search Results via category</h2>";
foreach($response['Items'] as $key=>$value){
	echo "<div style='border:1px solid'>";
	echo '<label>Item Name: '.@$value['itemname']['S'].'</label></br>';
	echo '<label>Item Id: '.@$value['itemid']['S'].'</label></br>';
	echo '<label>Category: '.@$value['category']['S'].'</label></br>';
	echo '<label>User Id: '.@$value['userid']['S'].'</label></br>';
	echo '<label><img src="https://s3.amazonaws.com/mycloudutaproject1/'.@$value['image']['S'].'" height="150" width="150"></label></br>';
	echo '</div>';

}

}*/

/*

// SEARCH ALL PICS
if(isset($_GET['search'])){

$search = $_GET['search'];

$response = $client->scan(array(
        'TableName' => 'cloudp3',
        'ProjectionExpression' => 'itemname,itemid,category,image,itemdesc,userid',
        
));

//print_r($response);
foreach($response['Items'] as $key=>$value){
	
	echo "<div style='border:1px solid'>";
	echo '<label>Item Name: '.@$value['itemname']['S'].'</label></br>';
	echo '<label>Item Id: '.@$value['itemid']['S'].'</label></br>';
	echo '<label>Category: '.@$value['category']['S'].'</label></br>';
	echo '<label>User Id: '.@$value['userid']['S'].'</label></br>';
	echo '<label><img src="https://s3.amazonaws.com/mycloudutaproject1/'.@$value['image']['S'].'" height="150" width="150"></label></br>';
	echo '</div>';

}
}
*/




/*
// SEARCH PERTICULAR PIC BY ITS NAME

if(isset($_GET['search'])){

$search = $_GET['search'];

$response = $client->scan(array(
        'TableName' => 'cloudp3',
        'ProjectionExpression' => 'itemname,itemid,category,image,itemdesc,userid',
        'ExpressionAttributeValues' =>  array (
            ':a1' => array('S' => $search )) ,
        'FilterExpression' => 'itemname = :a1' ,
));


foreach($response['Items'] as $key=>$value){
	
	echo "<div style='border:1px solid'>";
	echo '<label>Item Name: '.@$value['itemname']['S'].'</label></br>';
	echo '<label>Item Id: '.@$value['itemid']['S'].'</label></br>';
	echo '<label>Category: '.@$value['category']['S'].'</label></br>';
	echo '<label>User Id: '.@$value['userid']['S'].'</label></br>';
	echo '<label><img src="https://s3.amazonaws.com/mycloudutaproject1/'.@$value['image']['S'].'" height="150" width="150"></label></br>';
	echo '</div>';

}
}
*/



$userid = @$_SESSION['username'];

$response = $client->scan(array(
        'TableName' => 'cloudp3',
        'ProjectionExpression' => 'itemname,itemid,cby,image,comment1,userid',

));

//print_r($response);
foreach($response['Items'] as $key=>$value){
	echo "<div style='border:1px solid'>";
	echo "<form action='sellsure.php' method='get'>";
	echo '<label>Item Name: '.@$value['itemname']['S'].'</label></br>';
	echo '<label>Item Id: '.@$value['itemid']['S'].'</label></br>';
	echo '<label>Comment: '.@$value['comment1']['S'].'</label></br>';
	echo '<label>User Id: '.@$value['userid']['S'].'</label></br>';
	echo '<label><img src="https://s3.amazonaws.com/mycloudutaproject2/'.@$value['image']['S'].'" height="300" width="400"></label></br>';
    
    
    
    
    // COMMENT ON PIC
    if($_SESSION['username'] == $value['userid']['S']){
    echo "<textarea name='comment1' >".@$value['comment1']['S']."</textarea>";
    echo "<input type='hidden' name='update' value='".@$value['itemid']['S']."'>";
     echo "<input type='hidden' name='itemname' value='".@$value['itemname']['S']."'>";
     echo "by: ".@$value['userid']['S']."<input type='hidden' name='userid' value='".@$value['userid']['S']."'>";
    echo "<input type='hidden' name='image_name' value='".@$value['image']['S']."'>";
    
	echo "</br><label><input type='submit' name='AddComment' value='Add comment'></label>";
    }
    else{
        echo "<textarea name='comment1' disabled>".@$value['comment1']['S']."</textarea>";
    echo "by: ".@$value['userid']['S'];

    }
    
    
    
    if($_SESSION['username'] == $value['userid']['S']){
    
// DELETE PIC
	echo '<input type="hidden" name="delete" value="'.@$value['itemid']['S'].'">';
        echo '<input type="hidden" name="deleteB" value="'.@$value['image']['S'].'">';
	echo '</br><label><input type="submit" name="deleteitem" value="Delete the Item"></label>';
	    echo '</form>'; 
        echo '</div>';
    }// END OF IF()
    
     
}// END FOR()




/*

if(isset($_GET['searchcat'])){
$searchcat = $_GET['searchcat'];

$response = $client->scan(array(
        'TableName' => 'cloudp3',
        'ProjectionExpression' => 'itemname,itemid,category,image,itemdesc,userid',
        'ExpressionAttributeValues' =>  array (
            ':a1' => array('S' => $searchcat )) ,
        'FilterExpression' => 'category = :a1' ,
));

*/


// COMMENT ON SINGLE  PIC
if(isset($_GET['AddComment'])){
echo "YES";
    

$itemid = @$_GET['update'];
$comment1 = @$_GET['comment1'];
$itemname = @$_GET['itemname'];
$userid = @$_GET['userid'];
$image_name = @$_GET['image_name'];
//echo " comment1: ".@$comment1." itemid: ".$itemid." itemname ".$itemname." user id ".$userid;
    

    
    $response = $client->updateItem(array(
    'TableName' => 'cloudp3',
    "Key" => array (
        "itemid" => array (
            "S" => $itemid 
        ) 
    ),
     'ExpressionAttributeValues' =>  array (
        ':val1' => array(
            'S' => $comment1
        )
    ),
    'UpdateExpression' => 'set comment1 = :val1'
) );
    
header('location:sellsure.php');
}  
    
    
    
    
// DELETE SINGLE SELECTED PIC
if(isset($_GET['deleteitem'])){

$delete = @$_GET['delete'];
$deleteB = @$_GET['deleteB'];

$scan = $client->getIterator('scan',array('TableName' => 'cloudp3'));
foreach($scan as $item){
	$client->deleteItem(array(
	'TableName' => 'cloudp3',
	'Key' => array(
	'itemid' => array('S' => $delete)
	)
));
}
    
 
$bucket='mycloudutaproject1';
$s3 = S3Client::factory(array(
         'key'    => 'AKIAII5C65X6WEBVPBVA',
        'secret' => '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',
	
	));
    
$result = $s3->deleteObject(array(
    'Bucket' => $bucket,
    'Key'    => $deleteB
	
));
header('location:sellsure.php');
}








// LOGOUT
if(isset($_GET['logout'])){
  session_destroy();
  echo "session destroyed";
  header("location:index.php");
 }









?>
</body>
</html>
