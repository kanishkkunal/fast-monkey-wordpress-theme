<?php
/*
	FastMonkey Social Profile Widget

	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html

	Copyright: (c) 2013 Kanishk Kunal - http://kanishkkunal.in

		@package fast-monkey
		@version 1.0
*/

class FastMonkey_SocialWidget extends WP_Widget {
	var $defaults;

/*  Constructor
/* ------------------------------------ */
	function __construct() {
		parent::__construct( false, 'FastMonkey Social Profile', array('description' => __('Displays your Social profile', 'fast-monkey'), 'classname' => 'widget_fast_monkey_social') );;
	}

/*  Widget
/* ------------------------------------ */
	public function widget($args, $instance) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $before_widget;

		$title = apply_filters( 'widget_title', $instance['title'] );

		if ( !empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
		?>
		<div class="tm-social-profile uk-panel uk-panel-box uk-text-center">
        <img class="uk-border-circle" width="120" height="120" src="<?php echo $instance['imgurl']; ?>" alt="<?php echo $instance['name']; ?>">
        <h3 class="tm-profile-name"><?php echo $instance['name']; ?></h3>
        <p class="tm-profile-desc uk-text-muted"><?php echo $instance['profile']; ?></p>
        <p class="tm-profile-loc uk-text-muted uk-text-small"><i class="uk-icon-map-marker"></i><?php echo $instance['location']; ?></p>
				<hr>
				<?php wp_nav_menu( array( 'theme_location' => 'social-menu',
												'container' => 'div',
		 										'container_class' => 'tm-social-menu',
												'menu_class' => '',
	                      'fallback_cb' => 'false'
											)); ?>
    </div>


		<?php
		echo $after_widget;
	}

/*  Widget update
/* ------------------------------------ */
	public function update($new,$old) {
		$instance = $old;
		$instance['title'] = esc_attr($new['title']);
		$instance['imgurl'] = $new['imgurl'];
		$instance['name'] = esc_attr($new['name']);
		$instance['profile'] = esc_attr($new['profile']);
		$instance['location'] = esc_attr($new['location']);
		return $instance;
	}

/*  Widget form
/* ------------------------------------ */
	public function form($instance) {
		// Default widget settings
		$defaults = array(
			'title' 			=> '',
			'imgurl' 			=> '//www.gravatar.com/avatar/?d=mm',
			'name'				=> 'Your Name',
			'profile'			=> 'Your Profile Description',
			'location'		=> 'Your Location'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
?>

	<div class="fast-monkey-options-social">
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
		</p>
			<p>
				<label for="<?php echo $this->get_field_id('imgurl'); ?>">Profile Image:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('imgurl'); ?>" name="<?php echo $this->get_field_name('imgurl'); ?>" type="text" value="<?php echo esc_attr($instance["imgurl"]); ?>" />
			</p>
		<p>
				<label for="<?php echo $this->get_field_id('name'); ?>">Name:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>" type="text" value="<?php echo esc_attr($instance["name"]); ?>" />
		</p>
		<p>
				<label for="<?php echo $this->get_field_id('profile'); ?>">Profile:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('profile'); ?>" name="<?php echo $this->get_field_name('profile'); ?>" type="text" value="<?php echo esc_attr($instance["profile"]); ?>" />
		</p>
		<p>
				<label for="<?php echo $this->get_field_id('location'); ?>">Location:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('location'); ?>" name="<?php echo $this->get_field_name('location'); ?>" type="text" value="<?php echo esc_attr($instance["location"]); ?>" />
		</p>
	</div>
<?php

}

}

function fast_monkey_add_nav_menu_atts( $atts, $item, $args ) {
	//check if icon class is applied to menu and apply equivalent uk-icon to nav menu link
	if(count($item->classes) >= 1) {
		if(substr($item->classes[0], 0, 5) === "icon-") {
			$atts['class'] = $atts['class'].' uk-'.$item->classes[0];
		}
	}
  return $atts;
}
add_filter( 'nav_menu_link_attributes', 'fast_monkey_add_nav_menu_atts', 10, 4);
