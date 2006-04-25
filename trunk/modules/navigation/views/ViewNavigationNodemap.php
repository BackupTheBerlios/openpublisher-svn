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
 * ViewNavigationNodemap
 *
 */
 
class ViewNavigationNodemap extends SmartView
{
   /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'nodemap';
    
   /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public  $templateFolder = 'modules/navigation/templates/';
    
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {   
        // get the opener module
        if(isset($_REQUEST['openerModule']))
        {
            $this->tplVar['mod'] = (string)$_REQUEST['openerModule'];
            $this->tplVar['url_pager_var'] = (string)$_REQUEST['openerModule'].'_page=1';
        }
        else
        {
            $this->tplVar['url_pager_var'] = '';
            $this->tplVar['mod'] = 'navigation';
        }
        
        $this->tplVar['tree'] = array();
        
        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => 0,
                                   'result'  => & $this->tplVar['tree'],
                                   'status'  => array('=', 2),
                                   'fields'  => array('id_parent','status','id_node','title')));   
    }   
}

?>
