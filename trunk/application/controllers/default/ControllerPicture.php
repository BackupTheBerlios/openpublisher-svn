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
 * ControllerPicture class
 *
 */

class ControllerPicture extends JapaControllerAbstractPage
{
    /**
     * Cache expire time in seconds for this view
     * 0 = cache disabled
     */
    public $cacheExpire = 0;
    
    /**
     * Execute the controller to show full size image
     */
    function perform()
    { 
        // template var with charset used for the html pages
        $this->viewVar['charset']   = & $this->config['charset'];
        // template var with css folder
        $this->viewVar['cssFolder'] = & $this->config['css_folder'];

        if(isset($this->dontPerform))
        {
            return;
        }
        
        // init template 'pic' variable 
        $this->viewVar['pic'] = array();
        
        // get requested picture content
        $this->model->action($this->module,'getPicture', 
                             array('result' => & $this->viewVar['pic'],
                                   'id_pic' => (int)$this->current_id_pic,
                                   'fields' => array('title','description',
                                                     'file','size','width','height',
                                                     'mime','media_folder')));
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
        $this->current_id_pic  = $this->httpRequest->getParameter('id_pic', 'get', 'int');
        $id_node = $this->httpRequest->getParameter('id_node', 'get', 'int');
        $id_article = $this->httpRequest->getParameter('id_article', 'get', 'int');
        $id_text = $this->httpRequest->getParameter('id_text', 'get', 'int');
              
        // fetch the current id_pic. If no id_pic defined or not numeric
        // this view class loads the error template
        if( false === $this->current_id_pic ) 
        {
            $this->view  = 'error';     
        }       

        // check wether to fetch a picture from the
        // navigation, article or misc module
        // 
        if( false !== $id_node ) 
        {
            $this->module  = 'navigation';     
        } 
        elseif( false !== $id_article ) 
        {
            $this->module  = 'article';     
        } 
        elseif( false !== $id_text ) 
        {
            $this->module  = 'misc';     
        }        
        else 
        {
            $this->view      = 'error'; 
            $this->dontPerform  = TRUE; 
        }  
        
        $this->viewVar['module'] = $this->module;
        
        // filter action of the common module to prevent browser caching
        $this->model->action( 'common', 'filterDisableBrowserCache');    
    }
}

?>