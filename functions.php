<?php

// Include Beans
require_once( get_template_directory() . '/lib/init.php' );

/* Helpers and utility functions */
require_once 'include/helpers.php';

// Remove Beans Default Styling
remove_theme_support( 'beans-default-styling' );


// Enqueue uikit assets
beans_add_smart_action( 'beans_uikit_enqueue_scripts', 'fast_monkey_enqueue_uikit_assets', 5 );

function fast_monkey_enqueue_uikit_assets() {

	// Enqueue uikit overwrite theme folder
	beans_uikit_enqueue_theme( 'fast-monkey', get_stylesheet_directory_uri() . '/assets/less/uikit' );

	// Add the theme style as a uikit fragment to have access to all the variables
	beans_compiler_add_fragment( 'uikit', get_stylesheet_directory_uri() . '/assets/less/style.less', 'less' );

}

//Setup Theme
beans_add_smart_action( 'init', 'fast_monkey_init' );

function fast_monkey_init() {

	// Remove page post type comment support
	remove_post_type_support( 'page', 'comments' );
	// Register additional menus, we already have a Primary menu registered
	register_nav_menu('footer-menu', __( 'Footer Menu', 'fast-monkey'));
	register_nav_menu('social-menu', __( 'Social Menu', 'bench'));
}

// Setup document fragements, markups and attributes
beans_add_smart_action( 'wp', 'fast_monkey_setup_document' );

function fast_monkey_setup_document() {

	// Header and Primary Menu	// Site Logo
	beans_remove_action( 'beans_site_title_tag' );
	//Add back site title after logo image
	beans_add_smart_action('beans_logo_image_after_markup', 'fast_monkey_site_title');

	//Navigation menu
	beans_remove_attribute( 'beans_primary_menu', 'class', 'uk-float-right' );
	beans_add_attribute( 'beans_primary_menu', 'class', 'uk-float-left' );

	// Remove Breadcrumb
	beans_remove_action( 'beans_breadcrumb' );

	// Navigation
	beans_add_attribute( 'beans_sub_menu_wrap', 'class', 'uk-dropdown-center' );
	beans_remove_attribute( 'beans_menu_item_child_indicator', 'class', 'uk-margin-small-left' );

	// Layout
	if(beans_get_layout( ) != 'c') {
		beans_remove_attribute( 'beans_primary', 'class', 'uk-width-medium-7-10' );
		beans_add_attribute( 'beans_primary', 'class', 'uk-width-large-7-10' );
		beans_remove_attribute( 'beans_sidebar_primary', 'class', 'uk-width-medium-3-10' );
		beans_add_attribute( 'beans_sidebar_primary', 'class', 'uk-width-large-3-10 uk-visible-large' );
 }

	// Post content
	beans_add_attribute( 'beans_post_content', 'class', 'uk-text-large' );

	// Post meta
	beans_remove_action( 'beans_post_meta_categories' );
	beans_remove_output( 'beans_post_meta_categories_prefix' );
	beans_remove_output( 'beans_post_meta_date_prefix' );
	beans_add_attribute( 'beans_post_meta_date', 'class', 'uk-text-muted' );

	// Post embed
	beans_add_attribute( 'beans_embed_oembed', 'class', 'tm-cover-article' );

	// Comment form
	beans_add_attribute( 'beans_comment_fields_inner_wrap', 'class', 'uk-grid-small' );

	if ( !is_user_logged_in() )
		beans_replace_attribute( 'beans_comment_form_comment', 'class', 'uk-width-medium-1-1', 'uk-width-medium-6-10' );
  else {
		//Add edit post link when user is logged in
		if( is_singular() )
		beans_add_smart_action('beans_post_header_before_markup', 'fast_monkey_edit_link');
	}

	// Only applies to singular and not pages
 	if ( is_singular() && !is_page() ) {
 		//remove featured image
 		beans_remove_action( 'beans_post_image' );
 		// Post title
 		beans_add_attribute( 'beans_post_title', 'class', 'uk-margin-bottom' );
		//Widget area after post content
		beans_add_smart_action( 'the_content', 'fast_monkey_widget_after_post_content' );
 		// Post author profile
 		add_action( 'beans_comments_before_markup', 'fast_monkey_author_profile' );
 	}

	// Search
	if ( is_search() )
		beans_remove_action( 'beans_post_image' );

}


function fast_monkey_site_title() {
	echo beans_output( 'beans_site_title_text', get_bloginfo( 'name' ) );
}

function fast_monkey_edit_link() {
		edit_post_link( __( 'Edit', 'fast-monkey' ), '<div class="uk-margin-bottom-small uk-text-small uk-align-right"><i class="uk-icon-pencil-square-o"></i> ', '</div>' );
}

