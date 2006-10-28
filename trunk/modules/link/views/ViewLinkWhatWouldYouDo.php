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
 * ViewLinkWhatWouldYouDo class
 *
 */

class ViewLinkWhatWouldYouDo extends JapaControllerAbstractPage
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
    public $templateFolder = 'modules/link/templates/';
    
    /**
     * Execute the view
     *
     */
    function perform()
    {
        // init users template variable 
        $this->tplVar['link'] = array();   
        
        // add links which are finaly displayed
        // at the main admin page
        $this->tplVar['link']['wwyd'][] = array('link' => '?mod=link&view=addLink&disableMainMenu=1',
                                                'text' => 'Add Link');
    }     
}

?>