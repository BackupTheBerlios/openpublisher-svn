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
 * ViewMiscIndex class
 *
 */

class ViewMiscIndex extends SmartView
{
     /**
     * Default template for this view
     * @var string $template
     */
    public $template = 'index';
    
     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/misc/templates/';
    
    /**
     * Execute the view
     *
     */
    function perform()
    {
    } 
    
    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // only administrators can access misc module
        if($this->viewVar['loggedUserRole'] > $this->model->config['module']['misc']['perm'])
        {
            // reload admin
            @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER);
            exit;  
        }
    }         
}

?>