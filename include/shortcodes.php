<?php
/* ------------------------------------------------------------------------- *
 *  Basic Shortcodes
/* ------------------------------------------------------------------------- */

/* Tags
/* ------------------------------------ */
	function fast_monkey_tags_shortcode($atts,$content=NULL) {
		return the_widget( 'WP_Widget_Tag_Cloud', array('title' => 'Tags') );
	}
	add_shortcode('show-tags','fast_monkey_tags_shortcode');

/* Categories
/* ------------------------------------ */
	function fast_monkey_categories_shortcode($atts,$content=NULL) {
		return the_widget( 'WP_Widget_Categories', array('title' => 'Categories') );
	}
	add_shortcode('show-categories','fast_monkey_categories_shortcode');
