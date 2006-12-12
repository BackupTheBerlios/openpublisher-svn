<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * Module page controller factory.
 *
 */

class JapaControllerModulePageFactory extends JapaControllerPageFactory
{
    /**
     * Stack array of controller name chunks
     */   
    protected $controller_match = array();

    /**
     * return path to the module controller
     *
     * @param string $class_name Controller class name
     */ 
    protected function getControllerPath( $class_name )
    {
        $controller_match = $this->split_controller_name( $class_name );
        return JAPA_MODULES_DIR . strtolower($controller_match[1]) . '/controllers/';
    }
    
    /**
     * Split controller name into module and controller name
     *
     * @param string $controller Controller call name
     */ 
    private function split_controller_name( $controller )
    {
        $match = array();
        if(!@preg_match("/^([A-Z]{1}[a-z0-9]+)([a-zA-Z0-9]+)/", $controller, $match))
        {
            throw new JapaControllerException('Wrong controller call name: ' . $controller);
        }
        // we push controller names in stack to retrieve later the views in a nested controller concept
        // which is the case for modul applications.
        array_push($this->controller_match, $match);
        return $match;
    }   
    
    /**
     * return path to the module view
     *
     * @param object $controller Controller instance
     */ 
    protected function getViewPath( $controller )
    {
        // which view folder to use?
        if( $controller->viewFolder != false )
        {
            return $controller->viewFolder;
        }
        else
        {
            // retrieve controller names from stack
            $controller_match = array_pop($this->controller_match);
            return JAPA_MODULES_DIR . strtolower($controller_match[1]) . '/views/';
        }  
    }
    
    /**
     * return path to the module view
     *
     * @param object $controller Controller instance
     */ 
    protected function popControllerFromStack()
    {
        array_pop($this->controller_match); 
    }
    
    /**
     * disable caching for module views
     *
     * @param object $controller Controller instance
     * @param string $content View content
     */ 
    protected function startControllerCache( $controller )
    {
        return true;
    }
    /**
     * disable caching for module views
     *
     * @param object $controller Controller instance
     * @param string $content View content
     */ 
    protected function writeControllerCache( $controller, & $content ){}
    
    /**
     * return controller instance
     *
     * @param string $class_name Controller class name
     * @param mixed $data Data passed to the controller constructor if any
     * @param bool $controller_must_exsists 
     */ 
    protected function getControllerInstance( & $class_name, & $data, $controller_must_exsists )
    {       
        // JapaAbstractPageController
        if( !isset($this->pageController[$class_name]) )
        {
            // build the whole controller class name
            $requested_controller = 'Controller' . $class_name;
            
            // path to the modules controller class
            $class_file = $this->getControllerPath( $class_name ) . $requested_controller . '.php';

            if(@file_exists($class_file))
            {
                include_once($class_file);

                // make instance of the module controller class
                $this->pageController[$class_name] = new $requested_controller( $data );
            }
            // if controller file dosent exists return false (see: this function description)
            elseif($controller_must_exsists == true)
            {
                throw new JapaPageControllerException("Controller dosent exists: ".$class_file);
            }  
            else
            {
                // Strip out controller name if this controller dosent exists
                array_pop($this->controller_match);
                return false;
            }          
        }

        return $this->pageController[$class_name]; 
    }
}

?>