<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/*
 * The XML_RPC Server Controller
 *
 */

/*
 * Front Controller File Name (this file)
 */
if(!defined( 'SMART_CONTROLLER' ))
{
   define('SMART_CONTROLLER', 'rpcserver.php'); 
}

/*
 * Relative path to SMART. Example: 'test/'
 */
if(!defined( 'SMART_RELATIVE_PATH' ))
{
   define('SMART_RELATIVE_PATH', './'); 
}

/*
 * Force to use the views folder, independed of the sys config settings.
 */
if(!defined( 'SMART_VIEW_FOLDER' ))
{
   define('SMART_VIEW_FOLDER', 'views_rpcserver/'); 
}

/* #################################################### */
/* ######### Dont change any thing below !!! ########## */
/* #################################################### */

/* 
 * Secure include of files from this script
 */
if(!defined( 'SMART_SECURE_INCLUDE' ))
{
    define('SMART_SECURE_INCLUDE', 1);
}

// Define the absolute path to SMART
//
define('SMART_BASE_DIR', dirname(__FILE__) . '/');

// Include the system core file.
include( SMART_BASE_DIR . 'smart/includes/smart_core.php' );

$smartController = SmartController::newInstance('SmartXmlRpcController');

$smartController->dispatch();

?>