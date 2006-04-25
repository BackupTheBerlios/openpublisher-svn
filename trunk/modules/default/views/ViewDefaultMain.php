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

class ViewDefaultMain extends SmartView
{
     /**
     * Template for this view
     * @var string $template
     */
    public $template = 'main';
    
     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/default/templates/';
    
    /**
     * Execute the view
     *
     */
    function perform()
    {

    }     
}

?>