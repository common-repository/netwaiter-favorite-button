<?php
/*
Plugin Name: NetWaiter Favorite Button
Plugin URI: http://www.netwaiter.net/
Description: A simple NetWaiter Favorite Button (+Favorite) for customers to easily click on your homepage.
Version: 1.0.0
Author: R. David Liebers
Author URI: http://www.netwaiter.net/
License: GPL2
*/

class wp_netwaiter_favorite extends WP_Widget {
	// constructor
	public function __construct() {
        parent::WP_Widget(false, $name = __('NetWaiter Favorite Button', 'wp_widget_plugin') );
	}

	// widget form creation
	function form($instance) {	
	    // Check values
        if( $instance) {
             $title = esc_attr($instance['title']);
             $url = esc_attr($instance['url']);
			 $errorMessage = esc_attr($instance['errorMessage']);
        } else {
             $title = 'Favorite Us On myNetWaiter';
             $url = '';
			 $errorMessage = NULL;
        }    
?>
<p>
<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'wp_widget_plugin'); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('NetWaiter Url:', 'wp_widget_plugin'); ?></label>
(<a href='https://www.netwaiter.com/search/' target='_blank'>Find Your NetWaiter Url Here</a>)
<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
</p>
<p>
<?php
	if (!is_null($errorMessage) && isset($errorMessage) && trim($errorMessage) != '') {
?>
<span style="color: red;">* <?php echo $errorMessage; ?></span>
<?php
	}
?>
</p>
<?php
	}

	// widget update
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
        // Fields
        $instance['title'] = strip_tags($new_instance['title']);
        $regex = '/(https?\\:\\/\\/)?([a-zA-Z\\-]*)\\.netwaiter\\.com[\\/\\\\]([a-zA-Z\\-]*).*/i';
        $url = strip_tags($new_instance['url']);
        if (preg_match($regex, $url, $matches)) {
            $instance['url'] = "https://".$matches[2].".netwaiter.com/".$matches[3];
			$instance['errorMessage'] = NULL;
        }
        else {
            $instance['url'] = '';
			$instance['errorMessage'] = 'Invalid NetWaiter Url, please use the full Url found in the above link.';
        }
        return $instance;
	}

	// widget display
	function widget($args, $instance) {
		extract( $args, EXTR_SKIP );
       // these are the widget options
       $title = apply_filters('widget_title', $instance['title']);
       $url = $instance['url'];
       if (is_null($url) || !isset($url) || trim($url) === '') {
            return;
       }

       echo $before_widget;
       // Display the widget
       echo '<div class="widget-text wp_widget_plugin_box">';

       // Check if title is set
       if ( $title ) {
          echo $before_title . $title . $after_title;
       }

       // Check if text is set
        echo '<iframe height="35" src="'.$url.'/favoritebutton" style="width: 100%;"></iframe>';
       echo '</div>';
       echo $after_widget;
	}
}
add_action('widgets_init', create_function('', 'return register_widget("wp_netwaiter_favorite");'));
?>
