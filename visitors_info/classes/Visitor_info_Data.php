<?php

Class Visitor_info_Data {

public $visitor_ip, $table_name, $db, $apiKey;

public function __construct() {

  global $wpdb;
  $this->db =& $wpdb;

  $this->table_name = $wpdb->prefix . "visitors_info";
  $this->apikey = '';


}

public function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}


public function vi_info_get_visitor_data_by_ip($postRequest, $mobileRequest)
{

  var_dump("<pre>", $postRequest['results']);die("ovde");

    if($mobileRequest) {
        $this->addMobileData();
        return;
    }


  if(!isset($postRequest['action']) && $postRequest['action'] !== 'add_visitors') {
    return;
  }

if(!isset($postRequest['results'][0])){
   wp_die();
}
$lat = $postRequest['results'][0]['geometry']['location']['lat'];
$lng = $postRequest['results'][0]['geometry']['location']['lng'];

$referer_domain = null;

$referer_domain = isset($postRequest['referrer']) ? parse_url($postRequest['referrer'], PHP_URL_HOST) : null;


  $wp_admin = false;
  $cookieSet = 'not_set';

  if($this->isCrawlerDetected($_SERVER['HTTP_USER_AGENT']) === 'is_crawler' ) {
       echo "<script>window.location.replace('https://google.com')</script> ";
        exit();
  }

$is_ajax = 'xmlhttprequest' == strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '' );

if($is_ajax){
  $wp_admin = true;

}


if(!$this->isVisitorExists() && $this->isCrawlerDetected($_SERVER['HTTP_USER_AGENT']) === "not_crawler" ){

  //If not crawler, if not admin_ajax, and if cookie is not set

  $formatted_address = $postRequest['results'][0]['formatted_address'];
  $country_code = $postRequest['results'][0]['address_components'][0]['short_name'];
  $country_name = $postRequest['results'][0]['address_components'][0]['long_name'];
  $place = $postRequest['results'][0]['address_components'][1]['long_name'];
  $city = $postRequest['results'][0]['address_components'][2]['long_name'];
  $location = $lat.','.$lng;
  $dateTime = date('Y-m-d H:i:s');

   $this->db->insert(

    $this->table_name,
    array(
      'country_name' => $country_name,
      'country_code' => $country_code,
      'city' => $city,
      'ipaddress' => $this->visitor_ip,
      'location' => $location,
      'referer_page' => $referer_domain,
      'place' => $place,
      'dateTime' => $dateTime,
      'formatted_address' => $formatted_address
      )
    );


}

}


public function addMobileData()
{

         $country_code = $this->iptocountry($this->get_visitor_ip());
         $this->visitor_ip = $this->get_visitor_ip();


        if( $country_code !== "RS" ) {
            wp_die();
        }


  if(!$this->isVisitorExists() && $this->isCrawlerDetected($_SERVER['HTTP_USER_AGENT']) === "not_crawler" ){

     $PublicIP = $this->get_visitor_ip();

     $json  = file_get_contents("http://ipinfo.io/$PublicIP/geo");
	 $json  =  json_decode($json ,true);

if(!empty($json) && !is_null($json) && isset($json['country'])){

    	 $country =  $json['country'];
    	 $region= $json['region'];
    	 $city = $json['city'];
    	 $zip = $json['postal'];
    //	 $ipaddress = $json['ip'];
    	 $loc = $json['loc'];
    	 $dateTime = date('Y-m-d H:i:s');

        $this->db->insert(

        $this->table_name,
        array(
          'country_name' => $region,
          'country_code' => $country,
          'city' => $city,
          'ipaddress' => $this->visitor_ip,
          'location' => $loc,
          'referer_page' => 'Mobile',
          'place' => 'Mobile',
          'dateTime' => $dateTime,
          'formatted_address' => 'Mobile'
          )
        );
    }

    }

}


public function show_json_visitors_data()
{
  $query = $this->db->prepare( 'SHOW TABLES LIKE %s', $this->db->esc_like( $this->table_name ) );

  if ( $this->db->get_var( $query ) == $this->table_name ) {  // ako posotji tabela
    $results = $this->db->get_results("SELECT * FROM {$this->db->prefix}visitors_info ");

    header( "Content-Type: application/json" );
    echo json_encode($results);
    wp_die();

  }
}


public function get_visitor_ip()
{
  $ipaddress = '';
  if (isset($_SERVER['HTTP_CLIENT_IP']))
      $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
  else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
      $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
  else if(isset($_SERVER['HTTP_X_FORWARDED']))
      $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
  else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
      $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
  else if(isset($_SERVER['HTTP_FORWARDED']))
      $ipaddress = $_SERVER['HTTP_FORWARDED'];
  else if(isset($_SERVER['REMOTE_ADDR']))
      $ipaddress = $_SERVER['REMOTE_ADDR'];
  else
      $ipaddress = 'UNKNOWN';
  return $ipaddress;

}

//Detect is crawler or not.
public function isCrawlerDetected($USER_AGENT)
{
    $crawlers = array(
    array('Google', 'Google'),
    array('msnbot', 'MSN'),
    array('Rambler', 'Rambler'),
    array('Yahoo', 'Yahoo'),
    array('AbachoBOT', 'AbachoBOT'),
    array('accoona', 'Accoona'),
    array('AcoiRobot', 'AcoiRobot'),
    array('ASPSeek', 'ASPSeek'),
    array('CrocCrawler', 'CrocCrawler'),
    array('Dumbot', 'Dumbot'),
    array('FAST-WebCrawler', 'FAST-WebCrawler'),
    array('GeonaBot', 'GeonaBot'),
    array('Gigabot', 'Gigabot'),
    array('Lycos', 'Lycos spider'),
    array('MSRBOT', 'MSRBOT'),
    array('Scooter', 'Altavista robot'),
    array('AltaVista', 'Altavista robot'),
    array('IDBot', 'ID-Search Bot'),
    array('eStyle', 'eStyle Bot'),
    array('Scrubby', 'Scrubby robot')
    );

    foreach ($crawlers as $c)
    {
        if (stristr($USER_AGENT, $c[0]))
        {
            //return($c[1]);
            return "is_crawler";
        }
    }

    return "not_crawler";
}

public function isVisitorExists() {

  global $wpdb;
  $this->visitor_ip = $this->get_visitor_ip();
  $return = false;



$results = $this->db->get_results("SELECT * FROM {$this->db->prefix}visitors_info
 WHERE ipaddress = '$this->visitor_ip' "); //and DATE_ADD(dateTime, INTERVAL 7 DAY) <= CURDATE()


   if(is_array($results) && !empty($results)) {


     if(count($results) > 0 ) {

         foreach( $results as $obj ) {

              $date = strtotime($obj->dateTime);
              $date = strtotime("+7 day",$date);

               if(date('Y-m-d h:i:s',$date) <= date('Y-m-d h:i:s')) {
                   return false;

                }else{
                    return true;
                }
            }
       }
   }

   return false;


}



public function iptocountry($ip) {


    $numbers = preg_split( "/\./", $ip);

    include_once( WP_PLUGIN_DIR . "/visitors_info/includes/ip_files/".$numbers[0].".php" );

    $code = ($numbers[0] * 16777216) + ($numbers[1] * 65536) + ($numbers[2] * 256) + ($numbers[3]);

    foreach($ranges as $key => $value){

        if($key <= $code){
            if($ranges[$key][0] >= $code) {
             $country = $ranges[$key][1]; break;
         }
        }
    }
    return $country;
}



public function deleteCookie($cookieName)
{
   setcookie(".$cookieName.", "", time()-3600);
   return true;
}


}








 ?>
