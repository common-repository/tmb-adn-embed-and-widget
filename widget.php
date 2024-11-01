<?php

class TMB_ADN_Widget extends WP_Widget {
	function TMB_ADN_Widget() {
		$widget_ops = array(
			'classname' => 'tmb-adn-widget',
			'description' => __('A widget for displaying a users recent App.net posts or for specific hashtag') );
		$this->WP_Widget('tmb-adn-widget', __('TMB App.net widget'), $widget_ops);
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array(
			'title' => 'Posts on App.net',
			'selector' => '@thomasmb',
			'length' => '5'
		));
		
		$title = strip_tags($instance['title']);
		$selector = strip_tags($instance['selector']);
		$length = strip_tags($instance['length']);
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title'); ?>:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('selector'); ?>"><?php echo __('Posts for user or tag'); ?>:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('selector'); ?>" name="<?php echo $this->get_field_name('selector'); ?>" type="text" value="<?php echo $selector; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('length'); ?>"><?php echo __('Number of posts to display'); ?>:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('length'); ?>" name="<?php echo $this->get_field_name('length'); ?>" type="text" value="<?php echo $length; ?>" />
			</p>
		<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['selector'] = sanitize_text_field($new_instance['selector']);
		$instance['length'] = sanitize_text_field($new_instance['length']);
		
		return $instance;
	}

	function widget($args, $instance) {
		// outputs the content of the widget
		extract($args);
		
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$length = empty($instance['length']) ? ' ' : $instance['length'];
		$selector = empty($instance['selector']) ? false : $instance['selector'];
		
		if($selector == false)
			return;
			
		wp_register_script('tmb-adn-script', plugins_url( '/script.min.js', __FILE__ ), array( 'jquery' ));
		wp_print_scripts('tmb-adn-script');
		
		$is_user = $is_hashtag = false;
		
		if(substr($selector,0,1) == "@")
			$is_user = true;
		elseif(substr($selector,0,1) == "#"){
			$selector = substr($selector,1);
			$is_hashtag = true;
		}

		?>
		<?php echo $before_widget; ?>
			<?php if(!empty($title)) echo $before_title . $title . $after_title; ?>	
			<ul class="tmb-adn-widget-list" data-selector="<?php echo $selector; ?>" data-length="<?php echo $length; ?>" data-is-user="<?php echo $is_user ? "true" : "false"; ?>">
			</ul>
		<?php echo $after_widget; ?>
		<?php
		
		
	}

}

add_action('widgets_init', create_function('', 'return register_widget("TMB_ADN_Widget");'));

?>