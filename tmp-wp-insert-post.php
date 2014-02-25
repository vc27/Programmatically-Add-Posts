<?php
/* Template Name: wp-insert-post */

/**
 * File Name tmp-wp-insert-post.php
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

$post = array( // post array
	'ID' => false, // Are you updating an existing post?
	'post_content' => '', // The full text of the post.
	'post_name' => '', // The name (slug) for your post
	'post_title' => '', // The title of your post.
	'post_status' => '', // [ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ] // Default 'draft'.
	'post_type' => '', // [ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ] // Default 'post'.
	'post_author' => '', // The user ID number of the author. Default is the current user ID.
	'ping_status' => '', // [ 'closed' | 'open' ] // Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
	'post_parent' => '', // Sets the parent of the new post, if any. Default 0.
	'menu_order' => '', // If new post is a page, sets the order in which it should appear in supported menus. Default 0.
	'to_ping' => '', // Space or carriage return-separated list of URLs to ping. Default empty string.
	'pinged' => '', // Space or carriage return-separated list of URLs that have been pinged. Default empty string.
	'post_password' => '', // Password for post, if any. Default empty string.
	'guid' => '', // Skip this and let Wordpress handle it, usually.
	'post_content_filtered' => '', // Skip this and let Wordpress handle it, usually.
	'post_excerpt' => '', // For all your post excerpt needs.
	'post_date' => '', // [ Y-m-d H:i:s ] // The time post was made.
	'post_date_gmt' => '', // [ Y-m-d H:i:s ] // The time post was made, in GMT.
	'comment_status' => '', // [ 'closed' | 'open' ] // Default is the option 'default_comment_status', or 'closed'.
	'post_category' => '', // array('category_id') // Default empty.
	'tags_input' => '', // '<tag>, <tag>' or array() // Default empty.
	'tax_input' => '', // array( 'taxonomy' => <array | string> ) ] // For custom taxonomies. Default empty.
	'page_template' => '', // [ <string> ] // Default empty.
);

$post_id = wp_insert_post( $post );

$new_post = get_post( $post_id );
print_r($new_post); die();