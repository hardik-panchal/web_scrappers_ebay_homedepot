<?php
error_reporting(E_ALL);
$username = "root";
$password = "";
$hostname = "localhost"; 
//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password)
  or die("Unable to connect to MySQL");

$selected = mysql_select_db("test",$dbhandle)
  or die("Could not select examples");


include "phpQuery-onefile.php";

//for($ik=199;$ik<722;$ik=$ik+18){

$url = "http://www.shopwisefurniture.com/servlet/the-Bedroom-cln-Bedroom-Sets/searchpath/26666/start/".$ik."/total/725/Categories";
$data = file_get_contents($url) or die("problem");
$pq = phpQuery::newDocumentHTML($data, $charset = 'utf-8');

$product = pq(".pl_img a");

foreach($product as $ep){
	$product_name[] = pq($ep)->attr('title');
	$link_1 = '';
	$link_1 = "http://www.shopwisefurniture.com".pq($ep)->attr('href');
	$product_link[] = $link_1;
}

$price = pq(".pl_sale");
foreach($price as $pp){
	$price_1 = '';
	$price_1 = trim(str_replace('Sale:','',pq($pp)->html()));
	$price_1 = str_replace(',','',$price_1);
	$product_price[] = $price_1;
}

$sku = pq(".pl_sku");
foreach($sku as $ps){
	$product_sku[] = trim(str_replace('SKU:','',pq($ps)->html()));
}


//echo "<pre>";print_r($product_name);echo "</pre>";
//echo "<pre>";print_r($product_link);echo "</pre>";
//echo "<pre>";print_r($product_price);echo "</pre>";
//echo "<pre>";print_r($product_sku);echo "</pre>";
//die;

if(count($product_name) > 0){
  for($i=0;$i<count($product_name);$i++){
     $insert = "insert into shopwisefurniture(name,price,detail,link,sku) values('".$product_name[$i]."','".$product_price[$i]."','','".$product_link[$i]."','".$product_sku[$i]."')";
	 mysql_query($insert); 
	 echo $product_name[$i];
	 echo "<br/>";
  }
}

//}
        
				


?>
