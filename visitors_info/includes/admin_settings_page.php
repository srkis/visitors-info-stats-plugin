<?php

error_reporting(E_ALL);
ini_set("display_errors",1);

require_once( ABSPATH . 'wp-admin/includes/template.php' );
include_once( WP_PLUGIN_DIR . '/visitors_info/classes/Visitor_info_Render.php' );


class Visitor_info_stats
{

  public $html_render, $url;

  function __construct()
  {

    $this->url = home_url() . "/" . "wp-admin/admin-post.php";
    $this->html_render = new Visitor_info_render($this->url);
    $this->init_visitor_info_parser();
  }


public function init_visitor_info_parser()
{
  add_action('admin_menu', array($this, 'visitor_info_stats_init' ) );

}

public function visitor_info_stats_init(){

    add_menu_page('Visitors Info Stats', 'Visitors Info Stats', 'manage_options', 'visitors-info-stats-options',array($this, 'visitors_info_settings_page') );

    add_submenu_page(
      'visitors-info-stats-options',
      'Show Charts', //page title
      'Show Charts', //menu title
      'edit_themes', //capability,
      'show_charts',//menu slug
      array($this, 'show_charts')
  );

    add_submenu_page(
      'visitors-info-stats-options',
      'Request APIKEY', //page title
      'Request APIKEY', //menu title
      'edit_themes', //capability,
      'request_apikey',//menu slug
      array($this, 'request_apikey')
    );

}

public function parser_gallery_settings() {
    //register our settings
    register_setting( 'yt-playlist-gallery-settings-group', 'background' );
    register_setting( 'yt-playlist-gallery-settings-group', 'width' );
    register_setting( 'yt-playlist-gallery-settings-group', 'height' );
    register_setting( 'yt-playlist-gallery-settings-group', 'yt_limit' );
}

  public function show_charts(){
    echo $this->html_render->renderChartsPage();
  }

  public function unlock_lock_pdf(){
    die("unlock_lock_pdf");
  }

  public function request_apikey(){
    die("request_apikey");
  }

  public function visitors_info_settings_page() {

    if( isset($_GET['success']) ) {
      $msg = $_GET['success'];
      echo

      '<div class="updated">
        <p>' . __($msg) . '</p>
      </div>';

    }

    if( isset($_GET['error']) ) {
      $msg = $_GET['error'];
      echo

      '<div class="error">
        <p>' . __($msg) . '</p>
      </div>';

    }

  echo $this->html_render->render_visitor_info_page();

  }

}

$Visitor_info_stats = new Visitor_info_stats();


?>
