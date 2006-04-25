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
 * The base controller class, from which all other controllers extends
 *
 */

class SmartController extends SmartObject
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
     * Main Smart Config array
     *
     * @var array $config
     */        
    public $config;    

    /**
     * Main Smart Config array
     *
     * @var array $config
     */        
    private static $smartConfig;

    /**
     * Controller construct
     *
     * Here we fetch the module folders, create some base class instances
     * and run a broadcast init event to all modules
     * 
     * @param array $config Main Smart config array
     */
    public function __construct()
    {
        try
        {   
            // set reference to the config array
            $this->config = & self::$smartConfig;

            // display php errors
            ini_set('display_errors', $this->config['debug']);

            // log file of php errors
            ini_set('log_errors', TRUE);  
            ini_set('error_log', SMART_BASE_DIR . 'logs/php_error.log');            

            // set error reporting
            error_reporting( $this->config['error_reporting'] );

            // create base smart container instance
            $SmartContainer = new SmartContainer( $this->config );

            // create smart model instance
            $this->model = new SmartModel( $this->config );          

            // create user-defined error handler
            new SmartErrorHandler( $this->config );

            // check if the modules directory exists
            if(!is_dir(SMART_BASE_DIR . 'modules'))
            {
                throw new SmartInitException("Missing '".SMART_BASE_DIR . "modules' directory.");
            }

            // A "common" base module must be present
            //
            if( $this->config['base_module'] != '' )
            {
                $mod_common = SMART_BASE_DIR . 'modules/' . $this->config['base_module'];
  
                if( @is_dir( $mod_common ) )
                {
                    $this->model->init( $this->config['base_module'] );
                }
            }

            // check if a module was declared, which should play the last role in a broadcast action event
            if( $this->config['last_module'] != '' )
            {
                $last_module = $this->config['last_module'];
            }
            else
            {
                $last_module = FALSE;
            }

            // get exsisting module folders
            //
            $tmp_directory = dir( SMART_BASE_DIR . 'modules');
    
            while (FALSE != ($tmp_dirname = $tmp_directory->read()))
            {
                if ( ( $tmp_dirname == '.' ) || ( $tmp_dirname == '..' ) || ( $tmp_dirname == '.svn' ) )
                {
                    continue;
                }
                // dont load last module here
                if( $tmp_dirname == $last_module )
                {
                    continue;
                }

                if ( ($tmp_dirname != $this->config['base_module']) && @is_dir( SMART_BASE_DIR . 'modules/'.$tmp_dirname) )
                {
                    $this->model->init( $tmp_dirname );
                }
            }
  
            $tmp_directory->close();

            // get last module
            if( $last_module != FALSE )
            {
                $mod_init = SMART_BASE_DIR . 'modules/' . $last_module;
  
                if ( @is_dir( $mod_init ) )
                {
                    $this->model->init( $last_module );
                }
            }                         
        } 
        catch(SmartInitException $e)
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
        $e->flag = array('debug'           => $this->config['debug'],
                         'logs_path'       => $this->config['logs_path'],
                         'message_handle'  => $this->config['message_handle'],
                         'system_email'    => $this->config['system_email'],
                         'controller_type' => $this->config['controller_type']);  
        return;
    }

    /**
     * Set config array
     *
     * @param array $config Global Config array
     */
    public static function setConfig( &$config )
    {
        self::$smartConfig = & $config;
    }

    /**
     * Retrieve a new Controller instance.
     *
     * @param string $class Controller class name.
     * @param array $config Main Smart config array
     */
    public static function newInstance($class)
    {
        try
        {
            if (!isset(self::$instance))
            {
                $class_file = SMART_BASE_DIR . 'smart/includes/'.$class.'.php';
                
                if(!@file_exists($class_file))
                {
                    throw new SmartInitException($class_file.' dosent exists');
                }
                
                include_once($class_file);
                
                $object = new $class();

                if (!($object instanceof SmartController))
                {
                    throw new SmartInitException($class.' dosent extends SmartController');
                }

                // set singleton instance
                self::$instance = $object;
 
                return $object;
            } 
            else
            {
                $type = get_class(self::$instance);

                throw new SmartInitException('Controller instance exists: '.$type);
            }

        } 
        catch (SmartInitException $e)
        {
            $e->performStackTrace();
        } 
    }        
}

?>