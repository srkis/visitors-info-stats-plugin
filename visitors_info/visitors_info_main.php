<?php
/*
Plugin Name:Visitor info statistics
Plugin URI: https://github.com/srkis
Description: Statistic for your website visitors. Get visitors data like Country, city, IP Address, longitude, latitude, visit date and time and more
Author: Srdjan Stojanovic
Version: 1.0
Author URI: http://www.srdjan.icodes.rocks/
*/

if (!defined('ABSPATH')) {
    exit;
}

include_once(ABSPATH . 'wp-includes/pluggable.php');
include_once(ABSPATH . 'wp-includes/user.php');
include_once dirname( __FILE__ ) . '/includes/admin_settings_page.php';

require_once( ABSPATH . 'wp-admin/includes/template.php' );

include_once( WP_PLUGIN_DIR . '/visitors_info/classes/Visitor_info_Installer.php' );
include_once( WP_PLUGIN_DIR . '/visitors_info/classes/Visitor_info_Data.php' );
include_once( WP_PLUGIN_DIR . '/visitors_info/classes/Visitor_info_Browser_Detection.php' );
include_once dirname( __FILE__ ) . '/includes/constants.php';

require_once(ABSPATH . 'wp-load.php');
$vi_data = new Visitor_info_Data();

function installer(){

  $vi_installer = new Visitor_info_Installer();
  $vi_installer->visitor_info_install();

}
register_activation_hook(__file__, 'installer');


$detectBrowser = new Browser_Detection();


$device = $detectBrowser->getInfo();

if($device['device'] == "Mobile") {
    header('Access-Control-Allow-Origin: *');
    add_action( 'wp_ajax_nopriv_add_visitors', 'addVisitorData' );

}


if(getUserBrowser() === 'Mozilla-Firefox') {
  add_action( 'wp_ajax_nopriv_add_visitors', 'addVisitorData' );
}else{

  add_action( 'wp_ajax_add_visitors', 'addVisitorData' );
}

add_action( 'wp_ajax_get_visitors', 'getVisitorData' );



function addVisitorData()
{

  if(isset($_POST['action']) && $_POST['action'] == 'add_visitors'){

    $vi_data = new Visitor_info_Data();
    $vi_data->vi_info_get_visitor_data_by_ip($_POST, null);
  }

}


 function getVisitorData()
{
  $vi_data = new Visitor_info_Data();
  $results = $vi_data->show_json_visitors_data();

  }

if(isset($_GET['page'])) {
  switch ($_GET['page']) {
    case 'visitors-info-stats-options':
      add_action('admin_enqueue_scripts', 'admin_js');
      break;
      case 'show_charts':
        add_action('admin_enqueue_scripts', 'chartsScripts');
        break;
  }

}


function chartsScripts()
{
  wp_register_script('chartsScripts', plugin_dir_url(__FILE__).'scripts/renderCharts.js',  array('jquery'));
  wp_enqueue_script('chartsScripts');

}

 function admin_js() {
     wp_register_script('admin_js', plugin_dir_url(__FILE__).'scripts/foradmin.js',  array('jquery'));
     wp_enqueue_script('admin_js');
 }

 function visitors_geolocation() {

     wp_register_script('visitors_geolocation', plugin_dir_url(__FILE__).'scripts/visitors_geolocation.js',  array('jquery'));
     wp_enqueue_script('visitors_geolocation');
 }


function getUserBrowser(){
      $fullUserBrowser = (!empty($_SERVER['HTTP_USER_AGENT'])?
      $_SERVER['HTTP_USER_AGENT']:getenv('HTTP_USER_AGENT'));
      $userBrowser = explode(')', $fullUserBrowser);
      $userBrowser = $userBrowser[count($userBrowser)-1];

      if((!$userBrowser || $userBrowser === '' || $userBrowser === ' ' || strpos($userBrowser, 'like Gecko') === 1) && strpos($fullUserBrowser, 'Windows') !== false){
        return 'Internet-Explorer';
      }else if((strpos($userBrowser, 'Edge/') !== false || strpos($userBrowser, 'Edg/') !== false) && strpos($fullUserBrowser, 'Windows') !== false){
        return 'Microsoft-Edge';
      }else if(strpos($userBrowser, 'Chrome/') === 1 || strpos($userBrowser, 'CriOS/') === 1){
        return 'Google-Chrome';
      }else if(strpos($userBrowser, 'Firefox/') !== false || strpos($userBrowser, 'FxiOS/') !== false){
        return 'Mozilla-Firefox';
      }else if(strpos($userBrowser, 'Safari/') !== false && strpos($fullUserBrowser, 'Mac') !== false){
        return 'Safari';
      }else if(strpos($userBrowser, 'OPR/') !== false && strpos($fullUserBrowser, 'Opera Mini') !== false){
        return 'Opera-Mini';
      }else if(strpos($userBrowser, 'OPR/') !== false){
        return 'Opera';
      }
      return false;
    }


add_action('wp_enqueue_scripts', 'visitors_geolocation');


?>
