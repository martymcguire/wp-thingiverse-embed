<div class="thingiverse-thing" id="thing-<?php echo $thing_id ?>">
<div class="thingiverse-thing-data">
<div class="thing-left">
<div class="thing-title"><a href="<?php echo $thing->url ?>"><?php echo $thing->title ?></a> by <a href="<?php echo $thing->creator_url ?>"><?php echo $thing->creator ?></a></div>
<a href="<?php echo $thing->url ?>"><img class="thing-image" src="<?php echo $thing->main_image ?>" /></a>
</div>
<div class="thing-description">
<?php echo strip_tags($thing->description); ?>
</div>
</div>
<div class="thingiverse-thing-meta">
<a href="<?php echo $thing->url ?>">This thing</a> brought to you by <a href="http://www.thingiverse.com/"><img src="<?php echo WP_PLUGIN_URL . "/thingiverse-embed/thingiverse_logo.png" ?>" alt="Thingiverse.com" title="Thingiverse.com" /></a>
</div>
</div>
