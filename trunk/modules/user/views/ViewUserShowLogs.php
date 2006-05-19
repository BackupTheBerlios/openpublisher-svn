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
 
class ViewUserShowLogs extends SmartView
{
   /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'showLogs';
    
   /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public  $templateFolder = 'modules/user/templates/';
    
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {   
        $this->tplVar['logs'] = array();
        
        // get whole node tree
        $this->model->action('user','logGetEntries', 
                             array('id_item' => (int)$_GET['id_item'],
                                   'result'  => & $this->tplVar['logs'],
                                   'module'  => (string)$_GET['openerModule']));   
    }   
}

?>
