<?php
// ---------------------------------------------
// Open Publisher CMS
// Copyright (c) 2006
// by Armand Turpel < cms@open-publisher.net >
// http://www.open-publisher.net/
// ---------------------------------------------
// LICENSE LGPL
// http://www.gnu.org/licenses/lgpl.html
// ---------------------------------------------

/**
 * ViewNavigationIndex
 *
 */
 
class ViewNavigationIndex extends SmartView
{
     /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'index';
    
     /**
     * Default template folder for this view
     * @var string $templateFolder
     */    
    public  $templateFolder = 'modules/navigation/templates/';
    
    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // all accounts can access the map view
        if( !isset($_REQUEST['view']) || ($_REQUEST['view'] != "nodemap") )
        {
            // only administrators can access keyword module
            if($this->viewVar['loggedUserRole'] > $this->model->config['module']['navigation']['perm'])
            {
                // reload admin
                @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER);
                exit;  
            }
        }
    }     
}

?>