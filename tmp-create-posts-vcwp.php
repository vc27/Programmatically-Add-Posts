<?php
/* Template Name: create-posts-vcwp */

/**
 * File Name tmp-create-posts-vcwp.php
 * @package programaticaly-add-posts
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 1.0.0
 * @updated 00.00.00
 *
 * Description:
 * A file for testing functionality.
 **/
#################################################################################################### */


if ( ! is_user_logged_in() OR ! current_user_can('install_themes') ) {
	wp_die('Sorry no go!');
}

$import_posts_array = array(
	array( // Each array is a new post
		'post' => array( // post array
			'post_title' => 'Another Post',
			'post_content' => '',
			'post_status' => 'draft',
			'post_author' => 1,
			'post_type' => 'post',
		),
		'post_meta' => array(
			'special-key' => array(
				'key' => 'special-key',
				'value' => 'value',
				'unique' => 0,
				'prev_value' => 0,
				'pre_delete' => 0
			),
		),
		/*'post_terms' => array(
			'taxonomy' => array(
				'append_terms' => false,
				'taxonomy' => 'value',
				'terms' => array(), // (array/int/string)
			),
		),*/
	)
);


if ( function_exists( 'create__posts' ) ) {
	
	$posts = create__posts( $import_posts_array, array(
		'overwrite_posts' => true
	) );
	print_r($posts);
	
} // end if ( function_exists( 'create__posts' ) )






