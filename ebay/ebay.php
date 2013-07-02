<?php
include "phpQuery-onefile.php";

$url = "http://www.ebay.com/";
//$url = "http://www.ebay.com/_feedhome/feeds/before/1370670374000/?_=1370672498087";
//$url = "http://www.ebay.com/_feedhome/feeds/before/1370670788000/?_=1370674030987";
//$url = "http://www.ebay.com/_feedhome/feeds/before/1370666386000/?_=1370674045654";
//$url = "http://www.ebay.com/_feedhome/feeds/before/1370662879000/?_=1370674054200";
//$url = "http://www.ebay.com/_feedhome/feeds/before/1370659307000/?_=1370674059295";
//$url = "http://www.ebay.com/_feedhome/feeds/before/1370656827000/?_=1370674065191";
//$url = "http://www.ebay.com/_feedhome/feeds/before/1370654831000/?_=1370674071443";
//$url = "http://www.ebay.com/_feedhome/feeds/before/1370652353000/?_=1370674077689";
$data = file_get_contents($url) or die("problem");

$pq = phpQuery::newDocumentHTML($data, $charset = 'utf-8');

$product = pq(".feed .more .info a");

foreach($product as $ep){
	$value_product[] = pq($ep)->attr('title');
}
$description = pq(".feed .description a");
foreach($description as $edp){
	$value_description[] = pq($edp)->attr('title');
	
	$value_link[] = pq($edp)->attr('href');
}
$price = pq(".feed .more .prc");

foreach($price as $epr){
	$value_price[] = pq($epr)->html();
}




header('Content-Encoding: UTF-8');
header('Content-type: text/csv; charset=UTF-8');
//header("Content-Disposition:attachment;filename=ebay.csv");
header("Content-Disposition:attachment;filename=ebay7.csv");
$data = '';
$data.= "\n";
$data.= "Product Name,Product Price,Product Description,Product Link\n";
for($i=0;$i<count($value_product);$i++){
    $value_description[$i] = str_replace('â€','',$value_description[$i]);
    $value_description[$i] = str_replace('¦','',$value_description[$i]);
	$value_description[$i] = str_replace(',','-',$value_description[$i]);
   $data.=$value_product[$i].",".$value_price[$i].",".$value_description[$i].",".$value_link[$i];
   $data.= "\n";
}
echo $data;
exit;
?>
