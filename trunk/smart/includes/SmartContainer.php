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

/**
 * Parent container class from which all child conatiners extends
 *
 *
 */
 
class SmartContainer extends SmartObject
{
    /**
     * Array that contains child Container instances
     *
     * @var array $instance
     */
    private static $instance = array();

    /**
     * Main Smart Config array
     *
     * @var array $config
     */            
    private $config;

    /**
     * Constructor
     *
     * @param array $config Main Smart config array
     */  
    function __construct( & $config)
    {
        $this->config = & $config;
    }

    /**
     * set object variable
     */        
    public function __set( $name, $value)
    {
        if( ($this->config['debug'] == TRUE) && isset( $this->$name) )
        {
            throw new SmartContainerException( "Value {$name} was previously declared");            
        }
        
        $this->$name = $value;
    }
    /**
     * get object variable
     */ 
    public function __get( $name )
    {
        if( !isset( $this->$name) )
        {
            return NULL;
        }
        else
        {
            return $this->$name;
        }
    }
    
    /**
     * Retrieve a new Container instance.
     *
     * @param string $class Container class name.
     * @param array $config Main Smart config array
     */
    public static function newInstance($class, & $config)
    {
        if (!isset(self::$instance[$class]))
        {
            try
            {
                $class_file = SMART_BASE_DIR . 'smart/includes/'.$class.'.php';
                
                if(!@file_exists($class_file))
                {
                    throw new SmartContainerException($class_file.' dosent exists');
                }
                
                include_once($class_file);
                
                // the class exists
                $object = new $class( $config );

                if (!($object instanceof SmartContainer))
                {
                    throw new SmartContainerException($class.' dosent extends SmartContainer');
                }

                // register and return singleton instance
                return self::$instance[$class] = $object;
            } 
            catch (SmartContainerException $e)
            {
                $e->performStackTrace();
            }                 
        } 
        return self::$instance[$class];
    }        
}

?>