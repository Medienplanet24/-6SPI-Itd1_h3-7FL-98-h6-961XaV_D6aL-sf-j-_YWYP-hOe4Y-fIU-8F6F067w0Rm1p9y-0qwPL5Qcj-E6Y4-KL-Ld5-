<?php
/**
 * @package liefermia_printer
 * @version 1.2.4
 */
/*
Plugin Name: liefermia printer 
Plugin URI: http://wordpress.org/
Description:  
Author: Masoud Goodarzi
Version: 1.2.4
Author URI: http://net1.ir/
*/
add_action( 'init', 'liefermia_printer_url_handler' );

function liefermia_printer_url_handler() {
     if( isset( $_GET['liefermia_printer_exe'] ) ) { 
     
     	$newfile= plugin_dir_path( __FILE__ ) ."/last.txt";
if (!file_exists($newfile)) {
   // echo "sfaf";
    $myfile = fopen($newfile, 'wb');
    fwrite($myfile, '1');
    fclose($myfile);
}
$myfile = fopen($newfile, "r") or die("Unable to open files!");
$m= fgets($myfile);
//var_dump($m);
fclose($myfile);
// Create connection
function mb_unserialize($string) {
    $string2 = preg_replace_callback(
        '!s:(\d+):"(.*?)";!s',
        function($m){
            $len = strlen($m[2]);
            $result = "s:$len:\"{$m[2]}\";";
            return $result;

        },
        $string);
    return unserialize($string2);
}    

  global $wpdb;

$sql = "SELECT * FROM `".$wpdb->prefix ."wppizza_orders` where id >$m and (`transaction_details` LIKE 'SUCCESS' or initiator like 'COD') order by id asc limit 0,1";  
//$sql = "SELECT * FROM `wp_wppizza_orders` where id >4 order by id asc limit 0,1";
//$result = $wpdb->query("SET NAMES 'UTF8'");
$result = $wpdb->get_results($sql,ARRAY_A );
//var_dump($wpdb->num_rows); die;
if ($wpdb->num_rows > 0) {
  // output data of each row
  foreach($result as $row ) {
  	echo($row['order_date'].PHP_EOL); 
  	$customer=mb_unserialize($row['customer_ini']);

  	$order=mb_unserialize($row['order_ini']);
//  	var_dump($customer);   
// 	var_dump($order);   
echo 'Bestell ID'.PHP_EOL;  
echo '╔══════════╗'.PHP_EOL;  
//echo '║               ║'.PHP_EOL;  
echo '║    		   '.$row['id'].'  		     ║'.PHP_EOL;  
//echo '║               ║'.PHP_EOL;  
echo '╚══════════╝'.PHP_EOL;  
echo str_replace('  ','',$row['customer_details']); 
echo PHP_EOL.'════════════════════'.PHP_EOL; 
foreach($order['items'] as $order_item){
	echo $order_item['quantity'].'Ꭓ'.$order_item['title'].' '.$order_item["price_label"].PHP_EOL; 
	foreach($order_item['extend_data'] ['addingredients']['multi'] as $add_items){
		
		foreach($add_items as $atems){
		foreach($atems as $items){
	echo '+'.$items['count'].'*'.$items['name'].' '; 
		
}
}
	
}
echo PHP_EOL;
}
echo '════════════════════'; 
echo 'Gesamt:'.PHP_EOL.$row['order_total'].'€'.PHP_EOL; 

echo '════════════════════'.PHP_EOL; 
echo 'Bezahlt durch:'.'Barzahlung bei Lieferung'; 
  	$myfiles = fopen($newfile, "w") or die("Unable to open filse!"); 
fwrite($myfiles, $row["id"]); 
fclose($myfiles);
die;
//    echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
  }
} else {
  echo "";
}

die;
          // process data here
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
