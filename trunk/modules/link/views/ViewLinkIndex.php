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
 * ViewLinkIndex
 *
 */
 
class ViewLinkIndex extends SmartView
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
    public  $templateFolder = 'modules/link/templates/';
    
    /**
     * Perform on the index view
     */
    public function perform()
    {
        // set template var to show user options link
        // only on user main page and if the user role is at least an "editor"
        if(isset($_REQUEST['view']) && ($this->viewVar['loggedUserRole'] > 20))
        {
            $this->tplVar['show_admin_link'] = FALSE;
        }
        else
        {
            $this->tplVar['show_admin_link'] = TRUE;
        }
        return TRUE;
    }     
}

?>