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
 * ControllerLinkWhatWouldYouDo class
 *
 */

class ControllerLinkWhatWouldYouDo extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
    
    /**
     * Execute the view
     *
     */
    function perform()
    {
        // init users template variable 
        $this->viewVar['link'] = array();   
        
        // add links which are finaly displayed
        // at the main admin page
        $this->viewVar['link']['wwyd'][] = array('link' => $this->controllerVar['url_base'].'/'.$this->viewVar['adminWebController'].'/mod/link/cntr/addLink/disableMainMenu/1',
                                                 'text' => 'Add Link');
    }     
}

?>