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
 * ViewDefaultSystemInfo class
 *
 */

class ViewDefaultSystemInfo extends SmartView
{
     /**
     * Template for this view
     * @var string $template
     */
    public $template = 'systemInfo';
    
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
        $this->tplVar['phpVersion'] = phpversion();
        $this->tplVar['mysqlInfo'] = array();

        $this->model->action('common','mysqlInfo', 
                             array('result' => &$this->tplVar['mysqlInfo']));
    }     
}

?>