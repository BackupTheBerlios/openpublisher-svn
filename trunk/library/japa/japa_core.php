<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/*
 * The core include file
 *
 */

// init japa configuration array
$_jpconfig = array();

/**
 * Path of the rewrite base
 * null means autodetect
 * but under some apache configuration of the RewriteBase
 * you have to set the rewrite base here manualy
 * 
 */
$_jpconfig['rewrite_base'] = false;

/**
 * Path to the config dir
 */
$_jpconfig['config_path'] = JAPA_APPLICATION_DIR . 'configs/';
/**
 * Idem for the directory of log files.
 */
$_jpconfig['logs_path'] = JAPA_APPLICATION_DIR . 'logs/';

/**
 * Cache folder.
 */
$_jpconfig['cache_path'] = JAPA_APPLICATION_DIR . 'cache/';

/**
 * Default controllers and views folders
 */

$_jpconfig['public_controllers_folder'] = JAPA_APPLICATION_DIR . 'controllers/default/';

/**
 * Default public images, scripts and styles folders
 */
$_jpconfig['public_views_folder']    = JAPA_PUBLIC_DIR . 'views/default/';
$_jpconfig['public_images_folder']   = JAPA_PUBLIC_DIR . 'images/default/';
$_jpconfig['public_scripts_folder']  = JAPA_PUBLIC_DIR . 'scripts/default/';
$_jpconfig['public_styles_folder']   = JAPA_PUBLIC_DIR . 'styles/default/';

/**
 * The common module name. This module is required!
 */
$_jpconfig['base_module'] = 'common';

/**
 * The common module name. This module is required!
 */
$_jpconfig['last_module'] = false;

/**
 * The default module name. This module is required!
 */
$_jpconfig['default_module'] = 'default';

/**
 * The setup module name. This module is required!
 */
$_jpconfig['setup_module'] = 'setup';
  
/**
 * Name of the cache type class
 */
$_jpconfig['cache_type'] = 'JapaFileControllerCache';

/**
 * cache time type       // filemtime or filestime
 */
$_jpconfig['cache_time_type'] = 'filemtime';

/**
 * Name of the template engine class used for the public templates
 */
$_jpconfig['public_view_engine'] = 'JapaViewEnginePhp';

/**
 * Name of the template engine class used for the module templates
 */
$_jpconfig['view_engine'] = 'JapaViewContainerPhp';

/**
 * Use php code analyzer
 * This an experimental feature.
 */
$_jpconfig['useCodeAnalyzer'] = false;

/**
 * Allowed php constructs in templates
 */
$_jpconfig['allowedConstructs'] = array('if','else','elseif','else if','endif',
                                          'foreach','endforeach','while','do','for','endfor',
                                          'continue','break','switch','case',
                                          'echo','print','print_r','var_dump',
                                          'defined','define','isset','empty','count');

/**
 * Disallowed php variables in templates
 */
$_jpconfig['disallowedVariables'] = array('$GLOBALS', 
                                           '$_jpconfig', 
                                           '$this');

/**
 * admin view folder
 */
$_jpconfig['admin_view_folder'] = 'views/';                                          
                                       

/**
 * Default and error controller.
 */
$_jpconfig['application_controllers']        = array("Web", "Module", "Ajax", "Rpc");
$_jpconfig['default_application_controller'] = "Web";
$_jpconfig['default_module_application_controller'] = "Module";
$_jpconfig['default_controller'] = 'index';
$_jpconfig['default_ajax_controller'] = 'ajax';
$_jpconfig['error_controller']   = 'error';

/**
 * enable output compression (false = disable, true = enable)
 */
$_jpconfig['output_compression']       = false;

/**
 * output compression level 1-9
 */
$_jpconfig['output_compression_level'] = '4';

/**
 * recipient email of system messages: system@foo.com
 */
$_jpconfig['system_email'] = '';

/**
 * message log types ('LOG|SHOW|MAIL')
 */
$_jpconfig['message_handle'] = 'LOG|SHOW';
 
/**
 * error reporting
 */
$_jpconfig['error_reporting'] = E_ALL;

/**
 * Set debug mode.
 */
$_jpconfig['debug'] = false; 

/**
 * enable custom debug messages
 * 
 */
$_jpconfig['enable_custom_debug'] = false; 

/**
 * How to show debug messages
 * 'append' 'newWindow' 'log'
 */
$_jpconfig['debugShowMessageType'] = 'log'; 

 /**
 * get numbers of sql queries.
 */
$_jpconfig['debugGetNumQueries'] = false;
/**
 * get sql queries.
 */
$_jpconfig['debugGetQueries']    = false;

/**
 * Rights for media folders and files
 */
$_jpconfig['media_folder_rights'] = 0777;
$_jpconfig['media_file_rights']   = 0777;

// Check if there is a custom config file else use default config settings
//
if (@file_exists($_jpconfig['config_path'] . 'my_config.php'))
{
    include_once($_jpconfig['config_path'] . 'my_config.php');
}

/**
 * Module name from which retrive a controller name.
 * The name of the class that is associated with a controller
 */
$_jpconfig['controller_map']  = array();

/**
 * Version info
 */
$_jpconfig['japa_version'] = '1.2';
$_jpconfig['japa_version_name'] = 'JAPA';

/**
 * Disable cache global
 */
$_jpconfig['disable_cache'] = 0;

// set include path to pear
ini_set( 'include_path', '.' . PATH_SEPARATOR . JAPA_LIBRARY_DIR . 'PEAR' . PATH_SEPARATOR . ini_get('include_path') );

// set include path to the Zend Framework
ini_set( 'include_path', '.' . PATH_SEPARATOR . JAPA_LIBRARY_DIR . 'Zend/library' . PATH_SEPARATOR . ini_get('include_path') );

#se japa exceptions
include_once( JAPA_LIBRARY_DIR . 'japa/JapaException.php' );
#se

#eh error handler
include_once( JAPA_LIBRARY_DIR . 'japa/JapaErrorHandler.php' );
#eh

#ac action class
include_once( JAPA_LIBRARY_DIR . 'japa/JapaAction.php' );
#ac

#mc model class
include_once( JAPA_LIBRARY_DIR . 'japa/JapaModel.php' );
#mc

#cc controller class
include_once( JAPA_LIBRARY_DIR . 'japa/JapaInterfaceController.php' );
#cc

#cc controller class
include_once( JAPA_LIBRARY_DIR . 'japa/JapaController.php' );
#cc

#scc cache class
include_once( JAPA_LIBRARY_DIR . 'japa/JapaCache.php' );
#scc

#scc router class
include_once( JAPA_LIBRARY_DIR . 'japa/JapaRouter.php' );
#scc

/**
 * japa config class
 */
include_once( JAPA_LIBRARY_DIR . 'japa/JapaConfig.php' );
$japaConfig = new JapaConfig( $_jpconfig );

// pass the config array to the controller
JapaController::setConfig( $japaConfig );

// include debug class
//
if($_jpconfig['enable_custom_debug'] == true)
{  
    include_once( JAPA_LIBRARY_DIR . 'japa/JapaDebug.php' );
    $japaDebug = new JapaDebug();
    $japaDebug->config = $japaConfig;
    JapaController::setDebug( $japaDebug );
}

unset($_jpconfig);

?>