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
 * ControllerKeywordMap
 *
 */
 
class ControllerKeywordMap extends JapaControllerAbstractPage
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
            $opener_url_vars = $this->httpRequest->getParameter('opener_url_vars', 'request', 'alnum');
            $this->viewVar['mod'] = (string)$openerModule;
            $this->viewVar['opener_url_vars'] = '/addkey/1' . base64_decode((string)$opener_url_vars);
        }
        else
        {
            $this->viewVar['mod'] = 'keyword';
            $this->viewVar['opener_url_vars'] = '';
        }
        
        $this->viewVar['tree'] = array();
        
        // get whole node tree
        $this->model->action('keyword','getTree', 
                             array('id_key' => 0,
                                   'result'  => & $this->viewVar['tree'],
                                   'status'  => array('>', 0),
                                   'fields'  => array('id_parent','status','id_key','title')));   
    }   
}

?>
