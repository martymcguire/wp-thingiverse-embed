<?php
/*
Plugin Name: Thingiverse Shortcode
Plugin URI: ...
Description: Adds a [thingiverse] shortcode to embed info about objects and streams on <a href="http://www.thingiverse.com/">Thingiverse</a> in a post or page.
Version: 0.1
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


// [thingiverse thing="thing-id-number"]
function thingiverse_shortcode_func($atts, $content = null) {
  $thing_url = "http://www.thingiverse.com/thing:" . trim($thing);
  extract(shortcode_atts(array(
    'thing' => '1842'
  ), $atts));

  $html =  "<a href='" .$thing_url."'>Thing " . trim($thing) . "</a>";
 
  if($content != null){
    $html = $html."<noscript><code class=\"thingiverse_thing\"><pre>".$content."</pre></code></noscript>";
  }
  
  return $html;
  
}
add_shortcode('thingiverse', 'thingiverse_shortcode_func');

?>
