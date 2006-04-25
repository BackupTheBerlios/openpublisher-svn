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
 * Parent cache class from which all child cache classes extends
 *
 *
 */
 
class SmartCache extends SmartObject
{
    public $config;
    
    /**
     * Constructor
     *
     * @param string $config Main Smart configuration array
     */
    function __construct( & $config )
    {
        $this->config = & $config;
    }
    
    /**
     * Retrieve a new Cache instance.
     *
     * @param string $class Cache class name.
     */
    public static function newInstance($class, & $config)
    {
        $class_file = SMART_BASE_DIR . 'smart/includes/'.$class.'.php';
                
        if(!@file_exists($class_file))
        {
            throw new SmartCacheException($class_file.' dosent exists');
        }
                
        include_once($class_file);
                
        // the class exists
        $object = new $class( $config );

        if (!($object instanceof SmartCache))
        {
            throw new SmartCacheException($class.' dosent extends SmartCache');
        }

        // register and return singleton instance
        return $object;              
    }        
}

?>