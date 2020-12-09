<?php
/**
 * @package liefermia_printer
 * @version 1.1.3
 */
/*
Plugin Name: liefermia printer 
Plugin URI: http://wordpress.org/
Description:  
Author: Masoud Goodarzi
Version: 1.1.3
Author URI: http://net1.ir/
*/
add_action( 'init', 'liefermia_printer_url_handler' );

function liefermia_printer_url_handler() {
     if( isset( $_GET['liefermia_printer_exe'] ) ) {
     	include('../../../wp-config.php');
     	$newfile="last.txt";
if (file_exists($newfile)) {
   // $myfile = fopen($newfile, 'a');
//    fwrite($fh, 'd');
} else {
    echo "sfaf";
    $myfile = fopen($newfile, 'wb');
    fwrite($fh, '1');
    fclose($myfile);
}
$myfile = fopen("last.txt", "r") or die("Unable to open files!");
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
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//die;
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM `wp_wppizza_orders` where id >$m order by id asc limit 0,1";  
//$sql = "SELECT * FROM `wp_wppizza_orders` where id >4 order by id asc limit 0,1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
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
  	$myfiles = fopen("last.txt", "w") or die("Unable to open filse!"); 
fwrite($myfiles, $row["id"]); 
fclose($myfiles);
//    echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
  }
} else {
  echo "";
}
$conn->close();
die;
          // process data here
     }
}

require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://updateshop.liefermia.de/wp-plugins/plugin-printer.json',
	__FILE__, //Full path to the main plugin file or functions.php.
	'unique-plugin-or-theme-slug2'
);
