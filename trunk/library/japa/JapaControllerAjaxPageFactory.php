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

class JapaControllerAjaxPageFactory
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
     * Model object
     */
    private $classPath = '';  
    
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
        $controller = $this->getControllerInstance( $controllername );
             
        // Aggregate model object
        $controller->model = $this->model;

        // Aggregate router object
        $controller->router = $this->router;

        // Aggregate httpRequest object
        $controller->httpRequest = $this->httpRequest;

        // Aggregate session object
        $controller->session = $this->model->session;       
        
        // Aggregate the main configuration array
        $controller->config = & $this->model->config;
        
        // aggregate this object for create nested controllers
        $controller->controllerLoader = $this;
        
        // use this to pass variables inside (nested) controller
        $controller->controllerVar = & $this->controllerVars;

        // pass parameter data to the controller
        $controller->controllerData = & $data;

        $requested_controller = 'Controller' . $controllername;
     
        // ajax register: view object, view class name, view class methods
        $this->model->ajaxServer->registerClass($controller,$requested_controller,$controller->methods);  
        
        // run authentication
        $controller->auth();
            
        // run controller prepended filters
        $controller->prependFilterChain();
    } 
    
    /**
     * set path to the controller
     *
     * @param string $path 
     */ 
    public function setClassFilePath( & $path )
    {
        $this->classPath = & $path;   
    }
    
    /**
     * return controller instance
     *
     * @param string $class_name Controller class name
     * @param mixed $data Data passed to the controller constructor if any
     * @param bool $controller_must_exsists 
     */ 
    private function getControllerInstance( & $class_name )
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
                $this->pageController[$class_name] = new $requested_controller();
            }
            // if controller file dosent exists return false (see: this function description)
            elseif($controller_must_exsists == true)
            {
                throw new JapaPageControllerException("Controller dosent exists: ".$class_file);
            }            
        }

        return $this->pageController[$class_name]; 
    }
    
    /**
     * return path to the module controller
     *
     * @param string $class_name Controller class name
     */ 
    protected function getControllerPath( $class_name )
    {
        return $this->classPath;
    }
}

?>