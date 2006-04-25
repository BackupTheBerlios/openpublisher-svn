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
 * Admin view factory.
 *
 */

class SmartAdminViewFactory extends SmartViewFactory
{
    /**
     * dynamic call of admin view objects
     *
     * @param string $view View name
     * @param array $args Arguments passed to the view. 
     * $args[0] additional data (mixed type) is aggregated by the view object > $view->viewData
     * $args[1] additional data (mixed type) passed to the constructor
     * $args[2] bool true = continue (return FALSE) if a view dosent exists
     * $args[3] bool true = force a new instance
     */
    public function __call( $view_name, $args )
    {
        // split view name into module and view
        $view_match = $this->split_view_name( $view_name );
        
        // build the whole view class name
        $requestedView = 'View'.$view_name;

        // avoid E_NOTICE message if $args elements are not defined
        if( !isset( $args[0] ) ) 
        {
            $args[0] = NULL;
        }
        if( !isset( $args[1] ) ) 
        {
            $args[1] = NULL;
        }
        if( !isset( $args[2] ) ) 
        {
           $args[2] = NULL;
        } 
        if( !isset( $args[3] ) ) 
        {
           $args[3] = NULL;
        }        

        // check if a demanded view object dosent exists
        // or force a new instance anyway
        if( !isset($this->$requestedView) || ($args[3] == TRUE) )
        {
            // path to the modules view class
            $class_file = SMART_BASE_DIR . 'modules/'. strtolower($view_match[1]) . '/'.$this->model->config['admin_view_folder'].'View' . $view_name . '.php';

            if(@file_exists($class_file))
            {
                include_once($class_file);

                // force a new instance
                if( $args[3] == TRUE )
                {
                    $i = 1;
                    $requestedView = $requestedView . $i;
                    while( isset($this->$requestedView) )
                    {
                        $i++;
                        $requestedView = $requestedView . $i;
                    }
                    // make new instance of the module view class
                    $this->$requestedView = new $requestedView( $args[1] );
                }
                else
                {
                    // make instance of the module view class
                    $this->$requestedView = new $requestedView( $args[1] );
                }
            }
            // if view file dosent exists return FALSE (see: this function description)
            elseif($args[2] == TRUE)
            {
                return FALSE;
            }
            // if file dosent exists throw an exception
            else
            {
                throw new SmartViewException("View dosent exists: ".$class_file);
            }
        }
        
        // alias to the view object
        $view = $this->$requestedView;
          
        // Aggregate model object
        $view->model = & $this->model;
        
        // Aggregate session object
        $view->session = & $this->model->session;        

        // Aggregate the main configuration array
        $view->config = & $this->model->config;
            
        // include template container
        if( $view->renderTemplate == TRUE )
        {
            // parent template container class
            include_once( SMART_BASE_DIR . 'smart/includes/SmartTplContainer.php' );
            // get template container object
            $tplContainer = SmartContainer::newInstance( $this->model->config['template_engine'], $this->model->config );
            // aggregate the global config array
            $tplContainer->config = & $this->model->config;
            // aggregate this object
            $tplContainer->viewLoader = $this;
            // aggregate this container variable to store template variables
            $view->tplVar = & $tplContainer->vars; 
        }

        // Aggregate view container object
        $viewContainer = SmartContainer::newInstance('SmartViewContainer', $this->model->config );
        // use this to pass variables inside the view(s)
        $view->viewVar = & $viewContainer->vars;

        // pass parameter data to the view
        $view->viewData = & $args[0];
            
        // run authentication
        $view->auth();
            
        // run view prepended filters
        $view->prependFilterChain();
           
        // perform on the main job
        $view->perform();
          
        // render a template if needed
        if ( TRUE == $view->renderTemplate )
        { 
            // set template name
            $tplContainer->template = $view->template;
               
            // which template folder to use?
            if( $view->templateFolder != FALSE )
            {
                $tplContainer->templateFolder = $view->templateFolder;
            }
            else if(defined('SMART_TPL_FOLDER'))
            { 
                $tplContainer->templateFolder = SMART_TPL_FOLDER;
            }                
               
            // render the template
            $tplContainer->renderTemplate();
        }                
          
        // run append filters
        $view->appendFilterChain( $tplContainer->tplBufferContent );
           
        // echo the context
        echo $tplContainer->tplBufferContent;  
        
        // empty template buffer content
        $tplContainer->tplBufferContent = ''; 
    }
    
    /**
     * Split view call name into module and view name
     *
     * @param string $view View call name
     * @return array [0] = Module name / [1] View name
     */ 
    private function split_view_name( $view )
    {
        if(@preg_match("/([A-Z]{1}[a-z0-9]+)([a-zA-Z0-9]+)/", $view, $view_match))
        {
            return $view_match;
        }
        else
        {
            throw new SmartViewException('Wrong admin view call name: ' . $view);
        }
    }   
}

?>