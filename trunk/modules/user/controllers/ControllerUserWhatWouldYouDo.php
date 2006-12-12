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
 * ViewUserMain class
 *
 */

class ControllerUserWhatWouldYouDo extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
    
    /**
     * prepend filter chain
     *
     */
    function prependFilterChain()
    {
        // check permission to execute this view
        if(FALSE == $this->checkViewPermission())
        {
            $this->renderView = false;
        }    
    }
    
    /**
     * Execute the view
     *
     */
    function perform()
    {
        // stop if no template to render; means no rights
        if($this->renderView == false)
        {
            return;
        }
        
        // init users template variable 
        $this->viewVar['user'] = array();   
        
        $this->viewVar['user']['wwyd'][] = array('link' => $this->controllerVar['url_base'].'/'.$this->viewVar['adminWebController'].'/mod/user/cntr/addUser/disableMainMenu/1',
                                                 'text' => 'Add user');
    }     
    
    /**
     * Check permission to execute this view
     * @return bool
     */
    private function checkViewPermission()
    {
        if($this->controllerVar['loggedUserRole'] < 40)
        {
            return TRUE;
        }
        return FALSE;
    }    
}

?>