// Author profile in posts
function fast_monkey_author_profile() {
	echo '<h3>'.__('About the Author', 'fast-monkey').'</h3>';
	echo beans_open_markup( 'fast_monkey_author_profile', 'div',  array( 'class' => 'uk-panel uk-panel-box' ) );
  echo '<div class="uk-clearfix">';
	  echo '<div class="uk-align-left">'.get_avatar( get_the_author_meta('ID'), 104 ).'</div>';
   	echo '<div class="uk-text-large uk-text-bold">'.get_the_author_meta('display_name').'</div>';
		echo wpautop(get_the_author_meta('description'));
	echo '</div>';
	echo beans_close_markup( 'fast_monkey_author_profile', 'div' );
}

// Modify beans layout (filter)
beans_add_smart_action( 'beans_layout_grid_settings', 'fast_monkey_layout_grid_settings' );

function fast_monkey_layout_grid_settings( $layouts ) {

	return array_merge( $layouts, array(
		'grid' => 10,
		'sidebar_primary' => 3,
		'sidebar_secondary' => 3,
	) );

}

// Add search in header after primary menu
beans_add_smart_action( 'beans_primary_menu_after_markup', 'fast_monkey_primary_menu_search' );
function fast_monkey_primary_menu_search() {
	echo beans_open_markup( 'fast_monkey_menu_primary_search', 'div', array(
		'class' => 'tm-search uk-navbar-flip uk-hidden-small'
	) );
		get_search_form();
	echo beans_close_markup( 'fast_monkey_menu_primary_search', 'div' );
}

// Modify beans post meta items (filter)
beans_add_smart_action( 'beans_post_meta_items', 'fast_monkey_post_meta_items' );

function fast_monkey_post_meta_items( $items ) {

	// Remove author meta
	unset( $items['author'] );
	unset( $items['comments']);

	// Add categories meta
	$items['categories'] = 20;

	return $items;

}

// Remove comment after note (filter)
beans_add_smart_action( 'comment_form_defaults', 'fast_monkey_comment_form_defaults' );

function fast_monkey_comment_form_defaults( $args ) {

	$args['comment_notes_after'] = '';

	return $args;

}


// Add avatar uikit circle class (filter)
beans_add_smart_action( 'get_avatar', 'fast_monkey_avatar' );

function fast_monkey_avatar( $output ) {

	return str_replace( "class='avatar", "class='avatar uk-border-circle", $output ) ;

}

// Register a widget area below post content.
add_action( 'widgets_init', 'fast_monkey_below_post_widget_area' );

function fast_monkey_below_post_widget_area() {

    beans_register_widget_area( array(
        'name' => 'Below Post',
        'id' => 'below-post',
        'beans_type' => 'stack'
    ) );
}

//Display the Widget area
function fast_monkey_widget_after_post_content( $content ) {
	$output =  $content;
	$output .=  '<div class="tm-below-post-widget-area">';
	$output .=   beans_widget_area( 'below-post' );
	$output .=  '</div>';
	return $output;
}

// Add the footer menu
beans_add_smart_action( 'beans_footer_credit_before_markup', 'fast_monkey_footer_menu' );

function fast_monkey_footer_menu() {

	wp_nav_menu( array( 'theme_location' => 'footer-menu',
											'container' => 'nav',
	 										'container_class' => 'tm-footer-menu uk-navbar',
											'menu_class' => 'uk-navbar-nav uk-text-small'
										));

}

// Add footer content (filter)
beans_add_smart_action( 'beans_footer_credit_right_text_output', 'fast_monkey_footer' );

function fast_monkey_footer() { ?>

  <a href="http://themes.kanishkkunal.in/fast-monkey/" target="_blank" title="Fast Monkey theme for WordPress">Fast Monkey</a> theme for <a href="http://wordpress.org" target="_blank">WordPress</a>. Built-with <a href="http://www.getbeans.io/" title="Beans Framework for WordPress" target="_blank">Beans</a>.

<?php }

//Setup Widgets
beans_add_smart_action( 'widgets_init', 'fast_monkey_register_widgets');

function fast_monkey_register_widgets() {
			//Include widget classes
	 		require_once('widgets/posts.php');
	 		require_once('widgets/social.php');
	 		require_once('widgets/ads.php');

	 		// Regidter widgets
			register_widget('FastMonkey_Posts_Widget');
			register_widget( 'FastMonkey_SocialWidget' );
			register_widget('FastMonkey_Ads_Widget');
}


//Customizer fields

//Additional Header & Footer Codes (for Google Analytics)
add_action( 'init', 'fast_monkey_customization_fields' );
function fast_monkey_customization_fields() {

	$fields = array(
		array(
			'id' => 'fast_monkey_head_code',
			'label' => __( 'Additional Head Code', 'fast-monkey' ),
			'type' => 'textarea',
			'default' => ''
		)
	);

	beans_register_wp_customize_options( $fields, 'fast_monkey_custom_code', array( 'title' => __( 'Custom Code', 'fast-monkey' ), 'priority' => 1100 ) );
}

add_action('beans_head_append_markup', 'fast_monkey_custom_head_code');

function fast_monkey_custom_head_code() {
	echo get_theme_mod( 'fast_monkey_head_code', '' );
}

/* Shortcodes */
require 'include/shortcodes.php';
