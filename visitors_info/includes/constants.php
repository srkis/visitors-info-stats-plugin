<?php

add_action('plugins_loaded', function(){
    if ( !defined('PARSER_URL_UPLOAD_PDF') ) {
        define ( 'PARSER_URL_UPLOAD_PDF', 'http://localhost:5000/pdf_parser');
    }

    if ( !defined('PARSER_URL_HOME') ) {
        define ( 'PARSER_URL_HOME', 'http://localhost:5000/');
    }
    if ( !defined('PLUGIN_DIR') ) {
        define ( 'PLUGIN_DIR', dirname(__FILE__).'/' );
    }
    if ( !defined('NOT_CRAWLER') ) {
        define ( 'NOT_CRAWLER', 'not_crawler' );
    }
}, 0); # <= 0 is the priority
