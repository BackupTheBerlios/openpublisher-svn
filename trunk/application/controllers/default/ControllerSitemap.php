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
 * ViewSitemap
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
     * Execute the view of the "sitemap" template
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
    }

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {        
        // filter action of the common module to prevent browser caching
        $this->model->action( 'common', 'filterDisableBrowserCache');    
    }

    /**
     * init some variables
     *
     */    
    private function initVars()
    {
        // template array variables
        $this->viewVar['tree'] = array();
        
        // template var with charset used for the html pages
        $this->viewVar['charset']   = & $this->config['charset'];
        // template var with css folder
        $this->viewVar['cssFolder'] = & $this->config['css_folder'];
        
        // we need this template vars to show admin links if the user is logged
        $this->viewVar['loggedUserRole']      = $this->viewVar['loggedUserRole'];
        $this->viewVar['adminWebController']  = $this->config['admin_web_controller']; 
    }
}

?>