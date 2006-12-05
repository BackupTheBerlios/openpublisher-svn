<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * JapaRouter
 *
 *
 */
class JapaRouter
{
    /**
     * Router object
     *
     * @var object $instance
     */ 
    private static $instance = null;
    
    protected $request = array();
    
    protected $application_controller = false;
                                           
    public function __construct( $config )
    {
        $this->applicationControllers = $config->getVar('application_controllers');
        
        // set default application controller
        $this->application_controller = 'JapaController' . $config->getVar('default_application_controller') . 'Application';
        
        $this->run();
    }
    
    protected function run()
    {
        throw new JapaRouterException('Missing run methode in Router');  
    }
    
    public function getVar( $var )
    {
        if(!isset($this->request[$var]))
        {
            return false;
        }
        return $this->request[$var];  
    }
    
    public function getAppController()
    {
        return $this->application_controller;  
    }
    
    /**
     * Retrieve a new Router instance.
     *
     * @param array $config Global configuration array
     * @param string $type Router type
     */
    public static function newInstance( $config, $type )
    {
        try
        {
            if (!isset(self::$instance))
            {
                $requestedRouter = 'JapaRouter'.ucfirst($type);
                
                $class_file = JAPA_LIBRARY_DIR . 'japa/'.$requestedRouter.'.php';
                
                if(!@file_exists($class_file))
                {
                    throw new JapaInitException($class_file.' dosent exists');
                }
                
                include_once($class_file);
                
                $object = new $requestedRouter( $config );

                if (!($object instanceof JapaRouter))
                {
                    throw new JapaInitException($class.' dosent extends JapaRouter');
                }

                // set singleton instance
                self::$instance = $object;

                return $object;
            } 
            else
            {
                $type = get_class(self::$instance);

                throw new JapaInitException('Router instance exists: '.$type);
            }

        } 
        catch (JapaInitException $e)
        {
            $e->performStackTrace();
        } 
    }     
}

?>