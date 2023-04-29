<div class="thingiverse-thing-card">
	<div class="thingiverse-thing-card-header">
		<a class="thingiverse-thing-card-header-avatar" href="<?php echo $thing->creator_url ?>">
			<img src="<?php echo $thing->creator_img ?>" alt="Avatar" loading="lazy">
		</a>
		<a class="thingiverse-thing-card-header-title" href="<?php echo $thing->url ?>">
			<span class="thingiverse-thing-card-header-title-text" title="<?php echo $thing->title ?>"><?php echo $thing->title ?></span>
		</a>
		<div class="thingiverse-thing-card-header-likes">
			<a target="_blank" rel="noopener noreferrer">
				<img src="https://www.thingiverse.com/assets/inline-icons/895d411e8e0d002ae23f.svg" alt="Like" class="CardActionItem__icon--Z_QnU">
				<div class="thingiverse-thing-card-header-likes-text"><?php echo $thing->like_count; ?></div>
			</a>
		</div>
	</div>
	<div>
		<div class="thingiverse-thing-card-body-left">
			<a href="<?php echo $thing->url ?>">
				<img src="<?php echo $thing->main_image ?>" alt="Main Image" loading="lazy">
			</a>
		</div>
		<div class="thingiverse-thing-card-body-right">
			<span class="thingiverse-thing-card-body-title">By <a href="<?php echo $thing->creator_url ?>"><?php echo $thing->creator; ?></a> <?php echo date("F d, Y", strtotime($thing->create_date)); ?></span>
			<span class="thingiverse-thing-card-body-text"><?php echo $thing->description ?></span>
		</div>
	</div>
</div>
