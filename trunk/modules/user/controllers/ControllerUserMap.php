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
 * ControllerUserMap
 *
 */
 
class ControllerUserMap extends JapaControllerAbstractPage
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
            $this->viewVar['opener_url_vars'] = '/adduser/1/' . base64_decode((string)$opener_url_vars);
        }
        else
        {
            $this->viewVar['mod'] = 'user';
            $this->viewVar['opener_url_vars'] = '';
        }
        
        $this->viewVar['show_options_link'] = false;
        $this->viewVar['users'] = array();
        
        // assign template variable with users
        $this->model->action('user', 'getUsers',
                             array('result'         => & $this->viewVar['users'],
                                   'translate_role' => TRUE,
                                   'role'           => array('>',10),
                                   'fields' => array('id_user','status',
                                                     'login','role',
                                                     'name','lastname')));      }   
}

?>
