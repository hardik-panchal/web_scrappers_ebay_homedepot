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

$no_1 = $_REQUEST['no'] + 1;
$no_2 = $_REQUEST['no'] + 5;

$row = 0;
if (($handle = fopen("data.csv", "r")) !== FALSE) {

    $nm = 0;
    while (($rec1 = fgetcsv($handle, 5000, ",")) !== FALSE) {
	$row++;
    	
	 if($row >= $no_1 && $row <= $no_2) {
         if($rec1[0] != '' && $rec1[0] != 'name' && $rec1[0] != 'Product Name'){
		     $rec1[0] = trim($rec1[0]);
			 //$product_name = str_replace(' ','-',$rec1[4]);
			 //$product_name = str_replace('|','',$product_name);
			 $product_name = $rec1[0];
			 $product_name = str_replace('DeWalt ','DeWalt+',$product_name);
			 $product_name = str_replace(' ','%20',$product_name);
			 $keyword = $product_name;
	
			  
			    //$url = "http://www.homedepot.com/webapp/catalog/servlet/Search?storeId=10051&langId=-1&catalogId=10053&keyword=".$keyword."&Ns=None&Ntpr=1&Ntpc=1&selectedCatgry=Search+All";
				$url ="http://www.homedepot.com/webapp/catalog/servlet/Search?storeId=10051&langId=-1&catalogId=10053&keyword=".$keyword."&Ns=None&Ntpr=1&Ntpc=1&selectedCatgry=Search+All";
				/*$url ="http://www.homedepot.com/webapp/catalog/servlet/Search?storeId=10051&langId=-1&catalogId=10053&keyword=DeWalt+DW2730%208-Piece%20Drill%20Drive%20Bit%20Set&Ns=None&Ntpr=1&Ntpc=1&selectedCatgry=Search+All";*/
				$data = file_get_contents($url) or die("problem");
				
				if($data != ''){
				$pq = phpQuery::newDocumentHTML($data, $charset = 'utf-8');
				
				$result = pq(".grid_12 .product_title");
				$title = '';
				$price = '';
				$description = '';
				if(count($result) > 0){
				   $title = pq($result[0])->html();
			       
				   $price1 = pq(".pricingReg .pReg");
				   $price = trim(pq($price1[0])->html()); 
				   
				   $desc1 = pq("#product_description .normal");
				   $desc = trim(pq($desc1[0])->html()); 
				   
				} else {
				  $result = pq(".spad .content_image a");
				  if(count($result) > 0){
				  $link = pq($result[0])->attr('href');
				  $url = $link;
				  $data = file_get_contents($url) or die("problem");
				  if($data != ''){
				  $pq = phpQuery::newDocumentHTML($data, $charset = 'utf-8');
				  $result = pq(".grid_12 .product_title");
				  
				  $title = pq($result[0])->html();
				  
				  $price1 = pq(".pricingReg .pReg");
				  $price = trim(pq($price1[0])->html());
				  
				  $desc1 = pq("#product_description .normal");
				  $desc = trim(pq($desc1[0])->html());
				  }
				  } else {
				    $title ='';
				  }
			    }
				
				if($title != ''){
				$title = str_replace('<span itemprop="name">','',$title);
				$title = str_replace('</span>','',$title);
				$title = trim(str_replace(array("\n", "\r", "\r\n", "\n\r"), ' ', $title));
				
				$desc = str_replace('<span itemprop="description">','',$desc);
				$desc = str_replace('</span>','',$desc);
				$desc = trim(str_replace(array("\n", "\r", "\r\n", "\n\r"), ' ', $desc));
				
				
				
				echo $your_title = addslashes(str_replace(',','',$rec1[0]));
				echo "<br/>";
				echo $title = addslashes(str_replace(',','',$title));
				echo "<br/>";
				echo $price = addslashes(str_replace(',','',$price));
				echo "<br/>";
				echo $desc = addslashes(str_replace(',','',$desc));
				
				$insert = "INSERT INTO homedepot(your_title,name,price,detail) values('".$your_title."','".$title."','".$price."','".$desc."')";
				mysql_query($insert); 
				
			  }	
		   }
		 }
      }		 
	}
}
?>
<?php
echo '<script>window.location.href = "http://localhost/python/homedepot/homedepot.php?no='.$no_2.'";</script>';
exit;
?>