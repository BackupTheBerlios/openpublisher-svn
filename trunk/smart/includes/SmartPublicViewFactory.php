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
 * Public view factory.
 *
 */

class SmartPublicViewFactory extends SmartViewFactory
{
    /**
     * dynamic call of public view objects
     *
     * @param string $view View name
     * @param array $args Arguments passed to the view. 
     * $args[0] additional data (mixed type) is aggregated by the view object > $view->viewVar
     * $args[1] additional data (mixed type) passed to the constructor
     * $args[2] bool true = continue (return FALSE) if a view dosent exists
     * $args[3] bool true = force a new instance
     */
    public function __call( $viewname, $args )
    {
        $viewname = ucfirst($viewname);
        
        // build the whole view class name
        $requestedView = 'View'.$viewname;

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

        if( !isset($this->$requestedView) || ($args[3] == TRUE) )
        {
            // path to the modules view class
            $class_file = SMART_BASE_DIR . $this->model->config['views_folder'] . $requestedView.'.php';

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
        
        // Aggregate view container object
        $viewContainer = SmartContainer::newInstance('SmartViewContainer', $this->model->config);
        // use this to pass variables inside the view(s)
        $view->viewVar = & $viewContainer->vars;

        // pass parameter data to the view
        $view->viewData = $args[0];

        // Set template engine
        if($view->templateEngine != FALSE)
        {
            $this->model->config['public_template_engine'] = & $view->templateEngine;
        }

        // include template container
        if( $view->renderTemplate == TRUE )
        {
            // parent template container class
            include_once( SMART_BASE_DIR . 'smart/includes/SmartTplContainer.php' );
            // get template container object
            $tplContainer = SmartContainer::newInstance( $this->model->config['public_template_engine'], $this->model->config );
            // aggregate the global config array
            $tplContainer->config = & $this->model->config;
            // aggregate this object
            $tplContainer->viewLoader = & $this;
            // pass view vars to the tpl container
            $tplContainer->viewVar = & $view->viewVar;            
            // aggregate this container variable to store template variables
            $view->tplVar = & $tplContainer->vars; 
        }
        
        // run authentication
        $view->auth();
            
        // run view prepended filters
        $view->prependFilterChain();
     
        // get cache template content if cache enabled
        if(($view->cacheExpire != 0) && ($this->model->config['disable_cache'] == 0))
        {
            $cache = SmartCache::newInstance($this->model->config['cache_type'], $this->model->config);
            if(($cacheid = $view->cacheId) == FALSE)
            {
                $cacheid = $requestedView.serialize($args).serialize($_REQUEST).$_SERVER['PHP_SELF'];
            }
            if($cache->cacheIdExists( $view->cacheExpire, $cacheid))
            {
                return;
            }
        }
        
        // perform on the main job
        $view->perform();
            
        // render a template if needed
        if ( TRUE == $view->renderTemplate )
        {
            // set template name
            // usually it is the same as the view name
            // except if it is defined else in view instance
            if(empty($view->template))
            {
                $tplContainer->template = $viewname;
            }
            else
            {
                $tplContainer->template = ucfirst($view->template);
            }
            
            // which template folder to use?
            if( $view->templateFolder != FALSE )
            {
                $tplContainer->templateFolder = $view->templateFolder;
            }
            else if(!defined('SMART_TPL_FOLDER'))
            {
                $tplContainer->templateFolder = $this->model->config['templates_folder'];
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
      
        // write template content to cache if cache enabled
        if(($view->cacheExpire != 0) && ($this->model->config['disable_cache'] == 0))
        {
            $cache->cacheWrite( $tplContainer->tplBufferContent );
        }
            
        // echo the context
        echo $tplContainer->tplBufferContent;     
        
        // empty template buffer content
        $tplContainer->tplBufferContent = '';
    } 
}

?>