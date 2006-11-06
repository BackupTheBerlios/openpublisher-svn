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

// Start output buffering
//
@ob_end_clean();
ob_start();

// init japa configuration array
$JapaConfig = array();

/**
 * Path to the config dir
 */
$JapaConfig['config_path'] = JAPA_APPLICATION_DIR . 'configs/';
/**
 * Idem for the directory of log files.
 */
$JapaConfig['logs_path'] = JAPA_APPLICATION_DIR . 'logs/';

/**
 * Cache folder.
 */
$JapaConfig['cache_path'] = JAPA_APPLICATION_DIR . 'cache/';

/**
 * Default controllers and views folders
 */
$JapaConfig['public_views_folder']       = JAPA_APPLICATION_DIR . 'views/default/';
$JapaConfig['public_controllers_folder'] = JAPA_APPLICATION_DIR . 'controllers/default/';

/**
 * Default public images, scripts and styles folders
 */
$JapaConfig['public_images_folder']   = JAPA_PUBLIC_DIR . 'images/default/';
$JapaConfig['public_scripts_folder']  = JAPA_PUBLIC_DIR . 'scripts/default/';
$JapaConfig['public_styles_folder']   = JAPA_PUBLIC_DIR . 'styles/default/';

/**
 * The common module name. This module is required!
 */
$JapaConfig['base_module'] = 'common';

/**
 * The common module name. This module is required!
 */
$JapaConfig['last_module'] = false;

/**
 * The default module name. This module is required!
 */
$JapaConfig['default_module'] = 'default';

/**
 * The setup module name. This module is required!
 */
$JapaConfig['setup_module'] = 'setup';
  
/**
 * Name of the cache type class
 */
$JapaConfig['cache_type'] = 'JapaFileViewCache';

/**
 * cache time type       // filemtime or filestime
 */
$JapaConfig['cache_time_type'] = 'filemtime';

/**
 * Name of the template engine class used for the public templates
 */
$JapaConfig['public_view_engine'] = 'JapaViewEnginePhp';

/**
 * Name of the template engine class used for the module templates
 */
$JapaConfig['view_engine'] = 'JapaViewContainerPhp';

/**
 * Use php code analyzer
 * This an experimental feature.
 */
$JapaConfig['useCodeAnalyzer'] = false;

/**
 * Allowed php constructs in templates
 */
$JapaConfig['allowedConstructs'] = array('if','else','elseif','else if','endif',
                                          'foreach','endforeach','while','do','for','endfor',
                                          'continue','break','switch','case',
                                          'echo','print','print_r','var_dump',
                                          'defined','define','isset','empty','count');

/**
 * Disallowed php variables in templates
 */
$JapaConfig['disallowedVariables'] = array('$GLOBALS', 
                                           '$JapaConfig', 
                                           '$this');

/**
 * admin view folder
 */
$JapaConfig['admin_view_folder'] = 'views/';                                          
                                       

/**
 * Default and error controller.
 */
$JapaConfig['application_controllers']        = array("Web", "Module");
$JapaConfig['default_application_controller'] = "Web";
$JapaConfig['default_controller'] = 'index';
$JapaConfig['error_controller']   = 'error';

/**
 * enable output compression (false = disable, true = enable)
 */
$JapaConfig['output_compression']       = false;

/**
 * output compression level 1-9
 */
$JapaConfig['output_compression_level'] = '4';

/**
 * recipient email of system messages: system@foo.com
 */
$JapaConfig['system_email'] = '';

/**
 * message log types ('LOG|SHOW|MAIL')
 */
$JapaConfig['message_handle'] = 'LOG';
 
/**
 * error reporting
 */
$JapaConfig['error_reporting'] = E_ALL;

/**
 * Set debug mode.
 */
$JapaConfig['debug'] = true; 

/**
 * Rights for media folders and files
 */
$JapaConfig['media_folder_rights'] = 0777;
$JapaConfig['media_file_rights']   = 0777;

// Check if there is a custom config file else use default config settings
//
if (@file_exists($JapaConfig['config_path'] . 'my_config.php'))
{
    include_once($JapaConfig['config_path'] . 'my_config.php');
}

/**
 * Module name from which retrive a controller name.
 * The name of the class that is associated with a controller
 */
$JapaConfig['controller_map']  = array();

/**
 * Version info
 */
$JapaConfig['japa_version'] = '1.2';
$JapaConfig['japa_version_name'] = 'JAPA';

/**
 * Disable cache global
 */
$JapaConfig['disable_cache'] = 0;

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

// pass the config array to the controller
JapaController::setConfig( $JapaConfig );

?>