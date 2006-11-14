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
 * ViewDefaultMain class
 *
 */

class ControllerDefaultMain extends JapaControllerAbstractPage
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
        $this->viewVar['whatWouldYouDo'] = array();
        $this->controllerLoader->broadcast($this->viewVar['whatWouldYouDo'], 'whatWouldYouDo');  
    }     
}

?>