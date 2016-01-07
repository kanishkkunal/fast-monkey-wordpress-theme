<?php
// Setup fast-monkey
beans_add_smart_action( 'beans_before_load_document', 'fast_monkey_index_setup_document' );

function fast_monkey_index_setup_document() {

	// Post meta
	beans_remove_action( 'beans_post_meta_tags' );

	// Post more link
	beans_add_attribute( 'beans_post_more_link', 'class', 'uk-button uk-button-primary uk-button-small' );

}


/* Helpers and utility functions */
require_once 'include/helpers.php';

// Auto generate summary of Post content and read more button
beans_add_smart_action( 'the_content', 'fast_monkey_post_content' );

function fast_monkey_post_content( $content ) {

    $output = beans_open_markup( 'fast_monkey_post_content', 'p' );

    	$output .= beans_output( 'fast_monkey_post_content_summary', fast_monkey_get_excerpt( $content ) );

   	$output .= beans_close_markup( 'fast_monkey_post_content', 'p' );

		$output .= '<p>'.beans_post_more_link().'</p>';

   	return $output;

}

// Load beans document
beans_load_document();
