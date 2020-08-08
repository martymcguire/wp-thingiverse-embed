<?php
require("lib/thingiverse_thing.php");
$thing = new ThingiverseThing("http://www.thingiverse.com/thing:782");
#$thing = new ThingiverseThing("testing/thing_1046.html");
//$thing = new ThingiverseThing("http://www.thingiverse.com/thing:1046");
?>
<!-- RENDER THING -->
<?php echo $thing->title . " by " . $thing->creator . " (" . $thing->creator_url . ")\n"; ?>
<?php echo $thing->creator_img ?>

<?php foreach( $thing->images as $img ) { ?>
<?php echo $img["href"] . " - " . $img["img"] . "\n" ?>
<?php } ?>

-------------
Description: 
-------------
<?php echo "$thing->description\n" ?>

-------------
Instructions
-------------
<?php echo "$thing->instructions\n" ?>

-------------
Downloads
-------------
<?php foreach ($thing->downloads as $dl) { ?>

<?php foreach ($dl as $k => $v) { ?>
    <?php echo $k . ": " . $v . "\n" ?>
<?php } ?>
<?php } ?>
-------------
Main Image: <?php echo $thing->main_image ?>

-------------
