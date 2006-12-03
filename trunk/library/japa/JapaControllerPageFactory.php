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

class JapaControllerPageFactory
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
        if(false === ($controller = $this->getControllerInstance( $controllername, $args[0] , $args[1]  )))
        {
            return false;
        }
          
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

        // set view engine type
        $controller->viewEngine = $this->setViewEngine( $controller );

        $this->startViewEngine( $controller, $controllername );
        
        // run authentication
        $controller->auth();
            
        // run controller prepended filters
        $controller->prependFilterChain();

        // start caching
        if( false == $this->startControllerCache( $controller, $controllername ) )
        {
            return;
        }
         
        // perform on the main job
        $controller->perform();
           
        // render a view if needed
        if ( true == $controller->renderView )
        { 
            // set view name
            // usually it is the same as the controller name
            // except if it is defined else in controller instance
            if(empty($controller->view))
            {
                $this->viewEngine->view = 'view.' . $controllername;
            }
            else
            {
                $this->viewEngine->view = 'view.' . $controller->view;
            }            

           $this->viewEngine->viewFolder = $this->getViewPath( $controller );

            // render the view
            $this->viewEngine->renderView();      
        }                
       
        // run append filters
        $controller->appendFilterChain( $this->viewEngine->viewBufferContent );

        // write view content to cache
        $this->writeControllerCache( $controller, $this->viewEngine->viewBufferContent );
        
        // echo the context in simple views
        if( $controller->returnView == false )
        { 
            // echo the context
            echo $this->viewEngine->viewBufferContent;
        }
        // return the context in nested views
        else
        { 
            return $this->viewEngine->viewBufferContent;
        }  
        
        // empty view buffer content
        //$this->viewEngine->viewBufferContent = '';
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
                return false;
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
        else if(!defined('JAPA_VIEW_FOLDER'))
        {
            return $this->model->config['public_views_folder'];
        }
        else
        { 
            return JAPA_VIEW_FOLDER;
        }    
    }

    /**
     * set view engine
     *
     * @param object $controller Controller instance
     */ 
    protected function setViewEngine( $controller )
    {
        // Set view engine
        if($controller->viewEngine == false)
        {
             return $this->model->config['public_view_engine'];
        }
        return $controller->viewEngine;
    }
    
    /**
     * start view engine
     *
     * @param object $controller Controller instance
     */ 
    protected function startViewEngine( $controller )
    {
        // include view container
        if( $controller->renderView == true )
        {
            // controller container class
            include_once( JAPA_LIBRARY_DIR . 'japa/JapaViewEngine.php' );
            // get view container object
            $this->viewEngine = JapaViewEngine::newInstance( $controller->viewEngine, $this->model->config );
            // aggregate the global config array
            $this->viewEngine->config = & $this->model->config;        
            // aggregate this container variable to store view variables
            $controller->viewVar = & $this->viewEngine->vars; 
        }
    }

    /**
     * start caching
     *
     * @param object $controller Controller instance
     */ 
    protected function startControllerCache( $controller, $controllername )
    {
        // get cache view content if cache enabled
        if(($controller->cacheExpire != 0) && ($this->model->config[$this->model->config['base_module']]['disable_cache'] == 0))
        {
            $this->cache = JapaCache::newInstance($this->model->config['cache_type'], $this->model->config);
            if(($cacheid = $controller->cacheId) == false)
            {
                $cacheid = $controllername.serialize($_REQUEST).$_SERVER['PHP_SELF'];
            }
            if($this->cache->cacheIdExists( $controller->cacheExpire, $cacheid))
            {
                return false;
            }
        } 
        return true;
    }
    
    /**
     * write view content to cache
     *
     * @param object $controller Controller instance
     * @param string $content View content
     */ 
    protected function writeControllerCache( $controller, & $content )
    {
        // write view content to cache if cache enabled
        if(($controller->cacheExpire != 0) && ($this->model->config[$this->model->config['base_module']]['disable_cache'] == 0))
        {
            $this->cache->cacheWrite( $content );
        }
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