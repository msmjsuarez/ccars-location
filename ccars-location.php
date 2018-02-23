<?php
/*
Plugin Name:  Car Location Identifier
Plugin URI:   https://www.companyofcars.com/
Description:  This plugin is use to identify car dealer location based from post content.
Version:      1.0
Author:       MJ Suarez
Author URI:   https://github.com/msmjsuarez
License: GPLv2
*/


defined( 'ABSPATH' ) or die( 'Direct access to this file is prohibited!' );

define( 'PLUGIN_DIR', dirname(__FILE__).'/' );  

define( 'PLUGIN_URL', get_site_url().'/wp-content/plugins/ccars-location/');

add_shortcode("ccar_location", "ccar_location_func"); //called from /themes/companyofcars/partials/single-car/car-main.php



function ccar_location_func() {


    $post = get_post();
    $post_content = $post->post_content;

	if (stripos($post_content,'kelowna')) :
    	$dealer_location = "Kelowna";
	elseif (stripos($post_content,'vancouver')):
    	$dealer_location = "Vancouver";
	else:
	    $dealer_location = "";
	endif;


	echo "<div class='text-center'><span class='h3'>". $dealer_location ."</span></div>";
    
 
}

