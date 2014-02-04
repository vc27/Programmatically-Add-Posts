<?php
/**
 * File Name CreatePostsVCWP.php
 * @package WordPress
 * @subpackage CreatePostsVCWP
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 2.0
 * @updated 02.14.14
 *
 * Description:
 * Create posts by from an array of data
 **/
#################################################################################################### */


if ( class_exists( 'CreatePostsVCWP' ) ) return;

/* $import_posts_array = array(
	array(
		'id' => 'post-identifier', // used as an array key in stored option
		'post_meta' => array(
			'key' => array(
				'key' => 'key',
				'value' => 'value',
				'unique' => 0,
				'prev_value' => 0,
				'pre_delete' => 0
				),
			),
		'post_terms' => array(
			'taxonomy' => array(
				'append_terms' => false,
				'taxonomy' => 'value',
				'terms' => array(), // (array/int/string)
				),
			),
		'post_options' => array(
			'option_name' => array(
				'option_name' => 'option_name',
				'value' => 'value',
				'option_group' => 'option_group', // grouping options will biuld any array of grouped options.
				'option_group_type' => 'array_n', // array_n (numeric), array_a (associative)
				),
			),
		'post' => array( // post array
			'post_title' => 'My Post',
			'post_content' => '',
			'post_status' => 'draft',
			'post_author' => 1,
			'post_type' => 'post',
			),
		),
	);
		*/





/**
 * CreatePostsVCWP Class
 **/
class CreatePostsVCWP {
	
	
	
	/**
	 * Arguments for handling post imports
	 * 
	 * @access public
	 * @var array
	 **/
	var $args = array();
	
	
	
	/**
	 * Current post position in iterating array
	 * 
	 * @access public
	 * @var numeric
	 **/
	var $current_post = -1;
	
	
	
	/**
	 * In the loop
	 * 
	 * @access public
	 * @var bool
	 **/
	var $in_the_loop = false;
	
	
	
	/**
	 * Required Import Post Fields
	 * 
	 * @access public
	 * @var array
	 *
	 * Description:
	 * Fields that are required for a single post to be imported.
	 **/
	var $required_post_fields = array(
		'post_type',
		'post_title',
	);
	
	
	
	/**
	 * Import Post Status
	 * 
	 * @access public
	 * @var string
	 **/
	var $post_status = 'draft';
	
	
	
	/**
	 * Import Post Comment Status
	 * 
	 * @access public
	 * @var string
	 **/
	var $comment_status = 'closed';
	
	
	
	/**
	 * Post Author ID
	 * 
	 * @access public
	 * @var int
	 **/
	var $post_author = 0;
	
	
	
	/**
	 * Has Posts
	 * 
	 * @access public
	 * @var bool
	 **/
	var $has_posts = 0;
	
	
	
	/**
	 * Post Count
	 * 
	 * @access public
	 * @var numeric
	 **/
	var $post_count = 0;
	
	
	
	/**
	 * overwrite_posts
	 * 
	 * @access public
	 * @var bool
	 *
	 * Description:
	 * Bool to decide if existing posts should be overwritten by incoming data.
	 **/
	var $overwrite_posts = false;
	
	
	
	/**
	 * posts
	 * 
	 * @access public
	 * @var array
	 *
	 * Description:
	 * Array of incoming posts
	 **/
	var $posts = array();
	
	
	
	/**
	 * post
	 * 
	 * @access public
	 * @var array
	 *
	 * Description:
	 * Array of iterating single post data as it's being imported.
	 **/
	var $post = false;
	
	
	
	/**
	 * post meta
	 * 
	 * @access public
	 * @var array
	 *
	 * Description:
	 * Array of iterating single post post_meta data as it's being imported.
	 **/
	var $post_meta = false;
	
	
	
	/**
	 * post meta object
	 * 
	 * @access public
	 * @var array
	 *
	 * Description:
	 * Array of iterating single post post_meta processing data object
	 **/
	var $_post_meta = false;
	
	
	
	/**
	 * post options
	 * 
	 * @access public
	 * @var array
	 *
	 * Description:
	 * Array of iterating single post data to be added to an option.
	 **/
	var $post_options = false; 
	
	
	
	/**
	 * post options object
	 * 
	 * @access public
	 * @var array
	 *
	 * Description:
	 * Array of iterating single post option processing data object
	 **/
	var $_post_options = false;
	
	
	
	/**
	 * post options
	 * 
	 * @access public
	 * @var array
	 *
	 * Description:
	 * Array of iterating single post data to be added to an option.
	 **/
	var $post_terms = false; 
	
	
	
