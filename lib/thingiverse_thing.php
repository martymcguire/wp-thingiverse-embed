<?php

require_once("thingiverse.php");

class ThingiverseThing {
  /* TODO:
   *  - License
   *  - Other People's Copies
   *  - Variations
   *  - Ratings
   *  - Likes
   *  - Tags
   *  - QR Code
   *  - Part List
   *  - Required Tools
   */ 
  public $url;
  public $created_at;
  public $title = "Untitled";
  public $creator = "Unknown";
  public $creator_url;
  public $creator_img;
  public $images = array();
  public $main_image;
  public $description;
  public $instructions;
  public $downloads = array();

  function __construct( $thing_url = "" ) {
    if( $thing_url != null ) {
      $this->url = $thing_url;
      $dom = new DomDocument("1.0");
      // use @ to suppress parser warnings
      @$dom->loadHTMLfile($thing_url);
      $this->initialize_from_dom($dom);
    }
  }

  function initialize_from_dom($dom) {
    // get the interesting node
    $xp = new DomXpath($dom);

    // FIXME: check for parse error. set some kind of thing status!

    // Get thing $title, $creator, $creator_url
    $title_node = $xp->query("//h1[attribute::id=\"pageTitle\"]")->item(0);
    $this->title = str_replace(" by ", "", $title_node->childNodes->item(0)->wholeText);
    $creator_url_node = $title_node->childNodes->item(1);
    $this->creator_url = $creator_url_node->getAttribute("href");
    $this->creator = $creator_url_node->childNodes->item(0)->wholeText;

    // Get creator image
    $creator_img_node = $xp->query("//a[@href=\"" . $this->creator_url . "\"]/img[@class=\"render\"]")->item(0);
    $this->creator_img = ($creator_img_node == null) ?
                         null : $creator_img_node->getAttribute("src");

    // Get left sidebar images of the thing
    // Creates an array of assoc. arrays: href => "link to image", img => "image src"
    $image_nodes = $xp->query("//a[starts-with(@href, \"/image:\")][descendant::img[attribute::class=\"render\"]]");
    foreach ($image_nodes as $img_node){
      $img = array("href" => $img_node->getAttribute("href"),
                   "img"  => $img_node->childNodes->item(0)->getAttribute("src"));
      array_push($this->images, $img);
    }
    if ( count($this->images) > 0 ) {
      $this->main_image = $this->images[0]["img"];
    }

    // Get $description
    $this->description = trim($this->nodeContent($xp->query("//h2[text()=\"Description\"]/following-sibling::p")->item(0), false));

    // Get $instructions
    $this->instructions = trim($this->nodeContent($xp->query("//h2[text()=\"Instructions\"]/following-sibling::p")->item(0), false));

    // Get $downloads (array of assoc. arrays. name,img,url,size,count,render_error)
    $download_nodes = $xp->query("//h2[text()=\"Downloads\"]/following-sibling::table");
    for($i = 0; $i < $download_nodes->length; $i++){
      $dln = $download_nodes->item($i);
      $d = new DOMDocument('1.0');
      $b = $d->importNode($dln->cloneNode(true),true);
      $d->appendChild($b);
      $dlxp = new DomXpath($d);
      $size_count_str = $dlxp->query("//td")->item(1)->childNodes->item(2)->nodeValue;
      list($size, $count) = explode("/", $size_count_str);
      $err_div = ($dlxp->query("//div[@class=\"BaseError\"]")->length > 0);
      $dl = array( 
              "name" => trim($dlxp->query("//h3")->item(0)->nodeValue),
              "img" => $dlxp->query("//img[@class=\"render\"]/attribute::src")->item(0)->value,
              "url" => $dlxp->query("//a[starts-with(@href,\"/download\")]")->item(0)->getAttribute("href"),
              "size" => trim($size),
              "count" => trim($count),
              "render_error" => ($err_div ? "yes" : "no"),
            );
      array_push($this->downloads, $dl);
    }
  }

