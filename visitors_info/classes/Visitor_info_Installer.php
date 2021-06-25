<?php
Class Visitor_info_Installer {

  function __construct()
  {

    $this->visitor_info_install();
  }

  public function visitor_info_install()
  {

    global $wpdb;
    $table_name = $wpdb->prefix . "visitors_info";
    $visitor_info_db_version = '1.0.0';
    $charset_collate = $wpdb->get_charset_collate();

    if ( $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name ) {


       $sql = "CREATE TABLE $table_name (
    		ID mediumint(9) NOT NULL AUTO_INCREMENT,
    		`country_name` varchar(100),
      	`country_code` varchar(100),
    		`city` varchar(100),
        `place` varchar(100),
    		`ipaddress` varchar(255),
    		`location` varchar(100),
        `referer_page` varchar(255),
        `formatted_address` text,
    		`dateTime` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      	`device` varchar(100),
    		PRIMARY KEY  (ID)
    	) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        add_option('visitor_info_db_version', $visitor_info_db_version);

  }

  }
}
   ?>
