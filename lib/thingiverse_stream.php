<?php

require_once("thingiverse.php");
require_once("thingiverse_thing.php");

class ThingiverseStream {
  /* Working Stream Types
   * Site-wide:
   *  * featured (/featured, /rss/featured)
   *  * newest (/newest, /rss/newest)
   *  * popular (/popular, /rss/popular)
   *  * derivatives (/derivatives, /rss/derivatives)
   *  * instances (/made-things, /rss/instances)
   * User-specific:
   *  * designed (/<User>/things, /rss/user:<id>)
   *  * likes (/<User>/favorites, /rss/user:<id>/likes)
   *  * made (/<User>/made, /rss/user:<id>/made)
   */
  public $title; // stream title
  public $url;
  public $user; // if any
  public $user_id;
  public $user_url;
  public $things = array();

  function __construct( $type = "newest", $user = null ) {
    $this->user = $user;
    $this->user_url = (is_null($user) ? null : Thingiverse::BASE_URL . "/user:$user");
    $method_name = "initialize_stream_$type";
    if(method_exists($this, $method_name)){
      call_user_func( array($this, $method_name) );
    } else {
      throw new Exception("Sorry, '$type' streams are not yet supported.");
    }
  }

  function initialize_stream_newest() {
    $this->url = Thingiverse::BASE_URL . "/rss/newest";
    $this->title = "Newest Things";
    $this->load_stream_from_rss_url();
  }

  function initialize_stream_featured() {
    $this->url = Thingiverse::BASE_URL . "/rss/featured";
    $this->title = "Featured Things";
    $this->load_stream_from_rss_url();
  }

  function initialize_stream_popular() {
    $this->url = Thingiverse::BASE_URL . "/rss/popular";
    $this->title = "Popular Things";
    $this->load_stream_from_rss_url();
  }

  function initialize_stream_derivatives() {
    $this->url = Thingiverse::BASE_URL . "/rss/derivatives";
    $this->title = "Newest Derivatives";
    $this->load_stream_from_rss_url();
  }

  // Newest instances has the thing creator, not the instance creator as author.
  // Fall back to HTML parsing.
  function initialize_stream_instances() {
    $this->url = Thingiverse::BASE_URL . "/made-things";
    $this->title = "Newest Instances";
    $this->load_stream_from_instances_url();
  }

  function initialize_stream_designed() {
//    $this->user_id = Thingiverse::user_id_from_name($this->user);
    $this->user_id = $this->user;
    $this->url = Thingiverse::BASE_URL . "/rss/user:$this->user_id";
    $this->title = "Newest Things";
    $this->load_stream_from_rss_url();
  }

  function initialize_stream_likes() {
//    $this->user_id = Thingiverse::user_id_from_name($this->user);
    $this->user_id = $this->user;
    $this->url = Thingiverse::BASE_URL . "/rss/user:$this->user_id/likes";
    $this->title = "Newest Likes";
    $this->load_stream_from_rss_url();
  }

  // Made things RSS has the thing creator, not the instance creator as author.
  // Also shows the original creator's picture. Fall back to HTML parsing.
  function initialize_stream_made() {
    $this->url = Thingiverse::BASE_URL . "/$this->user/made";
    $this->title = "Newest Instances";
    $this->load_stream_from_made_url();
  }

  // Returns a DOM object for the specified URL. Pulls it from the transient
  // cache if it is available, otherwise fetches it.
  function get_dom_for_url($url){
    $dom = new DomDocument("1.0");
    // cache key - chop off "http://www.thingiverse.com" and sluggify
    $t_key = "thingiverse-stream-" . sanitize_title(substr($url,27));
    $dom_str = get_transient($t_key);
    if(false === $dom_str){
      @$dom->load($url); // use @ to suppress parser warnings
      $xml_data = $dom->saveXML();
      set_transient($t_key, $xml_data, 3600);
    } else {
      @$dom->loadXML($dom_str); // use @ to suppress parser warnings
    }
    return $dom;
  }

  function load_stream_from_rss_url() {
    $dom = $this->get_dom_for_url($this->url);
    // FIXME: check for parse error. set some kind of thing status!
    $this->parse_things_from_rss_dom($dom);
  }

  function load_stream_from_instances_url() {
    $dom = $this->get_dom_for_url($this->url);
    // FIXME: check for parse error. set some kind of thing status!
    $this->parse_thing_instances_from_html_dom($dom);
  }

  function load_stream_from_made_url() {
    $dom = $this->get_dom_for_url($this->url);
    // FIXME: check for parse error. set some kind of thing status!
    $this->parse_thing_mades_from_html_dom($dom);
  }

  function parse_things_from_rss_dom($dom) {
    $xp = new DomXpath($dom);
    $thing_nodes = $xp->query("//item");
    foreach ($thing_nodes as $thing_node){
      $thing = ThingiverseThing::from_rss_item_dom($thing_node);
      array_push($this->things, $thing);
    }
  }

  function parse_thing_instances_from_html_dom($dom) {
    $xp = new DomXpath($dom);
    $thing_nodes = $xp->query("//div[@class=\"instance_float\"]");
    foreach ( $thing_nodes as $thing_node ) {
      $thing = ThingiverseThing::from_html_instance_dom($thing_node);
      array_push($this->things, $thing);
    }
  }

  function parse_thing_mades_from_html_dom($dom) {
    $xp = new DomXpath($dom);
    $thing_nodes = $xp->query("//div[@class=\"thing_float\"]");
    foreach ( $thing_nodes as $thing_node ) {
      $thing = ThingiverseThing::from_html_made_dom($thing_node);
      array_push($this->things, $thing);
    }
  }

  /* TODO: delete me if not needed
  private function nodeContent ( $n, $outer = true ) {
      $d = new DOMDocument('1.0');
      $b = $d->importNode($n->cloneNode(true),true);
      $d->appendChild($b); $h = $d->saveHTML();
      // remove outter tags
      if (!$outer) $h = substr($h,strpos($h,'>')+1,-(strlen($n->nodeName)+4));
      return $h . "\n";
  } 
  */
}
?>