  public static function from_rss_item_dom( $dom ) {
    $thing = new ThingiverseThing();
    $d = new DOMDocument('1.0');
    $b = $d->importNode($dom->cloneNode(true),true);
    $d->appendChild($b);
    $xp = new DomXpath($d);
    $thing->title = $xp->query("//title/text()")->item(0)->wholeText;
    $thing->url = $xp->query("//link/text()")->item(0)->wholeText;
    $thing->creator = $xp->query("//author/text()")->item(0)->wholeText;
    $thing->creator_url = Thingiverse::BASE_URL . "/$thing->creator";

    // TODO: convert to some kind of timestamp object
    $thing->created_at = ThingiverseThing::_ago(strtotime($xp->query("//pubDate/text()")->item(0)->wholeText));

    // parse description, image out of <description> field
    $desc_html = ThingiverseThing::nodeContent($xp->query("//description")->item(0), false);
    $desc_dom = new DOMDocument('1.0');
    @$desc_dom->loadHTML($desc_html);
    $dxp = new DomXpath($desc_dom);
    $img_elem = $dxp->query("//img")->item(0);
    $thing->main_image = ($img_elem ? $img_elem->getAttribute("src") : null);
    $desc_elem = $dxp->query("//td")->item(1);
    $thing->description = trim(
       $desc_elem ? 
         ThingiverseThing::nodeContent($desc_elem, false) :
         null );
    return $thing;
  }

  public static function from_html_instance_dom( $dom ) {
    $thing = new ThingiverseThing();
    $d = new DOMDocument('1.0');
    $b = $d->importNode($dom->cloneNode(true),true);
    $d->appendChild($b);
    $xp = new DomXpath($d);

    $tmpelem = $xp->query("//div[@class=\"thing_name\"]/a")->item(0);
    $thing->title = $tmpelem->childNodes->item(0)->wholeText;

    $tmpelem = $xp->query("//div[@class=\"thing_info\"]/a")->item(0);
    $thing->creator = $tmpelem->childNodes->item(0)->wholeText;
    $thing->creator_url = Thingiverse::BASE_URL . "/$thing->creator";

    $tmpelem = $xp->query("//div[@class=\"thing_info\"]/span")->item(0);
    $thing->created_at = $tmpelem->childNodes->item(0)->wholeText;

    $tmpelem = $xp->query("//a")->item(0);
    $thing->url = $tmpelem->getAttribute("href");
    $thing->main_image = $tmpelem->childNodes->item(0)->getAttribute(src);
    return $thing;
  }

  public static function from_html_made_dom( $dom ) {
    $thing = new ThingiverseThing();
    $d = new DOMDocument('1.0');
    $b = $d->importNode($dom->cloneNode(true),true);
    $d->appendChild($b);
    $xp = new DomXpath($d);

    $tmpelem = $xp->query("//div[@class=\"thing_info\"]/a")->item(0);
    $thing->title = $tmpelem->childNodes->item(0)->wholeText;
    $tmpelem = $xp->query("//div[@class=\"thing_info\"]/a")->item(1);
    $thing->creator = $tmpelem->childNodes->item(0)->wholeText;
    $thing->creator_url = Thingiverse::BASE_URL . "/$thing->creator";

    // TODO: Get the time somehow :(
    //$thing->created_at = "Unknown"; 

    $tmpelem = $xp->query("//a")->item(2);
    $thing->url = Thingiverse::BASE_URL . $tmpelem->getAttribute("href");
    $thing->main_image = $tmpelem->childNodes->item(0)->getAttribute(src);
    return $thing;

  }

  private function nodeContent ( $n, $outer = true ) {
      $d = new DOMDocument('1.0');
      $b = $d->importNode($n->cloneNode(true),true);
      $d->appendChild($b); $h = $d->saveHTML();
      // remove outer tags
      if (!$outer) $h = substr($h,strpos($h,'>')+1,-(strlen($n->nodeName)+4));
      return $h . "\n";
  } 

  // From http://php.net/manual/en/function.time.php
  private function _ago($tm,$rcs = 0) {
    $cur_tm = time(); $dif = $cur_tm-$tm;
    $pds = array('second','minute','hour','day','week','month','year','decade');
    $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
    for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);
   
    $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
    if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
    return $x . "ago";
  }

}
?>
