<?php
/*
Plugin Name: Thingiverse Embed
Plugin URI: http://creatingwithcode.com/software/thingiverse-embed-wordpress/
Description: Adds a [thingiverse] shortcode to embed <a href="http://www.thingiverse.com/">Thingiverse</a> Things in a post or page, and a Thingiverse Stream widget to embed streams in sidebars.
Version: 0.2
Author: Marty McGuire
Author URI: http://www.creatingwithcode.com/

Copyright 2010 Marty McGuire

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once("lib/thingiverse.php");
require_once("lib/thingiverse_thing.php");
require_once("thingiverse-stream-widget.php");

// [thingiverse thing_id="thing-id-number"]
function thingiverse_shortcode_func($atts, $content = null) {
  extract(shortcode_atts(array(
    'thing' => 'none',
    'thing_id' => 'none'
  ), $atts));

  if( $thing_id == 'none' && $thing != 'none' ){ $thing_id = $thing; }
  $thing_url = Thingiverse::BASE_URL . "/thing:" . trim($thing_id);

  if($thing_id != 'none'){
    $thing = new ThingiverseThing($thing_url);
  } else {
    $thing = null;
  }

  if($thing != null && ($thing->creator_url != "http://www.thingiverse.com/")){
    ob_start();
    include("templates/thing.php");
    $html = ob_get_contents();
    ob_end_clean();
  } else {
	$html = "<pre>Error - could not find Thing {$thing_id}.</pre>";
  }

  // TODO: think of something cool to do with inline content
  if($content != null){
  }
  
  return $html;
}

function enqueue_thingiverse_styles() {
  $styleUrl = WP_PLUGIN_URL . '/thingiverse-embed/style.css';
  $styleFile = WP_PLUGIN_DIR . '/thingiverse-embed/style.css';
  if ( file_exists($styleFile) ) {
      wp_register_style('thingiverse_style', $styleUrl);
      wp_enqueue_style( 'thingiverse_style');
  }
}

add_shortcode('thingiverse', 'thingiverse_shortcode_func');
add_action('widgets_init', create_function('', 'return register_widget("ThingiverseStreamWidget");'));
add_action( 'wp_print_styles', 'enqueue_thingiverse_styles' );
?>
