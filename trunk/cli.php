<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

// bootstrap file

// cli only
if(isset($_REQUEST))
{
	exit;
}

// Define the absolute path to JAPA
//
define('JAPA_BASE_DIR', dirname(__FILE__) . '/');

// Define the absolute path to the JAPA library folder
//
define('JAPA_LIBRARY_DIR', JAPA_BASE_DIR . 'library/');

// Define the absolute path to the JAPA application folder
//
define('JAPA_APPLICATION_DIR', JAPA_BASE_DIR . 'application/');

// Define the absolute path to the JAPA modules folder
//
define('JAPA_MODULES_DIR', JAPA_BASE_DIR . 'modules/');

// Define the relative path to the JAPA public folder
//
define('JAPA_PUBLIC_DIR', 'public/');

// Include the system core file. 
include( JAPA_LIBRARY_DIR . 'japa/japa_core.php' );

// router which handles url rewrites
$japaRouter     = JapaRouter::newInstance( $japaConfig, 'cli' );

$japaController = JapaController::newInstance( $japaRouter );

$japaController->dispatch();

// Debug
if ($japaController->config->getVar('enable_custom_debug') == true)
{
    $debugLocation = array("file" => __FILE__, "line" => __LINE__);
    $japaDebug->setDebugPoint( 'end', $debugLocation );
    $japaDebug->japaVarDump( $japaController->config->getVar('debugShowMessageType') );
}

?>