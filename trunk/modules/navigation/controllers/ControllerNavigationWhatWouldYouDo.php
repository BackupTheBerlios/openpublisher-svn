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
 * ControllerNavigationWhatWouldYouDo class
 *
 */

class ControllerNavigationWhatWouldYouDo extends JapaControllerAbstractPage
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
        if($this->renderView == FALSE)
        {
            return;
        }
        
        // init users template variable 
        $this->viewVar['navigation'] = array();   
        
        // add links which are finaly displayed
        // at the main admin page
        $this->viewVar['navigation']['wwyd'][] = array('link' => $this->controllerVar['url_base'].'/'.$this->viewVar['adminWebController'].'/mod/navigation/cntr/addNode/disableMainMenu/1',
                                                       'text' => 'Add navigation node');
    } 
    /**
     * Check permission to execute this view
     * @return bool
     */
    private function checkViewPermission()
    {
        if($this->controllerVar['loggedUserRole'] < 40)
        {
            return true;
        }
        return false;
    }        
}

?>