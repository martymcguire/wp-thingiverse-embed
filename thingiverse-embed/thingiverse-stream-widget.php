<?php

require_once("lib/thingiverse_stream.php");

/**
 * ThingiverseStreamWidget Class
 */
class ThingiverseStreamWidget extends WP_Widget {
    /** constructor */
    function ThingiverseStreamWidget() {
        parent::WP_Widget( false, $name = 'Thingiverse Stream',
                          array(description => "Display Thingiverse.com streams") );
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
        $logo_url = WP_PLUGIN_URL . "/thingiverse-embed/thingiverse_logo.png";
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
                <div class="thingiverse-stream">
                  <?php 
                    $i = 0;
                    foreach ( $stream->things as $thing ) { ?>
                      <div class="tv-stream-thing">
                      <a href="<?php echo $thing->url ?>"><?php echo $thing->title ?><br /><img src="<?php echo $thing->main_image ?>" /></a>
                      </div>
                     <?php $i++; if( $i >= $max_items ) { break; } 
                    }
                  ?>
                  <div class="thingiverse-props">
                  <a href="<?php echo $stream->url ?>">See&nbsp;more&nbsp;on&nbsp;<img src="<?php echo $logo_url ?>" /></a>
                  </div>
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
      return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {        
        $title = esc_attr($instance['title']);
        $type = esc_attr($instance['type']);
        $user = esc_attr($instance['user']);
        $max_items = esc_attr($instance['max_items']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Stream Type:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>" type="text" value="<?php echo $type; ?>" /></label><br />
            Global Streams: <em>newest, featured, popular, derivatives, instances</em><br />
            User Streams: <em>designed, likes, made</em><br />
            </p>
            <p><label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('User:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo $user; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('max_items'); ?>"><?php _e('Number of Things to show (<em>Default: 3</em>):'); ?> <input class="widefat" id="<?php echo $this->get_field_id('max_items'); ?>" name="<?php echo $this->get_field_name('max_items'); ?>" type="text" value="<?php echo $max_items; ?>" /></label></p>
        <?php 
    }

} // class ThingiverseStreamWidget
?>
