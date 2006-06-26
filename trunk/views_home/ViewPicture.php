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
 * ViewNodePicture class
 *
 * The parent variables are:
 * $tplVar  - Array that may contains template variables
 * $viewVar - Array that may contains view variables, which
 *            are needed by some followed nested views.
 * $model   - The model object
 *            We need it to call modules actions
 * $template - Here you can define an other template name as the default
 * $renderTemplate - Is there a template associated with this view?
 *                   SMART_TEMPLATE_RENDER or SMART_TEMPLATE_RENDER_NONE
 * $viewData - Data passed to this view by the caller
 * $cacheExpire - Expire time in seconds of the cache for this view. 0 means cache disabled
 */

class ViewPicture extends SmartView
{
    /**
     * Cache expire time in seconds for this view
     * 0 = cache disabled
     */
    public $cacheExpire = 3600;
    
    /**
     * Html template associated with this view
     */
    public $template = 'picture';
    
    /**
     * Execute the view of the "nodePicture" template
     */
    function perform()
    { 
        // template var with charset used for the html pages
        $this->tplVar['charset']   = & $this->config['charset'];
        // template var with css folder
        $this->tplVar['cssFolder'] = & $this->config['css_folder'];

        if(isset($this->dontPerform))
        {
            return;
        }
        
        // init template 'pic' variable 
        $this->tplVar['pic'] = array();
        
        // get requested picture content
        $this->model->action($this->module,'getPicture', 
                             array('result' => & $this->tplVar['pic'],
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
            $this->tplVar['isUserLogged'] = FALSE; 
        }
        else
        {
            $this->tplVar['isUserLogged'] = TRUE;
        }
        $this->viewVar['loggedUserRole'] = $this->model->session->get('loggedUserRole');     
    }

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // fetch the current id_pic. If no id_pic defined or not numeric
        // this view class loads the error template
        if( !isset($_REQUEST['id_pic']) || preg_match("/[^0-9]+/",$_REQUEST['id_pic']) ) 
        {
            $this->template  = 'error';     
        }       
        else
        {
            $this->current_id_pic    = (int)$_REQUEST['id_pic'];          
        }

        // check wether to fetch a picture from the
        // navigation, article or misc module
        // 
        if( isset($_REQUEST['id_node']) ) 
        {
            $this->module  = 'navigation';     
        } 
        elseif( isset($_REQUEST['id_article']) ) 
        {
            $this->module  = 'article';     
        } 
        elseif( isset($_REQUEST['id_text']) ) 
        {
            $this->module  = 'misc';     
        }        
        else 
        {
            $this->template     = 'error'; 
            $this->dontPerform  = TRUE; 
        }  
        
        $this->tplVar['module'] = $this->module;
        
        // filter action of the common module to prevent browser caching
        $this->model->action( 'common', 'filterDisableBrowserCache');    
    }
}

?>