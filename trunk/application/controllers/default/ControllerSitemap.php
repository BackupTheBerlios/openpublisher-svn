<?php
// ----------------------------------------------------------------------
// Open Publisher CMS
// Copyright (c) 2006
// by Armand Turpel < cms@open-publisher.net >
// http://www.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE LGPL
// http://www.gnu.org/licenses/lgpl.html
// ----------------------------------------------------------------------

/**
 * ControllerSitemap
 *
 */

class ControllerSitemap extends JapaControllerAbstractPage
{
    /**
     * Cache expire time in seconds
     * 0 = cache disabled
     */
    public $cacheExpire = 3600;
    
    /**
     * Execute the controller of the "sitemap" view
     */
    function perform()
    {     
        $this->initVars();
        
        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => 0,
                                   'result'  => & $this->viewVar['tree'],
                                   'status'  => array('>=', 2),
                                   'fields'  => array('id_parent','status','id_node','title')));   
                                   
        // get result of the header and footer controller
        //       
        $this->viewVar['header']      = $this->controllerLoader->header();
        $this->viewVar['footer']      = $this->controllerLoader->footer();  
        $this->viewVar['rightBorder'] = $this->controllerLoader->rightBorder(); 
    }

    /**
     * authentication
     *
     */
    public function auth()
    {
        // Check if the visitor is a logged user
        //
        if(NULL == ($this->viewVar['loggedUserId'] = $this->model->session->get('loggedUserId')))
        {
            $this->viewVar['isUserLogged'] = FALSE; 
        }
        else
        {
            $this->viewVar['isUserLogged'] = TRUE;
        }
        $this->viewVar['loggedUserRole'] = $this->model->session->get('loggedUserRole');     
        
        if( ($this->viewVar['isUserLogged'] == TRUE) && ($this->viewVar['loggedUserRole'] < 100) )
        {
            $this->cacheExpire = 0;
            $this->viewVar['showEditLink'] = TRUE; 
        }
    }

    /**
     * init some variables
     *
     */    
    private function initVars()
    {
        // view array that containsthe whole node tree
        $this->viewVar['tree'] = array();
        
        // view vars
        $this->viewVar['charset'] = $this->config->getModuleVar('common', 'charset');
        $this->viewVar['loggedUserRole']     = $this->viewVar['loggedUserRole'];
        $this->viewVar['adminWebController'] = $this->config->getVar('default_module_application_controller');        
        $this->viewVar['cssFolder'] = JAPA_PUBLIC_DIR . 'styles/'.$this->config->getModuleVar('common', 'styles_folder');
        $this->viewVar['urlBase']   = $this->router->getBase();
    }
}

?>