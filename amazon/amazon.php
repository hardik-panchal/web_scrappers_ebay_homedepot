<?php

define('DB_HOST', 'localhost');
define('DB_PASSWORD', 'root');
define('DB_UNAME', 'root');
define('DB_NAME', 'amazon');

mysql_connect(DB_HOST,DB_UNAME,DB_PASSWORD) or die('Database connectioon error');

mysql_select_db('amaz0n') or die('database not connect');

set_time_limit(0);
error_reporting(E_ALL && ~E_NOTICE);



defined('AWS_API_KEY') or define('AWS_API_KEY', 'AKIAJPRSADF');
defined('AWS_API_SECRET_KEY') or define('AWS_API_SECRET_KEY', 'ydFp7QjMh3Dasdf');
defined('AWS_ASSOCIATE_TAG') or define('AWS_ASSOCIATE_TAG', 'wwwparseamnet-21');


$no_1 = $_REQUEST['no'] + 1;
$no_2 = $_REQUEST['no'] + 10;


require 'AmazonECS.class.php';
$data = array();
$row = 1;
$allow = 1;
$main_row = 0;
if (($handle = fopen("10000_records.csv", "r")) !== FALSE && $allow == 1) {
    $nm = 0;
	
    while (($rec1 = fgetcsv($handle, 10000, ",")) !== FALSE) {
        $num = count($rec1);
        $row++;
        
        if ($row >= $no_1 && $row <= $no_2) { 	

		
		$data[$main_row][0]['Curacao_SKU'] = trim($rec1[0]);
		$data[$main_row][0]['Model'] = trim($rec1[1]);
		$data[$main_row][0]['UPC'] = trim($rec1[3]);
		$data[$main_row][0]['Retail_Price'] = trim($rec1[4]);
		   
            if (trim($rec1[2]) != '' && trim($rec1[2]) != 'name') {
                $product_name_csv = trim($rec1[2]);

                try {
                    $amazonEcs = new AmazonECS(AWS_API_KEY, AWS_API_SECRET_KEY, 'DE', AWS_ASSOCIATE_TAG);
                    $amazonEcs->associateTag(AWS_ASSOCIATE_TAG);


                    $page_start = 1;
                    $page_end = 10;


                    $i = 0;
                    $time = time();
                    for ($j = 1; $j <= 1; $j++) {

                    $page = $j;
                     
					 $response = $amazonEcs->country('com')->category("All")->responseGroup('Large')->page($page)->search($product_name_csv);
                      

                        if (count($response->Items->Item) > 1) {
                            $dp = 0;
                            foreach ($response->Items->Item as $en) {

                                if ($dp == 0) {
                                    
									$data[$main_row][$dp]['YourTitle'] = $product_name_csv;
                                    
									$data[$main_row][$dp]['ASIN'] = $en->ASIN;
                                    $data[$main_row][$dp]['DetailPageURL'] = $en->DetailPageURL;
                                    $data[$main_row][$dp]['price'] = trim(str_replace($en->ItemAttributes->ListPrice->CurrencyCode, "", $en->ItemAttributes->ListPrice->FormattedPrice));
                                    $data[$main_row][$dp]['price'] = str_replace(",", ".", $data[$dp]['price']);

                                    $data[$main_row][$dp]['title'] = $en->ItemAttributes->Title;
                                    $data[$main_row][$dp]['avail'] = $en->Offers->Offer->OfferListing->Availability;

                                    $data[$main_row][$dp]['offerPrice'] = str_replace(array("EUR ", ","), array("", "."), $en->Offers->Offer->OfferListing->Price->FormattedPrice);
                                    $data[$main_row][$dp]['offerValidType'] = $en->Offers->Offer->OfferListing->AvailabilityAttributes->AvailabilityType;
                                    $data[$main_row][$dp]['offerValidMin'] = $en->Offers->Offer->OfferListing->AvailabilityAttributes->MinimumHours;
                                    $data[$main_row][$dp]['offerValidMax'] = $en->Offers->Offer->OfferListing->AvailabilityAttributes->MaximumHours;
                                    if (isset($en->EditorialReviews->EditorialReview->Content) && $en->EditorialReviews->EditorialReview->Content != '') {
                                        $content_data = $en->EditorialReviews->EditorialReview->Content;
                                        $content_data = preg_replace("/\s+/", " ", $content_data);
                                    } else {
                                        $content_data = '-';
                                    }
                                    
                                    $data[$main_row][$dp]['detail'] = $content_data;

                                    if ($_REQUEST['result']) {
                                        echo "<pre>";
                                        print_r($data);
                                        "</pre>";
                                        die;
                                    }
                                    $i++;
                                }
                                $dp++;
                            }
                        } else {
                            $en = '';
                            $en1 = '';
                            $en1 = $response->Items->Item;
                            if (isset($response->Items->Item)) {
                                $ASIN = $en1->ASIN;
                                $DetailPageURL = $en1->DetailPageURL;
                                $price = trim(str_replace($en1->ItemAttributes->ListPrice->CurrencyCode, "", $en1->ItemAttributes->ListPrice->FormattedPrice));
                                $price = str_replace(",", ".",$price);
                                $title = $en1->ItemAttributes->Title;
                                $avail = $en1->Offers->Offer->OfferListing->Availability;
                                $offerPrice = str_replace(array("EUR ", ","), array("", "."), $en1->Offers->Offer->OfferListing->Price->FormattedPrice);
                                $offerValidType = $en1->Offers->Offer->OfferListing->AvailabilityAttributes->AvailabilityType;
                                $offerValidMin = $en1->Offers->Offer->OfferListing->AvailabilityAttributes->MinimumHours;
                                $offerValidMax = $en1->Offers->Offer->OfferListing->AvailabilityAttributes->MaximumHours;
                                if (isset($en1->EditorialReviews->EditorialReview->Content) && $en1->EditorialReviews->EditorialReview->Content != '') {
                                    $content_data = $en1->EditorialReviews->EditorialReview->Content;
                                    $content_data = preg_replace("/\s+/", " ", $content_data);
                                } else {
                                    $content_data = '-';
                                }
                                $detail = $content_data;
                            } else {
                                $ASIN = '-';
                                $DetailPageURL = '-';
                                $price = '-';
                                $title = '-';
                                $avail = '-';
                                $offerPrice = '-';
                                $offerValidType = '-';
                                $offerValidMin = '-';
                                $offerValidMax = '-';
                                $detail = '-';
                            }
                            
                            $data[$main_row][0]['YourTitle'] = $product_name_csv;
                            $data[$main_row][0]['ASIN'] = $ASIN;
                            $data[$main_row][0]['DetailPageURL'] = $DetailPageURL;
                            $data[$main_row][0]['price'] = $price;
                            $data[$main_row][0]['title'] = $title;
                            $data[$main_row][0]['avail'] = $avail;
                            $data[$main_row][0]['offerPrice'] = $offerPrice;
                            $data[$main_row][0]['offerValidType'] = $offerValidType;
                            $data[$main_row][0]['offerValidMin'] = $offerValidMin;
                            $data[$main_row][0]['offerValidMax'] = $offerValidMax;
                            $data[$main_row][0]['detail'] = $detail;
                        }
                    }
                    $end_time = time();
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                $main_row++;
            }
        }
    }
} else {
    echo "csv file not readable";
}
//echo $row;

if (isset($_REQUEST['print_data'])) { 
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die;
}


for ($cs = 0; $cs < $main_row; $cs++) {
   // if ($data[$cs][0]['ASIN'] != '') {
		
		$data[$cs][0]['Curacao_SKU'] = addslashes(trim(str_replace(',', '-', $data[$cs][0]['Curacao_SKU'])));
		$data[$cs][0]['Model'] = addslashes(trim(str_replace(',', '-', $data[$cs][0]['Model'])));
		$data[$cs][0]['UPC'] = addslashes(trim(str_replace(',', '-', $data[$cs][0]['UPC'])));
		$data[$cs][0]['Retail_Price'] = addslashes(trim(str_replace(',', '-', $data[$cs][0]['Retail_Price'])));
		
		
		$data[$cs][0]['YourTitle'] = addslashes(trim(str_replace(',', '-', $data[$cs][0]['YourTitle'])));
        $data[$cs][0]['title'] = addslashes(trim(str_replace(',', '-', $data[$cs][0]['title'])));
        $data[$cs][0]['avail'] = addslashes(trim(str_replace(',', '-', $data[$cs][0]['avail'])));
        $data[$cs][0]['detail'] = addslashes(trim(str_replace(',', '-', $data[$cs][0]['detail'])));
        $data[$cs][0]['detail'] = addslashes(trim(str_replace('\n', '', $data[$cs][0]['detail'])));
        $data[$cs][0]['detail'] = addslashes(trim(str_replace('\r', '', $data[$cs][0]['detail'])));
        $data[$cs][0]['detail'] = addslashes(trim(str_replace('<br/>', '', $data[$cs][0]['detail'])));
        $data[$cs][0]['detail'] = addslashes(trim(str_replace('<br>', '', $data[$cs][0]['detail'])));
        $data[$cs][0]['detail'] = addslashes(trim(trim($data[$cs][0]['detail'])));
        $data[$cs][0]['detail'] = addslashes(trim(nl2br($data[$cs][0]['detail'])));
        $data[$cs][0]['detail'] = addslashes(trim(strip_tags($data[$cs][0]['detail'])));
        $data[$cs][0]['detail'] = addslashes(trim(str_replace('  ', ' ', $data[$cs][0]['detail'])));

        if ($data[$cs][0]['ASIN'] == '') {
            $data[$cs][0]['ASIN'] = '-';
        }
        if ($data[$cs][0]['DetailPageURL'] == '') {
            $data[$cs][0]['DetailPageURL'] = '-';
        }
        if ($data[$cs][0]['price'] == '') {
            $data[$cs][0]['price'] = '-';
        }
        if ($data[$cs][0]['title'] == '' or $data[$cs][0]['title'] == '-') {
            $data[$cs][0]['title'] = '-';
			$data[$cs][0]['status_null'] = '0';
        } else {
		    $data[$cs][0]['status_null'] = '1';
		}
        if ($data[$cs][0]['avail'] == '') {
            $data[$cs][0]['avail'] = '-';
        }
        if ($data[$cs][0]['offerPrice'] == '') {
            $data[$cs][0]['offerPrice'] = '-';
        }
        if ($data[$cs][0]['offerValidType'] == '') {
            $data[$cs][0]['offerValidType'] = '-';
        }
        if ($data[$cs][0]['offerValidMin'] == '') {
            $data[$cs][0]['offerValidMin'] = '-';
        }
        if ($data[$cs][0]['offerValidMax'] == '') {
            $data[$cs][0]['offerValidMax'] = '-';
        }
        if ($data[$cs][0]['detail'] == '') {
            $data[$cs][0]['detail'] = '-';
        }
        
		
		$insert = "INSERT INTO 10000_records_1(YourTitle,ASIN,DetailPageURL,Price,Title,Avail,OfferPrice,OfferValidType,OfferValidMin,OfferValidMax,detail,Curacao_SKU,Model,UPC,Retail_Price,Status) 
	   values('".$data[$cs][0]['YourTitle']."',
	          '".$data[$cs][0]['ASIN']."',
			  '".$data[$cs][0]['DetailPageURL']."',
			  '".$data[$cs][0]['price']."',
			  '".$data[$cs][0]['title']."',
			  '".$data[$cs][0]['avail']."',
			  '".$data[$cs][0]['offerPrice']."',
			  '".$data[$cs][0]['offerValidType']."',
			  '".$data[$cs][0]['offerValidMin']."',
			  '".$data[$cs][0]['offerValidMax']."',
			  '".$data[$cs][0]['detail']."',
			  '".$data[$cs][0]['Curacao_SKU']."',
			  '".$data[$cs][0]['Model']."',
			  '".$data[$cs][0]['UPC']."',
			  '".$data[$cs][0]['Retail_Price']."',
			  '".$data[$cs][0]['status_null']."'
			  )";
		  
		mysql_query($insert);  
		echo $data[$cs][0]['YourTitle'];
		echo "<br/>";	  
		
    //}
}

?> 

<?php
echo '<script>window.location.href = "http://www.spread5.net/amazon/amazon.php?no='.$no_2.'";</script>';
exit;
?>
