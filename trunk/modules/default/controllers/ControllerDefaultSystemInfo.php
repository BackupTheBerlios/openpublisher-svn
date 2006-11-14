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
 * ControllerDefaultSystemInfo class
 *
 */

class ControllerDefaultSystemInfo extends JapaControllerAbstractPage
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
        $this->viewVar['phpVersion'] = phpversion();
        $this->viewVar['mysqlInfo'] = array();

        $this->model->action('common','mysqlInfo', 
                             array('result' => &$this->viewVar['mysqlInfo']));
    }     
}

?>