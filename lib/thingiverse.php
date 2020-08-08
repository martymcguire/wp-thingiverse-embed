<?php

class Thingiverse {
   
  const BASE_URL = "http://www.thingiverse.com";

  public static function user_id_from_name( $user ) {
    $url = Thingiverse::BASE_URL . "/$user";
    $dom = new DomDocument("1.0");
    @$dom->loadHTMLFile($url); // use @ to suppress parser warnings
    $xp = new DomXpath($dom);
    $rss_url = $xp->query("//link[@rel=\"alternate\"]")->item(0)->getAttribute("href");
    $parts = explode(":", $rss_url);
    return $parts[2];
  }

}
?>
