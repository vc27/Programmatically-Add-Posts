<?php
/**
 * File Name CreatePostsMetaVCWP.php
 * @package WordPress
 * @subpackage ParentTheme
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 1.0
 * @updated 02.14.13
 **/
#################################################################################################### */


if ( class_exists( 'CreatePostsMetaVCWP' ) ) return;





/**
 * CreatePostsMetaVCWP Class
 *
 * @version 1.0
 * @updated 02.14.13
 **/
class CreatePostsMetaVCWP {
	
	
	/**
	 * overwrite_meta_data
	 * 
	 * @access public
	 * @var bool
	 *
	 * Description:
	 * Bool to decide if existing meta data should be overwritten by incoming meta data.
	 **/
	var $overwrite_meta_data = false;
	
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
	 * post_meta
	 * 
	 * @access public
	 * @var array
	 *
	 * Description:
	 * Array of post meta data to be added.
	 **/
	var $post_meta = null;
	
	/**
	 * Sanitize Key
	 * 
	 * @access public
	 * @var bool
	 **/
	var $sanitize_meta_key = true; 
	
	/**
	 * Single Meta Data Filter Name
	 * 
	 * @access public
	 * @var string
	 **/
	var $single_meta_data_filter_name = 'filter-single-post-meta-vcwp';
	
	
	
	
	
	
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
	 * add_post_meta
	 *
	 * @version 1.0
	 * @updated 02.14.13
	 **/
	function add_post_meta() {
		
		if ( $this->has_post_meta() AND $this->has_post_id() ) {
			foreach ( $this->post_meta as $this->key => $this->meta_data ) {
				if ( $this->have_meta_data() ) {
					$this->filter_meta_data();
					$this->sanitize_meta_key();
					$this->pre_delete_meta_data();

					if ( $this->overwrite_meta_data ) {
						$this->update_post_meta();
					} else {
						$this->_add_post_meta();
					}
				}
			}
		}
		
	} // end function add_post_meta
	
	
	
	
	
	
	/**
	 * has_post_meta
	 *
	 * @version 1.0
	 * @updated 02.14.13
	 **/
	function has_post_meta() {
		
		if ( isset( $this->post_meta ) AND ! empty( $this->post_meta ) AND is_array( $this->post_meta ) ) {
			return true;
		} else {
			return false;
		}
		
	} // end function has_post_meta 
	
	
	
	
	
	
	/**
	 * have_meta_data
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function have_meta_data() {
		
		if ( 
			isset( $this->meta_data ) AND  ! empty( $this->meta_data ) AND  is_array( $this->meta_data )
			AND
			$this->has_key()
		) {
			return true;
		} else {
			return false;
		}
		
	} // end function have_meta_data 
	
	
	
	
	
	
	/**
	 * have_meta_data
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function filter_meta_data() {
		
		$this->meta_data = apply_filters( $this->single_meta_data_filter_name, $this->meta_data, $this->post_id );
		
	} // end function filter_meta_data
	
	
	
	
	
	
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
	function sanitize_meta_key() {
		
		if ( $this->sanitize_meta_key == true ) {
			$this->meta_data['key'] = sanitize_key( $this->meta_data['key'] );
		}
		
	} // end function sanitize_meta_key 
	
	
	
	
	
	
	/**
	 * pre_delete_meta_data
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function pre_delete_meta_data() {
		
		if ( $this->is_pre_delete() AND $this->has_key() ) {
			delete_post_meta( $this->post_id, $this->meta_data['key'] );
		}
		
	} // end function pre_delete_meta_data
	
	
	
	
	
	
	/**
	 * is_pre_delete
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function is_pre_delete() {
		
		if ( isset( $this->meta_data['pre_delete'] ) AND $this->meta_data['pre_delete'] == true ) {
			return true;
		} else {
			return false;
		}
		
	} // end function is_pre_delete 
	
	
	
	
	
	
	/**
	 * is_pre_delete
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function has_key() {
		
		if ( isset( $this->meta_data['key'] ) AND ! empty( $this->meta_data['key'] ) ) {
			return true;
		} else {
			return false;
		}
		
	} // end function has_key
	
	
	
	
	
	
	/**
	 * update_post_meta
	 *
	 * @version 1.0
	 * @updated 02.14.13
	 **/
	function update_post_meta() {
		
		update_post_meta( $this->post_id, $this->meta_data['key'], $this->meta_value(), $this->meta_prev_value() );
		
	} // end function update_post_meta
	
	
	
	
	
	
	/**
	 * _add_post_meta
	 *
	 * @version 1.0
	 * @updated 02.14.13
	 **/
	function _add_post_meta() {
		
		add_post_meta( $this->post_id, $this->meta_data['key'], $this->meta_value(), $this->meta_unique() );
		
	} // end function _add_post_meta
	
	
	
	
	
	
	/**
	 * meta_value
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function meta_value() {
		
		if ( isset( $this->meta_data['value'] ) ) {
			return $this->meta_data['value'];
		} else {
			return false;
		}
		
	} // end function meta_value 
	
	
	
	
	
	
	/**
	 * meta_prev_value
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function meta_prev_value() {
		
		if ( isset( $this->meta_data['prev_value'] ) ) {
			return $this->meta_data['prev_value'];
		} else {
			return false;
		}
		
	} // end function meta_prev_value 
	
	
	
	
	
	
	/**
	 * meta_unique
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function meta_unique() {
		
		if ( isset( $this->meta_data['unique'] ) ) {
			return $this->meta_data['unique'];
		} else {
			return false;
		}
		
	} // end function meta_unique
	
	
	
} // end class CreatePostsMetaVCWP