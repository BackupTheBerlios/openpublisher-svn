<?php
// ----------------------------------------------------------------------
// Japa PHP Framework
// Copyright (c)  Armand Turpel < armand.turpel@open-publisher.net >
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * Page controller factory.
 *
 */

class JapaControllerCliPageFactory
{
    /**
     * Model object
     */
    public $model;  

    /**
     * Array of page controller instances
     */    
    private $pageController = array();

    /**
     * Array of page controller instances
     */    
    private $controllerVars = array();
    
    /**
     * dynamic call of public controller objects
     *
     * @param string $controller controller name
     * @param array $args Arguments passed to the controller. 
     * $args[0] additional data (mixed type) is aggregated by the controller object > $controller->controllerVar
     * $args[1] additional data (mixed type) passed to the constructor
     * $args[2] bool true = continue (return false) if a controller dosent exists
     * $args[3] bool true = force a new instance
     */
    public function __call( $controllername, $args )
    {
        $controllername = ucfirst($controllername);
        
        // avoid E_NOTICE message if $args elements are not defined
        if( !isset( $args[0] ) ) 
        {
            $args[0] = NULL;
        }
        if( !isset( $args[1] ) ) 
        {
            $args[1] = NULL;
        }

        // create page controller instance
        if(false === ($controller = $this->getControllerInstance( $controllername, $args[0] , $args[1]  )))
        {
            return false;
        }
          
        // Aggregate model object
        $controller->model = $this->model;  
        
        // Aggregate the main configuration object
        $controller->config = $this->model->config;
        
        // aggregate this object for create nested controllers
        $controller->controllerLoader = $this;
        
        // use this to pass variables inside (nested) controller
        $controller->controllerVar = & $this->controllerVars;

        // pass parameter data to the controller
        $controller->controllerData = & $data;
            
        // run controller prepended filters
        $controller->prependFilterChain();
         
        // perform on the main job
        $controller->perform();           
    } 
    
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
            $class_file = JAPA_APPLICATION_DIR . 'controllers/cli/' . $requested_controller . '.php';

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
                return false;
            }          
        }

        return $this->pageController[$class_name]; 
    }
    
    /**
     * Call broadcast controllers
     *
     * @param string $view View call name
     * @param mixed  $data Data passed to the view object
     * @param bool   $controller_must_exsists If true dont continue even if a controller dosent exists.
     */    
    public function broadcast( & $result, $controller, $data = false, $controller_must_exsists = false )
    {
        $_modules = $this->model->getAvailaibleModules();
        
        foreach($_modules as $module)
        {
            $controller_name = ucfirst($module).ucfirst($controller);
            if(false === ($result[] = $this->$controller_name( $data, $controller_must_exsists )))
            {
                array_pop($result);
            }
        }      
    } 
}

?>