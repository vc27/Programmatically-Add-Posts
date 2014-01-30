<?php
/**
 * File Name CreatePostsVCWP.php
 * @package WordPress
 * @subpackage ParentTheme
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 1.4
 * @updated 02.10.13
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
 *
 * @version 1.1
 * @updated 02.10.13
 **/
class CreatePostsVCWP {
	
	
	/**
	 * Option name
	 * 
	 * @access public
	 * @var string
	 * Description:
	 * Used for various purposes when an import may be adding content to an option.
	 **/
	var $option_name = false;
	
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
	 * append_posts
	 * 
	 * @access public
	 * @var bool
	 *
	 * Description:
	 * Bool to decide if existing posts should be appended to the db or ignored.
	 **/
	var $append_posts = false;
	
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
	 * add_posts
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function add_posts( $posts ) {
		
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
			
		} // end if ( $this->have_posts() )
		
	} // end function add_posts 
	
	
	
	
	
	
	/**
	 * do_action
	 *
	 * @version 1.0
	 * @updated 04.01.13
	 **/
	function do_action() {
		
		if ( isset( $this->post['post_author'] ) AND ! empty( $this->post['post_author'] ) AND is_numeric( $this->post['post_author'] ) ) {
			$this->set( 'post_author', $this->post['post_author'] );
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
	 **/
	function prep_post_data() {
		
		$this->existing_post_id();
		$this->overwrite_post();
		$this->append_post();
		
	} // end function prep_post_data 
	
	
	
	
	
	
	/**
	 * existing_post_id
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function existing_post_id() {		
		global $wpdb;
		
		if ( isset( $this->post['ID'] ) AND is_numeric( $this->post['ID'] ) ) {
			$this->set( 'existing_post_id', $this->post['ID'] );
			return;
		}

		// Set post_name
		$post_name = sanitize_title_with_dashes( $this->post['post_title'] );
		$post_type = $this->post['post_title'];

		// Query DB
		$querystr = "	SELECT $wpdb->posts.ID
						FROM $wpdb->posts

						WHERE 
							$wpdb->posts.post_name = '$post_name' AND 
							$wpdb->posts.post_type = '$post_type' AND
							$wpdb->posts.post_status IN ( 'publish', 'draft', 'private' )
						";

		$results = $wpdb->get_results( $querystr );

		// Return results
		if ( isset( $results[0]->ID ) AND is_numeric( $results[0]->ID ) ) {			
			$this->set( 'existing_post_id', $results[0]->ID );
		} else {
			$this->set( 'existing_post_id', false );
		}
		
	} // end function existing_post_id 
	
	
	
	
	
	
	/**
	 * overwrite_post
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function overwrite_post() {
		
		if ( $this->overwrite_posts AND isset( $this->existing_post_id ) AND is_numeric( $this->existing_post_id ) AND $this->existing_post_id > 0 ) {
			$this->post['ID'] = $this->existing_post_id;
		}
		
	} // end function overwrite_post 
	
	
	
	
	
	
	/**
	 * append_post
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function append_post() {
		
		if ( $this->append_posts AND isset( $this->post['ID'] ) AND is_numeric( $this->post['ID'] ) AND $this->post['ID'] > 0 ) {
			unset( $this->post['ID'] );
		} else if ( $this->append_posts == false ) {
			$this->post['ignore'] = true;
		}
		
	} // end function append_post
	
	
	
	
	
	
	/**
	 * post_ignored
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function post_ignored() {
		
		if ( isset( $this->post['ignore'] ) AND $this->post['ignore'] == true ) {
			return true;
		} else {
			return false;
		}
		
	} // end function post_ignored 
	
	
	
	
	
	
	/**
	 * import_post
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function import_post() {
		
		$this->wp_insert_post();
		if ( ! $this->has_import_errors() ) {
			$this->set( 'current_post_id', $this->imported_post_ids[$this->current_post] );
			$this->add_post_meta();
			$this->add_post_options();
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
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function wp_insert_post() {
		
		$this->imported_post_ids[$this->current_post] = wp_insert_post( apply_filters( 'create-posts-vcwp--insert-post', $this->post ) );
		
	} // end function wp_insert_post
	
	
	
	
	
	
	/**
	 * has_import_errors
	 *
	 * @version 1.0
	 * @updated 02.10.13
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
	 * add_post_options
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function add_post_options() {
		
		if ( $this->has_post_options() AND $this->has_current_post_id() ) {
			
			if ( ! class_exists( 'CreatePostsOptionsVCWP' ) ) {
				require_once( 'CreatePostsOptionsVCWP.php' );
			}
			
			if ( class_exists( 'CreatePostsOptionsVCWP' ) ) {
				$this->_post_options = new CreatePostsOptionsVCWP();
				$this->_post_options->set( 'post_id', $this->current_post_id );
				$this->_post_options->set( 'post_options', $this->post_options );
				// $this->_post_options->set( 'sanitize_option_key', true ); // default = true 
				// $this->_post_options->set( 'single_option_filter_name', 'filter-single-post-option-vcwp' ); // default = filter-single-post-option-vcwp
				
				$this->_post_options->add_post_options();
				
				$this->append_post_option_group();
			}
			
		}
		
	} // end function add_post_options
	
	
	
	
	
	
	/**
	 * has_post_options
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function has_post_options() {
		
		if ( isset( $this->post_options ) AND ! empty( $this->post_options ) AND is_array( $this->post_options ) ) {
			return true;
		} else {
			return false;
		}
		
	} // end function has_post_options 
	
	
	
	
	
	
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
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function has_posts() {
	    
	    if ( is_array( $this->posts ) AND ! empty( $this->posts ) ) {
			$this->set( 'has_posts', true );
	        return true;
	    } else {
	        return false;
		}
	    
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
	 * The Item
	 *
	 * @version 1.0
	 * @updated 02.10.13
	 **/
	function the_post() {
		
		$this->current_post++;		
		$this->set_iterating_post( 'post', 'post' );
		$this->set_iterating_post( 'post_meta', 'post_meta' );
		$this->set_iterating_post( 'post_terms', 'post_terms' );
		$this->set_iterating_post( 'post_options', 'post_options' );
		$this->set_iterating_post( 'id', 'post_identifier' );
		
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