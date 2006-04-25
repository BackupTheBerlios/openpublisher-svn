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
 * Parent factory view class
 *
 */
 
class SmartViewFactory extends SmartObject
{
    /**
     * Model object
     */
    protected $model;
    
    /**
     * Main Smart Config Array
     */
    protected $config;    
    
    public function __construct( & $model, & $config )
    {
        $this->model  = & $model;
        $this->config = & $config;
    }
    
    /**
     * Call broadcast views
     *
     * @param string $view View call name
     * @param mixed  $data Data passed to the view object
     * @param mixed  $data Data passed to the view class constructor
     * @param bool   $continue If true continue even if a view dosent exists.
     *                         TRUE is required for broadcasting view calls.
     * @param bool   $instance If true force a new view instance if such an exists.
     */    
    public function broadcast( $view, $data = FALSE, $constructor_data = FALSE, $continue = TRUE, $instance = FALSE )
    {
        $_modules = $this->model->getAvailaibleModules();
        
        foreach($_modules as $module)
        {
            $view_name = ucfirst($module).ucfirst($view);
            $this->$view_name( $data, $constructor_data, $continue, $instance);
        }        
    }    
}

?>
