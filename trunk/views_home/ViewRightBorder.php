<?php
// ----------------------------------------------------------------------
// Open Publisher CMS
// Copyright (c) 2006
// by Armand Turpel < cms@open-publisher.net >
// http://www.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE LGPL
// http://www.gnu.org/licenses/lgpl.html
// ----------------------------------------------------------------------

/**
 * ViewRightBorder class
 *
 */

class ViewRightBorder extends SmartView
{
    /**
     * Cache expire time in seconds for this view
     * 0 = cache disabled
     */
    public $cacheExpire = 3600;
    
    /**
     * Execute the view of the "RightBorder" template
     */
    public function perform()
    {                           
        $this->tplVar['borderText']    = array();

        // get text for the front page
        $this->model->action('misc','getText', 
                             array('id_text' => 2,
                                   'result'  => & $this->tplVar['borderText'],
                                   'fields'  => array('body')));  
    }
}

?>