	/**
	 * post options object
	 * 
	 * @access public
	 * @var array
	 *
	 * Description:
	 * Array of iterating single post option processing data object
	 **/
	var $_post_terms = false;
	
	
	
	/**
	 * existing_post_id
	 * 
	 * @access public
	 * @var numeric
	 *
	 * Description:
	 * Determines if the iterating post registers as existing.
	 **/
	var $existing_post_id = 0;
	
	
	
	/**
	 * imported_post_ids
	 * 
	 * @access public
	 * @var array
	 *
	 * Description:
	 * Array of imported post ids from wp_insert_post
	 **/
	var $imported_post_ids = array();
	
	
	
	/**
	 * current_post_id
	 * 
	 * @access public
	 * @var numeric
	 *
	 * Description:
	 * Current id of imported post.
	 **/
	var $current_post_id = null;
	
	
	
	/**
	 * errored_posts
	 * 
	 * @access public
	 * @var array
	 *
	 * Description:
	 * Array of errored posts found during import
	 **/
	var $errored_posts = array();
	
	
	
	/**
	 * errors
	 * 
	 * @access public
	 * @var array
	 **/
	var $errors = array();
	
	
	
	
	
	
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
	 * @updated 02.10.13
	 **/
	function set( $key, $val = false ) {
		
		if ( isset( $key ) AND ! empty( $key ) ) {
			$this->$key = $val;
		}
		
	} // end function set
	
	
	
	
	
	
	/**
	 * error
	 *
	 * @version 1.0
	 * @updated 00.00.00
	 **/
	function error( $error_key ) {
		
		$this->errors[] = $error_key;
		
	} // end function error
	
	
	
	
	
	
	/**
	 * add_posts
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function add_posts( $posts, $args ) {
		
		$this->set_import_args( $args );
		$this->set( 'posts', $posts );
		$this->set_post_count();
		
		if ( $this->have_posts() ) {
			
			while ( $this->have_posts() ) {
				
				$this->the_post();
				
				// Import single posts...
				if ( $this->has_post_data() ) {					
					$this->prep_post_data();
					
					$this->import_post();
					$this->do_action();
					
				} else {
					
					$this->post['error'] = 'missing_post_data';
					$this->append_errored_post();
					
				}// end if ( $this->has_post_data() )
				
			} // end while ( $this->have_posts() )
			
			// $this->update_post_option_groups();
			
		} else {
			
			$this->error('no-posts');
			
		} // end if ( $this->have_posts() )
		
	} // end function add_posts 
	
	
	
	
	
	
	/**
	 * set_import_args
	 *
	 * @version 1.0
	 * @updated 02.04.14
	 **/
	function set_import_args( $args = array() ) {
		
		if ( isset( $args ) AND ! empty( $args ) AND is_array( $args ) ) {
			$this->set( 'args', array_merge( $this->args, $args ) );
		}
		
		foreach ( $this->args as $k => $v ) {
			$this->set( $k, $v );
		}
		
	} // end function set_import_args 
	
	
	
	
	
	
	/**
	 * do_action
	 *
	 * @version 1.1
	 * @updated 02.04.14
	 *
	 * Description:
	 * Pass post id, author and type to an action.
	 **/
	function do_action() {
		
		if ( isset( $this->post['post_author'] ) AND ! empty( $this->post['post_author'] ) AND is_numeric( $this->post['post_author'] ) AND $this->post['post_author'] >= 1 ) {
			$this->set( 'post_author', $this->post['post_author'] );
		} else if ( isset( $this->post_author ) AND ! empty( $this->post_author ) AND is_numeric( $this->post_author ) AND $this->post_author >= 1 ) {
			$this->set( 'post_author', $this->post_author );
		} else {
			global $userdata;
			$this->set( 'post_author', $userdata->ID );
		}
		
		do_action( 'import--add-update-delete-post', $this->current_post_id, $this->post_author, 'import' );
		
	} // end function do_action
	
	
	
	
	
	
	/**
	 * has_post_data
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 *
	 * Description:
	 * Check to make sure that each post has the required
	 * fields in order to be imported.
	 **/
	function has_post_data() {
		
		foreach ( $this->required_post_fields as $key ) {
			if ( ! isset( $this->post[$key] ) OR empty( $this->post[$key] ) ) {
				return false;
			} 
		}
		
		return true;
		
	} // end function has_post_data 
	
	
	
	
	
	
	/**
	 * prep_post_data
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 * 
	 * Description:
	 * Pre post for inserting in to database. Any added added post preparation
	 * should be done at this point. 
	 **/
	function prep_post_data() {
		
		$this->set_existing_post_id();
		$this->overwrite_post();
		
	} // end function prep_post_data 
	
	
	
	
	
	
	/**
	 * set_existing_post_id
	 *
	 * @version 1.1
	 * @updated 02.04.14
	 *
	 * Description:
	 * Check for an existing post-id and set "existing_post_id".
	 **/
	function set_existing_post_id() {		
		global $wpdb;
		
		// if the post array already has a post-id don't bother checking the db
		if ( isset( $this->post['ID'] ) AND is_numeric( $this->post['ID'] ) ) {
			$this->set( 'existing_post_id', $this->post['ID'] );
			return;
		}

		// Set post_name
		$post_name = sanitize_title_with_dashes( $this->post['post_title'] );
		$post_type = $this->post['post_type'];

		// Query DB
		$querystr = "	SELECT $wpdb->posts.ID
						FROM $wpdb->posts

						WHERE 
							$wpdb->posts.post_name = '$post_name' AND 
							$wpdb->posts.post_type = '$post_type'
						";

		$results = $wpdb->get_results( $querystr );

		// Return results
		if ( isset( $results[0]->ID ) AND is_numeric( $results[0]->ID ) ) {			
			$this->set( 'existing_post_id', $results[0]->ID );
		} else {
			$this->set( 'existing_post_id', false );
		}
		
	} // end function set_existing_post_id 
	
	
	
	
	
	
	/**
	 * overwrite_post
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 * 
	 * Description:
	 * If "overwrite_posts" is set and existing_post_id is set 
	 * pass existing_post_id to the current post for use.
	 **/
	function overwrite_post() {
		
		if ( $this->overwrite_posts AND isset( $this->existing_post_id ) AND is_numeric( $this->existing_post_id ) AND $this->existing_post_id > 0 ) {
			$this->post['ID'] = $this->existing_post_id;
		}
		
	} // end function overwrite_post
	
	
	
	
	
	
	/**
	 * import_post
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 *
	 * Description:
	 * Import single post.
	 **/
	function import_post() {
		
		$this->wp_insert_post();
		if ( ! $this->has_import_errors() ) {
			$this->set( 'current_post_id', $this->imported_post_ids[$this->current_post] );
			$this->add_post_meta();
			$this->add_post_terms();
		} else {
			$this->set( 'current_post_id', null );
			$this->post['error'] = 'is_wp_error';
			$this->post['wp_error'] = $this->imported_post_ids[$this->current_post];
			$this->append_errored_post();
		}
		
	} // end function import_post 
	
	
	
	
	
	
	/**
	 * wp_insert_post
	 *
	 * @version 1.1
	 * @updated 02.04.14
	 * 
	 * Description:
	 * Insert post using wordpress wp_insert_post()
	 * Add returned post_id to the imported post ids array.
	 * Filter is available for last min alterations.
	 **/
	function wp_insert_post() {
		
		$this->imported_post_ids[$this->current_post] = wp_insert_post( apply_filters( 'create-posts-vcwp--insert-post', $this->post, $this ) );
		
	} // end function wp_insert_post
	
	
	
	
	
	
	/**
	 * has_import_errors
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 * 
	 * Description:
	 * User wp is_wp_error to check for errors.
	 **/
	function has_import_errors() {
		
		return is_wp_error( $this->imported_post_ids[$this->current_post] );
		
	} // end function has_import_errors 
	
	
	
	
	
	
	/**
	 * append_errored_post
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function append_errored_post() {
		
		$this->errored_posts[] = $this->post;
		
	} // end function append_errored_post
	
	
	
	
	
	
	/**
	 * has_current_post_id
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function has_current_post_id() {
		
		if ( isset( $this->current_post_id ) AND ! empty( $this->current_post_id ) AND is_numeric( $this->current_post_id ) ) {
			return true;
		} else {
			return false;
		}
		
	} // end function has_current_post_id
	
	
	
	
	
	
	/**
	 * add_post_meta
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function add_post_meta() {
		
		if ( $this->has_post_meta() AND $this->has_current_post_id() ) {
			
			if ( ! class_exists( 'CreatePostsMetaVCWP' ) ) {
				require_once( 'CreatePostsMetaVCWP.php' );
			}
			
			if ( class_exists( 'CreatePostsMetaVCWP' ) ) {
				$this->_post_meta = new CreatePostsMetaVCWP();
				$this->_post_meta->set( 'overwrite_meta_data', $this->overwrite_posts );
				$this->_post_meta->set( 'post_id', $this->current_post_id );
				$this->_post_meta->set( 'post_meta', $this->post_meta );
				// $this->_post_meta->set( 'sanitize_meta_key', true ); // default = true 
				// $this->_post_meta->set( 'single_meta_data_filter_name', 'filter-single-post-meta-vcwp' ); // default = filter-single-post-meta-vcwp
				$this->_post_meta->add_post_meta();
			}
			
		}
		
	} // end function add_post_meta
	
	
	
	
	
	
	/**
	 * has_post_meta
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function has_post_meta() {
		
		if ( isset( $this->post_meta ) AND ! empty( $this->post_meta ) AND is_array( $this->post_meta ) ) {
			return true;
		} else {
			return false;
		}
		
	} // end function has_post_meta 
	
	
	
	
	
	
	/**
	 * add_post_terms
	 *
	 * @version 1.0
	 * @updated 03.10.13
	 **/
	function add_post_terms() {
		
		if ( $this->has_post_terms() AND $this->has_current_post_id() ) {
			
			if ( ! class_exists( 'CreatePostsTermsVCWP' ) ) {
				require_once( 'CreatePostsTermsVCWP.php' );
			}
			
			if ( class_exists( 'CreatePostsTermsVCWP' ) ) {
				$this->_post_terms = new CreatePostsTermsVCWP();
				$this->_post_terms->set( 'post_id', $this->current_post_id );
				$this->_post_terms->set( 'terms', $this->post_terms );
				// $this->_post_terms->set( 'sanitize_option_key', true ); // default = true 
				// $this->_post_terms->set( 'single_option_filter_name', 'filter-single-post-option-vcwp' ); // default = filter-single-post-option-vcwp
				
				$this->_post_terms->add_post_terms();
			}
			
		}
		
	} // end function add_post_terms
	
	
	
	
	
	
	/**
	 * has_post_terms
	 *
	 * @version 1.0
	 * @updated 03.10.13
	 **/
	function has_post_terms() {
		
		if ( isset( $this->post_terms ) AND ! empty( $this->post_terms ) AND is_array( $this->post_terms ) ) {
			return true;
		} else {
			return false;
		}
		
	} // end function has_post_terms
	
	
	
	
	
	
	####################################################################################################
	/**
	 * Post Loop
	 **/
	####################################################################################################
	
	
	
	
	
	
	/**
	 * have_posts
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 * 
	 * Description:
	 * Check for incoming post and iterate the loop
	 **/
	function have_posts() {
		
		if ( $this->has_posts() ) { 
			
			if ( $this->current_post + 1 < $this->post_count ) {
				
				$this->in_the_loop = true;
				return true;
				
			} else if ( $this->current_post + 1 == $this->post_count AND $this->post_count > 0 ) {
				
				return false;
				
			}
			
			$this->in_the_loop = false;
			return false;
			
		} else {
			return false;
		}
		
	} // end function have_posts
	
	
	
	
	
	
	/**
	 * has posts
	 *
	 * @version 1.1
	 * @updated 02.04.14
	 **/
	function has_posts() {
	    
	    if ( isset( $this->posts ) AND ! empty( $this->posts ) AND is_array( $this->posts ) ) {
			$this->set( 'has_posts', 1 );
	    } else {
	        $this->set( 'has_posts', 0 );
		}
		
		return $this->has_posts;
	    
	} // end function has_posts
	
	
	
	
	
	
	/**
	 * has posts
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function set_post_count() {
	    
		if ( $this->has_posts() ) {
			$this->set( 'post_count', count( $this->posts ) );
		}
	    
	} // end function set_post_count
	
	
	
	
	
	
	/**
	 * The Post
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 * 
	 * Description:
	 * Disperse post data to various locations for further use.
	 * post, post_meta, post_terms, post_options
	 **/
	function the_post() {
		
		$this->current_post++;		
		$this->set_iterating_post( 'post', 'post' );
		$this->set_iterating_post( 'post_meta', 'post_meta' );
		$this->set_iterating_post( 'post_terms', 'post_terms' );
		$this->set_iterating_post( 'post_options', 'post_options' );
		
	} // end function the_post
	
	
	
	
	
	
	/**
	 * set_post
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function set_iterating_post( $incoming_key, $set_key ) {
		
		if ( isset( $this->posts[$this->current_post][$incoming_key] ) AND ! empty( $this->posts[$this->current_post][$incoming_key] ) AND is_array( $this->posts[$this->current_post][$incoming_key] ) ) {
			$this->set( $set_key, $this->posts[$this->current_post][$incoming_key] );
		} else {
			$this->set( $set_key, false );
		}
		
	} // end function set_post
	
	
	
} // end class CreatePostsVCWP