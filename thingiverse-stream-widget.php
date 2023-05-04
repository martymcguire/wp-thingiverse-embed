<?php

require_once("lib/thingiverse_stream.php");

/**
 * ThingiverseStreamWidget Class
 */
class ThingiverseStreamWidget extends WP_Widget {
	
	protected $widget_slug = 'thingiverse-embed';

    /** constructor */
	public function __construct() {
		parent::__construct(
			$this->widget_slug, 'Thingiverse Stream', $this->widget_slug, array(
				'classname'   => $this->widget_slug . '-class',
				'description' => 'Display Thingiverse.com streams',
				$this->widget_slug
			)
		);
	}
	

    /** @see WP_Widget::widget */
    function widget($args, $instance) {   
        extract( $args );
        $title = apply_filters('widget_title', 
                               empty( $instance['title'] ) ? 
                                 __( 'Thingiverse' ) : 
                                 $instance['title']);
        $type = empty( $instance['type'] ) ? __( 'newest' ) : $instance['type'];
        $user = $instance['user'];
        $max_items = empty( $instance['max_items'] ) ? __( '3' ) : $instance['max_items'];
        $stream = new ThingiverseStream($type, $user);
        $logo_url = WP_PLUGIN_URL . "/thingiverse-embed/makerbot-thingiverse-logo.png";
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
                <div class="thingiverse-stream">
                  <?php 
                    $i = 0;
                    $numItems = count($stream->things);
                    foreach ( $stream->things as $thing ) { 
                    	$last_class="";
                    	$see_more="";
                    	$title="title=\"".$thing->title."\"";
                    	$thing_url=$thing->url;
                    	if( ($i+1 >= $max_items or $i+1 === $numItems) and $this->is_checked( $instance, 'show_see_more_at_last' )){
                    		$last_class="tv-stream-thing-last";
                    		$title="";
                    		$see_more="<span>See more on <img src=\"".$logo_url."\" /></span>";
                    		$thing_url=$stream->user_url;
                    	}
                    ?>
                      <div class="tv-stream-thing <?php echo $last_class ?>">
                      	<a href="<?php echo $thing_url ?>"><?php echo $see_more;?><img src="<?php echo $thing->main_image ?>" <?php echo $title;?>/></a>
                      </div>
                     <?php $i++; if( $i >= $max_items ) { break; } 
                    }
                  ?>
                </div>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {       
      $instance = $old_instance;
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['type'] = strip_tags($new_instance['type']);
      $instance['user'] = strip_tags($new_instance['user']);
      $instance['max_items'] = strip_tags($new_instance['max_items']);
      $instance['show_see_more_at_last'] = strip_tags($new_instance['show_see_more_at_last']);
      return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {        
        $title = esc_attr($instance['title']);
        $type = esc_attr($instance['type']);
        $user = esc_attr($instance['user']);
        $max_items = esc_attr($instance['max_items']);
        $show_see_more_at_last = esc_attr($instance['show_see_more_at_last']);
        
        ?>
            <p>
            	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> 
            		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            	</label>
            </p>
            <p>
            	<label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Stream Type:'); ?> 
            		<input class="widefat" id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>" type="text" value="<?php echo $type; ?>" />
            	</label>
            	<br />
		    Global Streams: <em>newest, featured, popular, derivatives, instances</em><br />
		    User Streams: <em>designed, likes, made</em><br />
            </p>
            <p>
            	<label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('User ID:'); ?> 
            		<input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo $user; ?>" />
            	</label>
            </p>
            <p>
            	<label for="<?php echo $this->get_field_id('max_items'); ?>"><?php _e('Number of Things to show (<em>Default: 3</em>):'); ?> 
            		<input class="widefat" id="<?php echo $this->get_field_id('max_items'); ?>" name="<?php echo $this->get_field_name('max_items'); ?>" type="text" value="<?php echo $max_items; ?>" />
            	</label>
            </p>
            <p>
		<h4>Show</h4>
		<input class="checkbox" type="checkbox" <?php checked( ${'show_see_more_at_last'}, 'on' ); ?>
		   id="<?php echo $this->get_field_id( 'show_see_more_at_last' ) ?>"
		   name="<?php echo $this->get_field_name( 'show_see_more_at_last' ) ?>"/>
		<label for="<?php echo $this->get_field_id( 'show_see_more_at_last' ) ?>">
			<?php echo ucfirst( str_replace( '_', ' ', ucfirst( 'show_see_more_at_last' ) ) ) ?>
		</label>
		<br/>
	    </p>
		
        <?php 
    }
    
	public function is_checked( $conf, $name ) {
		return isset( $conf[ $name ] ) && $conf[ $name ] == 'on';
	}

} // class ThingiverseStreamWidget
?>
