<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >2004, 2005, 2006
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * Japa view engine class
 *
 *
 */
 
class JapaViewEngine
{
    /**
     * Array of unique view engine object(s)
     *
     * @var array $instance
     */
    private static $instance = array();
    
    /**
     * Global config variables
     *
     * @var mixed $config
     */
    public $config = null;
    
    /**
     * Template variables
     *
     * @var mixed $vars
     */
    public $vars = null;

    /**
     * View variables
     *
     * @var mixed $viewVar
     */
    public $viewVar = null;

    /**
     * View buffer content
     *
     * @var string viewBufferContent
     */
    public $viewBufferContent = '';    
    
    /**
     * render the view
     *
     */
    function renderView()
    {
    } 

    /**
     * Retrieve a new View engine instance.
     *
     * @param string $class View engine class name.
     * @param array $config Main Japa config array
     */
    public static function newInstance($class, & $config)
    {
        if (!isset(self::$instance[$class]))
        {
            try
            {
                $class_file = JAPA_LIBRARY_DIR . 'japa/'.$class.'.php';
                
                if(!@file_exists($class_file))
                {
                    throw new JapaContainerException($class_file.' dosent exists');
                }
                
                include_once($class_file);
                
                // the class exists
                $object = new $class( $config );

                if (!($object instanceof JapaViewEngine))
                {
                    throw new JapaViewException($class.' dosent extends JapaViewEngine');
                }

                // register and return singleton instance
                return self::$instance[$class] = $object;
            } 
            catch (JapaViewException $e)
            {
                $e->performStackTrace();
            }                 
        } 
        return self::$instance[$class];
    }        
}

?>