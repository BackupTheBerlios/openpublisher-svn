<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >


// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * Parent cache class from which all child cache classes extends
 *
 *
 */
 
class JapaCache
{
    public $config;
    
    /**
     * Constructor
     *
     * @param string $config Main Japa configuration array
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
        $class_file = JAPA_BASE_DIR . 'library/japa/'.$class.'.php';
                
        if(!@file_exists($class_file))
        {
            throw new JapaCacheException($class_file.' dosent exists');
        }
                
        include_once($class_file);
                
        // the class exists
        $object = new $class( $config );

        if (!($object instanceof JapaCache))
        {
            throw new JapaCacheException($class.' dosent extends JapaCache');
        }

        // register and return singleton instance
        return $object;              
    }        
}

?>