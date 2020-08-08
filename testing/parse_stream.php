<?php
require_once("thingiverse/lib/thingiverse_stream.php");
//$tvstrm = new ThingiverseStream("newest");
//$tvstrm = new ThingiverseStream("featured");
//$tvstrm = new ThingiverseStream("popular");
//$tvstrm = new ThingiverseStream("derivatives");
$tvstrm = new ThingiverseStream("instances");
//$tvstrm = new ThingiverseStream("designed", "Schmarty");
//$tvstrm = new ThingiverseStream("likes", "Schmarty");
//$tvstrm = new ThingiverseStream("made", "Schmarty");
?>
<!-- RENDER STREAM -->
<?php echo $tvstrm->title . (is_null($tvstrm->user) ? "" : " by " . $tvstrm->user . " (" . $tvstrm->user_url . ")"); ?>

<?php foreach( $tvstrm->things as $thing ) { ?>

  -------------------
  <?php echo $thing->title ?>

  <?php echo $thing->url ?>

  by <?php echo $thing->creator ?> (<?php echo $thing->creator_url ?>)
  at <?php echo $thing->created_at ?>

  -------------
  Description: 
  -------------
  <?php echo $thing->description ?>

  Main Image: <?php echo $thing->main_image ?>


<?php } ?>
