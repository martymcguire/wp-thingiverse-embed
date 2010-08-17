<?php
require("lib/thingiverse_thing.php");
define("WP_PLUGIN_URL", "derp");
$thing_id = "782";
$thing = new ThingiverseThing("http://www.thingiverse.com/thing:782");

include("templates/thing.php");

trim(null);
?>
