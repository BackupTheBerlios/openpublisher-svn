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
 * SmartAjaxViewFactory factory.
 *
 */

class SmartAjaxViewFactory extends SmartViewFactory
{
    /**
     * dynamic call of ajax view objects
     *
     * @param string $view View name
     * @param array $args Arguments passed to the view. 
     */
    public function __call( $viewname, $args )
    {
        $viewname = ucfirst($viewname);
        
        // build the whole view class name
        $requestedView = 'View'.$viewname;

        // path to the view class
        $class_file = SMART_BASE_DIR . $this->model->config['views_folder'] . $requestedView.'.php';

        if(@file_exists($class_file))
        {
            include_once($class_file);

            // make instance of the module view class
            $requestedViewObject = new $requestedView();
            // Aggregate model object
            $requestedViewObject->model   = & $this->model;                
            // Aggregate config object
            $requestedViewObject->config  = & $this->model->config;
            // Aggregate session object
            $requestedViewObject->session = & $this->model->session;               
                     
            // ajax register: view object, view class name, view class methods
            $this->model->ajaxServer->registerClass($requestedViewObject,$requestedView,$requestedViewObject->methods);                

            // run authentication
            $requestedViewObject->auth();
            
            // run view prepended filters
            $requestedViewObject->prependFilterChain();
        }
        else
        {
            throw new SmartViewException("View dosent exists: ".$class_file);        
        }
    } 
}

?>