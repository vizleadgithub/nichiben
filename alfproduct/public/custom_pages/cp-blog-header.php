<?php
if ( !isset($wp_did_header) ) {
	$wp_did_header = true;
	require_once( dirname(__FILE__) . '/cp-load.php' );
	wp();
	require_once( ABSPATH . WPINC . '/template-loader.php' );
}

?>