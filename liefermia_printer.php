<?php
/**
 * @package liefermia_printer
 * @version 1.2.7
 */
/*
Plugin Name: liefermia printer 
Plugin URI: http://net1.ir/
Description:  
Author: Masoud Goodarzi
Version: 1.2.7
Author URI: http://net1.ir/
*/
add_action( 'init', 'liefermia_printer_url_handler' );

function liefermia_printer_url_handler()
{
    if( isset( $_GET['liefermia_printer_exe'] ) )
    { 
    	$upload_dir   = wp_upload_dir();
    	    	$newfile= $upload_dir['basedir']."/last_access_device_".$_GET['device_id'].".txt";
		global $wpdb;

		if (!file_exists($newfile))
		{
			$sqlid = "SELECT order_id FROM  `".$wpdb->prefix ."wppizza_orders_meta` ORDER BY `order_id` DESC LIMIT 1";
			$resultid = $wpdb->get_results($sqlid,ARRAY_A );
			foreach($resultid as $rowid )
			{
			   	$myfile = fopen($newfile, 'wb');
				fwrite($myfile, ''.$rowid['order_id'].'');
				fclose($myfile);
			}
		}
		$myfile = fopen($newfile, "r") or die("Unable to open files!");
		$m=fgets($myfile);
		fclose($myfile);
		
		// Create connection
		function mb_unserialize($string)
		{
			$string2 = preg_replace_callback
			(
				'!s:(\d+):"(.*?)";!s',
				function($m)
				{
				    $len = strlen($m[2]);
				    $result = "s:$len:\"{$m[2]}\";";
				    return $result;
				},
				$string
			);
			return unserialize($string2);
		}    
		
		global $wpdb;
		
		$sql = "SELECT * FROM `".$wpdb->prefix ."wppizza_orders` where id >$m and (`payment_status` LIKE 'COMPLETED') order by id asc limit 0,1";  
			//$sql = "SELECT * FROM `wp_wppizza_orders` where id =8 order by id asc limit 0,1";
			//$result = $wpdb->query("SET NAMES 'UTF8'");
			$result = $wpdb->get_results($sql,ARRAY_A );
			//var_dump($wpdb->num_rows); die;
			if ($wpdb->num_rows > 0) 
			{
			  // output data of each row
			  foreach($result as $row ) 
			  {
			  	$customer=mb_unserialize($row['customer_ini']);
			  	$order=mb_unserialize($row['order_ini']);
			  	 	echo get_bloginfo( 'name' ).PHP_EOL;
				echo '            BESTELLNUMMER'.PHP_EOL; 
				if($row['initiator']=='PAYPAL')
				{
					echo '           ╔════════════╗'.PHP_EOL;  
					echo '           '.$row['transaction_id'].PHP_EOL;
					echo '           ╚════════════╝'.PHP_EOL;
				}
				else
				{
					echo '            ╔══════════╗'.PHP_EOL;  
					echo '            '.$row['transaction_id'].PHP_EOL;
					echo '            ╚══════════╝'.PHP_EOL;	
				}
				echo PHP_EOL;
				echo 'KUNDENDATEN'.PHP_EOL; 
				echo 'Kunde: '.html_entity_decode($customer['cname']).PHP_EOL;
				echo PHP_EOL;
				echo 'ADRESSE: '.PHP_EOL;
				echo html_entity_decode($customer['wppizza-dbp-map-location']).PHP_EOL;
				echo PHP_EOL;
				echo 'Tel.: '.$customer['ctel'].PHP_EOL;
				if($customer['ccomments']!=NULL)
				{
					echo 'Info: '.html_entity_decode($customer['ccomments']).PHP_EOL;
				}
				echo '════════════════════'.PHP_EOL; 
				foreach($order['items'] as $order_item)
				{
					echo '- '.$order_item['quantity'].' x '.$order_item['title'].' '.$order_item["price_label"].PHP_EOL; 
					if ( count($order_item['extend_data']['addingredients']['multi']) > 0)
					{
						foreach($order_item['extend_data']['addingredients']['multi'] as $add_items)
						{
							foreach($add_items as $atems)
							{
								foreach($atems as $items)
								{
									echo '      + '.$items['count'].'x '.$items['name'].' '.PHP_EOL;
								}
							}	
						}
					}
				}
				echo '════════════════════'.PHP_EOL; 
				echo "GESAMTBETRAG: ".$row['order_total']."€".PHP_EOL; 
				echo '════════════════════'.PHP_EOL; 
				echo 'Bezahlart: '; 
				if($row['initiator']=='COD'){
					echo 'Barzahlung';
				}elseif($row['initiator']=='PAYPAL'){
					echo 'Paypal';
				}elseif($row['initiator']=='CCOD'){
					echo 'Kartenzahlung';
				}else{
					echo $row['initiator'];
				}
				echo PHP_EOL;
				echo 'Bestelltyp: '; 
				if($row['order_self_pickup']=='N'){
					echo 'Lieferung';
				}elseif($row['order_self_pickup']=='Y'){
					echo 'Abholung';
				}
				echo PHP_EOL;
				echo PHP_EOL;
				echo '   - DIES IST KEINE RECHNUNG - ';
				echo PHP_EOL;
				echo PHP_EOL;
				//$timestamp = $row['order_date'];
				//$datum = date("d.m.Y - H:i", $timestamp);
				echo $row['order_date'];
				
				 $myfiles = fopen($newfile, "w") or die("Unable to open files!"); 
				fwrite($myfiles, $row["id"]); 
				fclose($myfiles);
				die;
			}
		}
		die;
    }
}
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/Medienplanet24/-6SPI-Itd1_h3-7FL-98-h6-961XaV_D6aL-sf-j-_YWYP-hOe4Y-fIU-8F6F067w0Rm1p9y-0qwPL5Qcj-E6Y4-KL-Ld5-/',
	__FILE__,
	'unique-plugin-or-theme-slug2'
);

//Optional: If you're using a private repository, specify the access token like this:
$myUpdateChecker->setAuthentication('');

//Optional: Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

$myUpdateChecker->getVcsApi()->enableReleaseAssets();
