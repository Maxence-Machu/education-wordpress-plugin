<?php
/*
  Plugin Name: Basic Shortcodes
  Description: Plugin fournissant des shortcodes
  Author: maxence_mch
  Version: 1.0.0
 */

function shortcode_spacemeteo($atts){
    extract(shortcode_atts(
      array (
        'planet' => 'Mars'
      ),$atts));
  

    switch($planet){
      case "Mars":
        $temperature = '-40°C';
        break;
      case "Earth":
        $temperature = '15°C';
        break;
    }
  
    return "<h1> Il faut aujourd'hui " . $temperature . " sur " . $planet . "👨‍🚀</h2>" ; 
}

add_shortcode('spacemeteo', 'shortcode_spacemeteo');


