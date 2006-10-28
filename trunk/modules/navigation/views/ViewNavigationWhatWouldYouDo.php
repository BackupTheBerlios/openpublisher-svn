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

class ViewNavigationWhatWouldYouDo extends JapaControllerAbstractPage
{
     /**
     * Template for this view
     * @var string $template
     */
    public $template = 'whatWouldYouDo';
    
     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/navigation/templates/';

    /**
     * prepend filter chain
     *
     */
    function prependFilterChain()
    {
        // check permission to execute this view
        if(FALSE == $this->checkViewPermission())
        {
            $this->renderTemplate = FALSE;
        }    
    }
    
    /**
     * Execute the view
     *
     */
    function perform()
    {
        // stop if no template to render; means no rights
        if($this->renderTemplate == FALSE)
        {
            return;
        }
        
        // init users template variable 
        $this->tplVar['navigation'] = array();   
        
        // add links which are finaly displayed
        // at the main admin page
        $this->tplVar['navigation']['wwyd'][] = array('link' => '?mod=navigation&view=addNode&disableMainMenu=1',
                                                      'text' => 'Add navigation node');
    } 
    /**
     * Check permission to execute this view
     * @return bool
     */
    private function checkViewPermission()
    {
        if($this->viewVar['loggedUserRole'] < 40)
        {
            return TRUE;
        }
        return FALSE;
    }        
}

?>