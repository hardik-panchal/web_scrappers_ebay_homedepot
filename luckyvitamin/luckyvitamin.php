<?php

$url = "http://www.luckyvitamin.com/";
$data = file_get_contents($url) or die("problem");

$pq = phpQuery::newDocumentHTML($data, $charset = 'utf-8');

$product = pq("h4.product-name a");

$compare_match = 0;
$i=0; foreach($product as $ep){

    if($compare_match == 0){
	   $value_product_l[] = trim(pq($ep)->html());
	   $link_product_l[] = "http://www.luckyvitamin.com/".pq($ep)->attr('href');
	   
	   if(searchWords(trim(pq($ep)->html()),$search_word))
		{
			 $compare_match = 1;
			 $array_element = $i;
		}
	}
	$i=$i+1;
}

$price = pq(".product-price1");

$i=0;foreach($price as $epr){

    if($i==$array_element){
	   $value_price_l[] = trim(str_replace('Retail Price:','',pq($epr)->html()));
	}
  $i=$i+1;	
}


$url = $link_product_l[$array_element];
$data = file_get_contents($url) or die("problem");
echo $data;
die;
$pq = phpQuery::newDocumentHTML($data, $charset = 'utf-8');
echo "ff".$InStock = pq('#tabPricingContent td.product-stock')->html();
die;

$search[0]['competitors'][1]['seller'] = 'http://www.luckyvitamin.com/';
$search[0]['competitors'][1]['url'] = 'http://www.luckyvitamin.com/';
$search[0]['competitors'][1]['title'] = $value_product_l[$array_element];
$search[0]['competitors'][1]['price'] = $value_price_l[0];

?>
