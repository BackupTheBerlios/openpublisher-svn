<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ViewHeaderSmarty class
 */

class ViewHeaderSmarty extends SmartView
{
    /**
     * The template name associated with this view
     */
    public $template = 'headerSmarty';
    
    /**
     * Execute the view of the "headerSmarty" template
     */
    function perform()
    {
        // template var of the relative path
        $this->tplVar['relativePath'] = SMART_RELATIVE_PATH;
        // charset used for the html pages
        $this->tplVar['charset'] = & $this->config['charset'];
        // main controller
        $this->tplVar['smartController'] = SMART_CONTROLLER; 
    }
}

?>