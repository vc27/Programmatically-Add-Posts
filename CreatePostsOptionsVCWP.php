<?php
/**
 * File Name CreatePostsOptionsVCWP.php
 * @package WordPress
 * @subpackage ParentTheme
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 1.0
 * @updated 02.14.13
 **/
#################################################################################################### */


if ( class_exists( 'CreatePostsOptionsVCWP' ) ) return;





/**
 * CreatePostsOptionsVCWP Class
 *
 * @version 1.0
 * @updated 02.14.13
 **/
class CreatePostsOptionsVCWP {
	
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
	 * post_options
	 * 
	 * @access public
	 * @var array
	 *
	 * Description:
	 * Array of post option data to be added.
	 **/
	var $post_options = null;
	
	/**
	 * Sanitize Key
	 * 
	 * @access public
	 * @var bool
	 **/
	var $sanitize_option_key = true; 
	
	/**
	 * Single option Data Filter Name
	 * 
	 * @access public
	 * @var string
	 **/
	var $single_option_filter_name = 'filter-single-post-option-vcwp';
	
	
	
	
	
	
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
	 * add_post_options
	 *
	 * @version 1.0
	 * @updated 02.14.13
	 **/
	function add_post_options() {
		
		if ( $this->has_post_options() AND $this->has_post_id() ) {
			foreach ( $this->post_options as $this->key => $this->option ) {
				if ( $this->have_option() ) {
					$this->filter_option();
					$this->sanitize_option_key();
					$this->update_option();
				}
			}
		}
		
	} // end function add_post_options
	
	
	
	
	
	
	/**
	 * has_post_options
	 *
	 * @version 1.0
	 * @updated 02.14.13
	 **/
	function has_post_options() {
		
		if ( isset( $this->post_options ) AND ! empty( $this->post_options ) AND is_array( $this->post_options ) ) {
			return true;
		} else {
			return false;
		}
		
	} // end function has_post_options 
	
	
	
	
	
	
	/**
	 * have_option
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function have_option() {
		
		if ( 
			isset( $this->option ) AND  ! empty( $this->option ) AND  is_array( $this->option )
			AND
			$this->has_key()
		) {
			return true;
		} else {
			return false;
		}
		
	} // end function have_option 
	
	
	
	
	
	
	/**
	 * have_option
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function filter_option() {
		
		$this->option = apply_filters( $this->single_option_filter_name, $this->option, $this->post_id );
		
	} // end function filter_option
	
	
	
	
	
	
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
	
	
	
	
	
	
	/**
	 * has_post_id
	 *
	 * @version 1.0
	 * @updated 02.14.13
	 **/
	function sanitize_option_key() {
		
		if ( $this->sanitize_option_key == true ) {
			$this->option['key'] = sanitize_key( $this->option['key'] );
		}
		
	} // end function sanitize_option_key
	
	
	
	
	
	
	/**
	 * has_key
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function has_key() {
		
		if ( isset( $this->option['key'] ) AND ! empty( $this->option['key'] ) ) {
			return true;
		} else {
			return false;
		}
		
	} // end function has_key
	
	
	
	
	
	
	/**
	 * update_option
	 *
	 * @version 1.0
	 * @updated 02.14.13
	 **/
	function update_option() {
		
		update_option( $this->post_id, $this->option['key'], $this->option_value() );
		
	} // end function update_option
	
	
	
	
	
	
	/**
	 * option_value
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function option_value() {
		
		if ( isset( $this->option['value'] ) ) {
			return $this->option['value'];
		} else {
			return false;
		}
		
	} // end function option_value 
	
	
	
} // end class CreatePostsOptionsVCWP