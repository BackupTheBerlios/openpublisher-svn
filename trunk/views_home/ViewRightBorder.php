<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
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