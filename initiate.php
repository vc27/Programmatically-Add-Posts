<?php
/**
 * File Name initiate.php
 * @package CreatePosts
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 1.0
 * @updated 00.00.00
 **/
#################################################################################################### */


if ( ! defined('CreatePosts_INIT') ) {
	
	
	
	/**
	 * create__posts --> Wrapper Function
	 *
	 * @version 1.0
	 * @updated	00.00.00
	 **/
	function create__posts( $posts, $args ) {

		$create_posts = false;
		if ( ! class_exists( 'CreatePostsVCWP' ) ) {
			require_once( "CreatePostsVCWP.php" );
		}

		if ( class_exists( 'CreatePostsVCWP' ) ) {

			$create_posts = new CreatePostsVCWP();
			$create_posts->add_posts( $posts, $args );

		}

		return $create_posts;

	} // end function create__posts
	
	define( 'CreatePosts_INIT', true );
	
	
	
} // end if ( ! defined('CreatePosts_INIT') )