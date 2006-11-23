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
 * ControllerNavigationNodemap
 *
 */
 
class ControllerNavigationNodemap extends JapaControllerAbstractPage
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
        $openerModule = $this->httpRequest->getParameter('openerModule', 'request', 'alnum');
        
        // get the opener module
        if(!empty($openerModule))
        {
            $this->viewVar['mod'] = (string)$openerModule;
            $this->viewVar['url_pager_var'] = (string)$openerModule.'_page/1';
        }
        else
        {
            $this->viewVar['url_pager_var'] = '';
            $this->viewVar['mod'] = 'navigation';
        }
        
        $this->viewVar['tree'] = array();
        
        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => 0,
                                   'result'  => & $this->viewVar['tree'],
                                   'status'  => array('=', 2),
                                   'fields'  => array('id_parent','status','id_node','title')));   
    }   
}

?>
