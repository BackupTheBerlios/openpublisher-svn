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
 * ViewUserMap
 *
 */
 
class ViewUserMap extends JapaControllerAbstractPage
{
   /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'map';
    
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
        // get the opener module
        if(isset($_REQUEST['openerModule']))
        {
            $this->tplVar['mod'] = (string)$_REQUEST['openerModule'];
            $this->tplVar['opener_url_vars'] = '&adduser=1&' . base64_decode((string)$_REQUEST['opener_url_vars']);
        }
        else
        {
            $this->tplVar['mod'] = 'user';
            $this->tplVar['opener_url_vars'] = '';
        }
        
        $this->tplVar['users'] = array();
        
        // assign template variable with users
        $this->model->action('user', 'getUsers',
                             array('result'         => & $this->tplVar['users'],
                                   'translate_role' => TRUE,
                                   'role'           => array('>',10),
                                   'fields' => array('id_user','status',
                                                     'login','role',
                                                     'name','lastname')));      }   
}

?>
