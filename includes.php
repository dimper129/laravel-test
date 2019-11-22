<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

/**
 * Import settings
 *
 */
require_once(strPath . "members/settings.php");

/**
 * Import Error handling engine
 *
 */
require_once(strPath . "classes/application_errors.php");
 
/**
 * Import Import Language Parser
 *
 */
require_once(strPath . "classes/langstrings.php");
/**
 * Import Language Parser
 *
 */
require_once( strPath . "classes/dbLayer.php" );


/**
 * Import Template Parser
 *
 */
require_once( strPath . "classes/template_parser.php" );

/**
 * Import Storage Class
 *
 */
require_once( strPath . "classes/storage.php" );

/**
 * Import Authentication Class
 *
 */
require_once( strPath . "classes/authentication.php" );

/**
 * Import Group Rights
 *
 */
require_once( strPath . "classes/grouprights.php" );

/**
 * Import Generic Function list
 *
 */

require_once( strPath . "functions/defs.php" );

define( "mysqlDateFormat" , mysqlDateFormat() );
define( "mysqlTimeFormat" , mysqlTimeFormat() );

?>