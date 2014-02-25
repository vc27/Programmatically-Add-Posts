<?php
/**
 * File Name initiate-addons.php
 * @package ProjectName
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 1.0.0
 * @updated 00.00.00
 *
 * Description:
 * Include core functionality, activation and theme functions.
 **/
#################################################################################################### */


if ( ! defined('THEME_ADDONS_INIT') ) {
	
	// Added Functionality
	require_once( "ProgramaticallyAddPosts/initiate.php" );
	
	define( 'THEME_ADDONS_INIT', true );
	
} // end if ( ! defined('THEME_ADDONS_INIT') )