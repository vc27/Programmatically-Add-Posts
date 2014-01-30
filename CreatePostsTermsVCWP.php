<?php
/**
 * File Name CreatePostsTermsVCWP.php
 * @package WordPress
 * @subpackage ParentTheme
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 1.0
 * @updated 03.10.13
 **/
#################################################################################################### */


if ( class_exists( 'CreatePostsTermsVCWP' ) ) return;





/**
 * CreatePostsTermsVCWP Class
 *
 * @version 1.0
 * @updated 03.10.13
 **/
class CreatePostsTermsVCWP {
	
	
	/**
	 * overwrite_terms
	 * 
	 * @access public
	 * @var bool
	 *
	 * Description:
	 * Bool to decide if existing terms should be overwritten by incoming terms
	 **/
	var $append_terms = false;
	
	/**
	 * post_id
	 * 
	 * @access public
	 * @var numeric
	 *
	 * Description:
	 * Current id of imported post.
	 **/
	var $post_id = null;
	
	/**
	 * terms
	 * 
	 * @access public
	 * @var array
	 *
	 * Description:
	 * Array of terms to be added.
	 **/
	var $terms = null;
	
	
	
	
	
	
	/**
	 * __construct
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function __construct() {
		
		
		
	} // end function __construct
	
	
	
	
	
	
	/**
	 * set
	 *
	 * @version 1.0
	 * @updated 02.14.13
	 **/
	function set( $key, $val = false ) {
		
		if ( isset( $key ) AND ! empty( $key ) ) {
			$this->$key = $val;
		}
		
	} // end function set
	
	
	
	
	
	
	/**
	 * add_post_terms
	 *
	 * @version 1.0
	 * @updated 03.10.13
	 **/
	function add_post_terms() {
		
		if ( $this->has_terms() AND $this->has_post_id() ) {
			foreach ( $this->terms as $this->term ) {
				
				$return = wp_set_object_terms( $this->post_id, $this->term['terms'], $this->term['taxonomy'], $this->append_terms );
				
			}
		}
		
	} // end function add_post_terms
	
	
	
	
	
	
	/**
	 * has_terms
	 *
	 * @version 1.0
	 * @updated 02.14.13
	 **/
	function has_terms() {
		
		if ( isset( $this->terms ) AND ! empty( $this->terms ) AND is_array( $this->terms ) ) {
			return true;
		} else {
			return false;
		}
		
	} // end function has_terms 
	
	
	
	
	
	
	/**
	 * has_post_id
	 *
	 * @version 1.0
	 * @updated 02.14.13
	 **/
	function has_post_id() {
		
		if ( isset( $this->post_id ) AND ! empty( $this->post_id ) AND is_numeric( $this->post_id ) ) {
			return true;
		} else {
			return false;
		}
		
	} // end function has_post_id 
	
	
	
} // end class CreatePostsTermsVCWP