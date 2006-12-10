<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/*
 * The base controller class, from which all other controllers extends
 *
 */

abstract class JapaController implements JapaInterfaceController
{
    /**
     * Controller object
     *
     * @var object $instance
     */ 
    private static $instance = null;

    /**
     * Model object
     *
     * @var object $model
     */        
    public $model;
    
    /**
     * Main Japa Config object
     * only serves as a refernce to the static config object
     *
     * @var object $config
     */        
    public $config;   
    
    /**
     * Main Japa Config object
     * only serves as a refernce to the static debug object
     *
     * @var object $debug
     */        
    public $debug;    

    /**
     * Main Japa Config array
     *
     * @var object $config
     */        
    private static $japaConfig;
    
    /**
     * Main Japa Debug object
     *
     * @var object $japadebug
     */        
    private static $japaDebug;

    /**
     * Controller construct
     *
     * Here we fetch the module folders, create some base class instances
     * and run a broadcast init event to all modules
     * 
     * @param object $router Japa router object
     */
    public function __construct( $router )
    {
        try
        {
            // set reference to the config object
            $this->config = self::$japaConfig;
            
            // set reference to the debug object
            $this->debug = self::$japaDebug;

            $url_base = $this->config->getVar('url_base');

            if( ($router != false) && ($url_base == null) )
            {
                // assign url base config var
                $this->config->setVar('url_base', $router->getBase());
                $this->router = & $router;
            }

            // display php errors
            ini_set('display_errors', $this->config->getVar('debug'));

            // log file of php errors
            ini_set('log_errors', true);  
            ini_set('error_log', JAPA_APPLICATION_DIR . 'logs/php_error.log');            

            // set error reporting
            error_reporting( $this->config->getVar('error_reporting') );

            // create japa model instance
            $this->model = new JapaModel( $this->config, $this->debug );          

            // create user-defined error handler
            new JapaErrorHandler( $this->config );

            // check if the modules directory exists
            if(!is_dir( JAPA_MODULES_DIR ))
            {
                throw new JapaInitException("Missing '".JAPA_MODULES_DIR . "' directory.");
            }
            
            // register all module folders
            $this->registerModulesFolders();

        } 
        catch(JapaInitException $e)
        {
           $e->performStackTrace();
        }
    }
    /**
     * Set exception flags.
     *
     * @param object $e Exception 
     */
    protected function setExceptionFlags( $e )
    {
        $e->flag = array('debug'           => $this->config->getVar('debug'),
                         'logs_path'       => $this->config->getVar('logs_path'),
                         'message_handle'  => $this->config->getVar('message_handle'),
                         'system_email'    => $this->config->getVar('system_email'),
                         'controller_type' => $this->config->getVar('controller_type'));  
        return;
    }
    /**
     * Send init call to all modules
     *
     */
    private function registerModulesFolders()
    {
        // A "common" base module must be present
        //
        $base_module = $this->config->getVar('base_module');
        $last_module = $this->config->getVar('last_module');
            
        if( $base_module != false )
        {
            
            $mod_common = JAPA_MODULES_DIR . $base_module;
  
            if(file_exists( $mod_common ))
            {
                // register this module folder
                $this->model->init( $base_module );
            }
            else
            {
                throw new JapaInitException("The module '{$mod_common}'  must be installed!");
            }
        }

        // get exsisting module folders
        //
        $tmp_directory = dir( JAPA_MODULES_DIR );
    
        while (false != ($tmp_dirname = $tmp_directory->read()))
        {
            if ( ( $tmp_dirname == '.' ) || ( $tmp_dirname == '..' ) || ( $tmp_dirname == '.svn' ) )
            {
                continue;
            }
            
            // dont register base module here
            if( $tmp_dirname == $base_module )
            {
                continue;
            }
            
            // dont register last module here
            if( $tmp_dirname == $last_module )
            {
                continue;
            }

            if ( @is_dir( JAPA_MODULES_DIR . $tmp_dirname) )
            {
                $this->model->init( $tmp_dirname );
            }
        }
  
        $tmp_directory->close();
        
        // register last module
        if( $last_module != false )
        {
            $mod_init = JAPA_MODULES_DIR . $last_module;
  
            if ( @is_dir( $mod_init ) )
            {
                $this->model->init( $last_module );
            }
            else
            {
                throw new JapaInitException("The 'last' module folder '{$mod_init}' is missing!");
            }
        }     
    }

    /**
     * Set config array
     *
     * @param array $config Global Config array
     */
    public static function setConfig( JapaConfig $config )
    {
        self::$japaConfig = $config;
    }
    
    /**
     * Set debug object
     *
     * @param object $debug 
     */
    public static function setDebug( JapaDebug $debug )
    {
        self::$japaDebug = $debug;
    }

    /**
     * Retrieve a new Controller instance.
     *
     * @param object $router Router which handles url rewrites (cli).
     */
    public static function newInstance( $router )
    {
        try
        {
            if (!isset(self::$instance))
            {
                // get application controller class name
                $application_controller = $router->getAppController();
                
                $class_file = JAPA_BASE_DIR . 'library/japa/'.$application_controller.'.php';
                
                if(!@file_exists($class_file))
                {
                    throw new JapaInitException($class_file.' dosent exists');
                }
                
                include_once($class_file);

                $object = new $application_controller( $router );

                if (!($object instanceof JapaController))
                {
                    throw new JapaInitException($class.' dosent extends JapaController');
                }

                // set singleton instance
                self::$instance = $object;
 
                return $object;
            } 
            else
            {
                $type = get_class(self::$instance);

                throw new JapaInitException('Controller instance exists: '.$type);
            }

        } 
        catch (JapaInitException $e)
        {
            $e->performStackTrace();
        } 
    }        
}

?>