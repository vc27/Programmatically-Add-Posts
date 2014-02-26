<?php
/* Template Name: wp-delete-post */

/**
 * File Name tmp-wp-delete-post.php
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



$deleted_post = wp_delete_post( 71, true );

print_r($deleted_post); die();