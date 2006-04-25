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
 * ViewOptionsIndex
 *
 */
 
class ViewOptionsIndex extends SmartView
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
    public  $templateFolder = 'modules/options/templates/';

    
    /**
     * Perform on the index view
     */
    public function perform()
    {

    }    
}

?>