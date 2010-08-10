<div class="thingiverse-thing" id="thing-<?= $thing_id ?>">
<div class="thingiverse-thing-data">
<div class="thing-left">
<div class="thing-title"><a href="<?= $thing->url ?>"><?= $thing->title ?></a> by <a href="<?= $thing->creator_url ?>"><?= $thing->creator ?></a></div>
<a href="<?= $thing->url ?>"><img class="thing-image" src="<?= $thing->main_image ?>" /></a>
</div>
<div class="thing-description">
<?= $thing->description ?>
</div>
</div>
<div class="thingiverse-thing-meta">
<a href="<?= $thing->url ?>">This thing</a> brought to you by <a href="http://www.thingiverse.com/"><img src="<?= WP_PLUGIN_URL . "/thingiverse-embed/thingiverse_logo.png" ?>" alt="Thingiverse.com" title="Thingiverse.com" /></a>
</div>
</div>
