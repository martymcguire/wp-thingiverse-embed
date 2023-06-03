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
  public $creator_url = "https://www.thingiverse.com/";
  public $creator_img;
  public $images = array();
  public $main_image;
  public $description;
  public $instructions;
  public $downloads = array();
  public $like_count;
  public $create_date;

  function __construct( $thing_url = "" ) {
    if( $thing_url != null ) {
      // copy from the cache if it exists
      $thing_id = substr($thing_url, strrpos($thing_url, ':') + 1);
      $thing_cache_id = "thingiverse-embed-thing-$thing_id";
      $cached_thing = get_transient($thing_cache_id);
      if(false === $cached_thing){
        $this->url = $thing_url;       
        $authorization_header = 'Authorization: Bearer '.$this->get_authorization_token();
        $options  = ['http' => ['header' => $authorization_header]];
	$context  = stream_context_create($options);
	$json = file_get_contents('https://api.thingiverse.com/things/'.$thing_id, false, $context);
	$obj = json_decode($json);
	$this->initialize_from_json($obj);
	
        // cache for 1 hour
        set_transient($thing_cache_id, $this, 3600);
      } else {
        foreach(get_object_vars($cached_thing) as $prop => $value){
          $this->$prop = $value;
        }
      }
    }
  }
  
  function get_authorization_token(){
  	$cached_token = get_transient('thingiverse_authorization_token');
  	if(false === $cached_token){
  		$js = file_get_contents('https://cdn.thingiverse.com/site/js/app.bundle.js');
  		preg_match_all('/,x="\w+/', $js, $matches);
		$text = $matches[0];
		$token = substr($text[0],strrpos($text[0], 'x=')+3);
  		error_log(print_r($token, TRUE)); 
  		// cache 1 day
        	set_transient('thingiverse_authorization_token', $token, 86400);
        	return $token;
  	}
  	else{
  		return $cached_token;
  	}
  }
  
  function initialize_from_json($obj) {

  	$this->title 		= $obj-> name;
  	$this->creator_url 	= $obj-> creator -> public_url;
  	$this->creator		= $obj-> creator -> name;
  	$this->creator_img	= $obj-> creator -> thumbnail;
  	$this->main_image 	= $obj-> thumbnail;
  	$this->description	= $obj-> description;
  	$this->instructions	= $obj-> instructions;
  	$this->like_count	= $obj-> like_count;
  	$this->create_date	= $obj-> added;
  	
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
    $desc_elem = $dxp->query("//div")->item(1);
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

  private static function nodeContent ( $n, $outer = true ) {
      if( $n == null ) { return null; }
      $d = new DOMDocument('1.0');
      $b = $d->importNode($n->cloneNode(true),true);
      $d->appendChild($b); $h = $d->saveHTML();
      // remove outer tags
      if (!$outer) $h = substr($h,strpos($h,'>')+1,-(strlen($n->nodeName)+4));
      return $h . "\n";
  } 

  // From http://php.net/manual/en/function.time.php
  private static function _ago($tm,$rcs = 0) {
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
