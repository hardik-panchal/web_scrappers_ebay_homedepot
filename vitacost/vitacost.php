<?php
$url = "http://www.vitacost.com";
$data = file_get_contents($url) or die("problem");

$pq = phpQuery::newDocumentHTML($data, $charset = 'utf-8');

$product = pq(".pNameM");

$compare_match = 0;
$i=0; foreach($product as $ep){
    if($compare_match == 0){
	   $value_product[] = pq($ep)->html();
	   
	   if(searchWords(pq($ep)->html(),$search_word))
		{
			 $compare_match = 1;
			 $array_element = $i;
		}
	}
	$i=$i+1;
}


$price = pq(".pOurPriceM");

$i=0; foreach($price as $epr){
    if($i==$array_element){
	   $value_price[] = trim(str_replace('Vitacost price:','',pq($epr)->html()));
	}
  $i=$i+1;
}

$link = pq("a.pNameM");

$i=0; foreach($link as $lk){
    if($i==$array_element){
	   $value_link[] = "http://www.vitacost.com/".trim(str_replace('Vitacost price:','',pq($lk)->attr('href')));
	}
  $i=$i+1;
}


$url = $value_link[0];
$data = file_get_contents($url) or die("problem");
$pq = phpQuery::newDocumentHTML($data, $charset = 'utf-8');
$InStock = pq('.pBuyMsgLive')->html();
if($InStock == 'In stock'){
  $stock = 'true';
} else {
  $stock = 'false';
} 
$search[0]['competitors'][0]['seller']  = 'http://www.vitacost.com/';
$search[0]['competitors'][0]['url']     = 'http://www.vitacost.com/';
$search[0]['competitors'][0]['title']   = $value_product[$array_element];
$search[0]['competitors'][0]['price']   = $value_price[0];
$search[0]['competitors'][0]['instock'] = $stock;

?>
