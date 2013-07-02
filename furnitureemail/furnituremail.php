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

//$keyword = trim($_REQUEST['keyword']);


$row = 0;
if (($handle = fopen("furniture_products.csv", "r")) !== FALSE) {

    $nm = 0;
    while (($rec1 = fgetcsv($handle, 1000, ",")) !== FALSE) {
	$row++;
	 if($row > 2100 && $row < 2151) {
         if($rec1[4] != '' && $rec1[4] != 'name' && $rec1[4] != 'Product Name'){
		     $rec1[4] = trim($rec1[4]);
			 $product_name = str_replace(' ','-',$rec1[4]);
			 $product_name = str_replace('|','',$product_name);
			 echo $product_name;	
	         echo "<br/>";
			 
			  
			    $url = "http://www.homefurnitureshowroom.com/search/".$product_name.".html";
				//$data = file_get_contents($url) or die("problem");
				$data = file_get_contents($url);
				if($data != ''){
				$pq = phpQuery::newDocumentHTML($data, $charset = 'utf-8');
				
				$result = pq(".catItem");
				
				if(count($result)>0){
					$i=0;
				   $result_1 = pq(".catItem a");
				   foreach($result_1 as $er){
					 if($i < 1){
					  $value_r = '';
					  $value_r = pq($er)->attr('title');
					  $i++;
					 }
				   }
				   
				   $result_2 = pq(".catItem .fntlb");
				   $j=0;
				   foreach($result_2 as $epr){
					 if($j < 1){
					  $price ='';
					  $price = pq($epr)->html();
					  $value_p = '';
					  $value_p = str_replace('From ','',$price); 
					  $j++;
					 }
				   }
				if($value_r != '' && $value_p != ''){
				   $insert = "insert into product(your_title,name,price) values('".$product_name."','".$value_r."','".$value_p."')";
				   mysql_query($insert);
				}   
				} 
		   }
		 }
      }		 
	}
}


?>
