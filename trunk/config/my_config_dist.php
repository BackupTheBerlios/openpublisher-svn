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
 * My config file
 *
 * Here you can overwrite some configuration settings
 * for a production environment
 *
 * You have to rename this file to "my_config.php"
 *
 */

/*
 * Check if this script is included in the Smart environment
 * (Do not remove/change this check)
 */
if (!defined( 'SMART_SECURE_INCLUDE' ))
{
    exit;
}

/**
 * Name of the admin web controller
 */
$SmartConfig['admin_web_controller'] = 'admin.php';

/**
 * Name of the public web controller
 */
$SmartConfig['public_web_controller'] = 'index.php';

/**
 * The common module name.
 */
$SmartConfig['base_module'] = 'common';

/**
 * The module name which is receives the last message in an broadcast action!
 */
$SmartConfig['last_module'] = '';

/**
 * The default module name.
 */
$SmartConfig['default_module'] = 'default';

/**
 * The setup module name. 
 */
$SmartConfig['setup_module'] = 'setup';

/**
 * Path to the config dir
 */
$SmartConfig['config_path'] = SMART_BASE_DIR . 'config/';

/**
 * Path to the logs dir
 */
$SmartConfig['logs_path'] = SMART_BASE_DIR . 'logs/';

/**
 * Path to the cache dir
 */
$SmartConfig['cache_path'] = SMART_BASE_DIR . 'cache/';

/**
 * Name of the cache type class
 */
$SmartConfig['cache_type'] = 'SmartFileViewCache';

/**
 * cache time type       // filemtime or filestime
 */
$SmartConfig['cache_time_type'] = 'filemtime';

/**
 * Name of the template engine class used for the public templates
 * 'SmartTplContainerPhp' or 'SmartTplContainerSmarty'
 */
$SmartConfig['public_template_engine'] = 'SmartTplContainerPhp';

/**
 * Use php code analyzer
 * This an experimental feature.
 */
$SmartConfig['useCodeAnalyzer'] = FALSE;

/**
 * Allowed php constructs in templates
 */
$SmartConfig['allowedConstructs'] = array('if','else','elseif','else if','endif',
                                          'foreach','endforeach','while','do','for','endfor',
                                          'continue','break','switch','case',
                                          'echo','print','print_r','var_dump','exit',
                                          'defined','define','isset','empty','count');

/**
 * Disallowed php variables in templates
 */
$SmartConfig['disallowedVariables'] = array('$GLOBALS', 
                                            '$SmartConfig',
                                            '$this');

/**
 * admin view folder
 */
$SmartConfig['admin_view_folder'] = 'views/';
/**
 * admin template folder
 */
$SmartConfig['admin_template_folder'] = 'templates/';

/**
 * Default templates and views folder
 */
$SmartConfig['default_template_folder'] = 'templates_smart/';
$SmartConfig['default_css_folder']      = 'css_smart/';
$SmartConfig['default_view_folder']     = 'views_smart/';

/**
 * Default index and error views.
 */
$SmartConfig['default_view'] = 'index';
$SmartConfig['error_view']   = 'error';

/**
 * enable output compression (FALSE = disable, TRUE = enable)
 */
$SmartConfig['output_compression']       = FALSE;

/**
 * output compression level 1-9
 */
$SmartConfig['output_compression_level'] = '4';

/**
 * recipient email of system messages: system@foo.com
 */
$SmartConfig['system_email'] = '';

/**
 * message log types ('LOG|SHOW|MAIL')
 */
$SmartConfig['message_handle'] = 'LOG';
 
/**
 * error reporting
 */
$SmartConfig['error_reporting'] = E_ALL;

/**
 * Set debug mode.
 * Set to false in a production environement
 */
$SmartConfig['debug'] = true; 

/**
 * How to show debug messages
 * 'append' 'newWindow' 'log'
 */
$SmartConfig['debugShowMessageType'] = 'newWindow'; 

 /**
 * get numbers of sql queries.
 */
$SmartConfig['debugGetNumQueries'] = true;
/**
 * get sql queries.
 */
$SmartConfig['debugGetQueries']    = false;

/**
 * Rights for media folders and files
 */
$SmartConfig['media_folder_rights'] = 0777;
$SmartConfig['media_file_rights']   = 0777;

?>