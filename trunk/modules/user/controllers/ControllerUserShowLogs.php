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
 * ViewUserShowLogs
 *
 */
 
class ControllerUserShowLogs extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
    
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {   
        $this->viewVar['logs'] = array();
        
        $id_item      = $this->httpRequest->getParameter('id_item', 'get', 'digits');
        $openerModule = $this->httpRequest->getParameter('openerModule', 'get', 'raw');
        
        // get whole node tree
        $this->model->action('user','logGetEntries', 
                             array('id_item' => (int)$id_item,
                                   'result'  => & $this->viewVar['logs'],
                                   'module'  => (string)$openerModule));   
    }   
}

?>
