<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ViewSimpleNode class
 * Every view must extends SmartView
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
 * $cacheExpire - Expire time in seconds of the cache for this view. = means cache disabled
 *
 */

class ViewSimpleNodeSmarty extends SmartView
{
    /**
     * Cache expire time in seconds
     * 0 = cache disabled
     */
    public $cacheExpire = 0;
        
    /**
     * Set Smarty as template engine
     */
    public $templateEngine = 'SmartTplContainerSmarty';
        
    /**
     * Execute the view of the "node" template
     */
    function perform()
    {     
        $this->initVars();
        
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->tplVar['node'],
                                   'id_node' => (int)$this->current_id_node,
                                   'fields'  => array('title','body')));                                 

        // get text for the page footer
        $this->tplVar['footer'] = array();
        $this->model->action('misc','getText', 
                             array('id_text' => 3,
                                   'result'  => & $this->tplVar['footer'],
                                   'fields'  => array('body')));  
                                 
        return TRUE;
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
        // fetch the current id_node. If no id_node defined or not numeric
        // this view class loads the error template
        if( !isset($_REQUEST['id_node']) || preg_match("/[^0-9]+/",$_REQUEST['id_node']) ) 
        {
            $this->template  = 'error';     
        }
        else
        {
            $this->current_id_node    = (int)$_REQUEST['id_node'];          
        }
        
        // filter action of the common module to prevent browser caching
        $this->model->action( 'common', 'filterDisableBrowserCache');    
    }

    /**
     * append filter chain
     *
     */
    public function appendFilterChain( & $outputBuffer )
    {
        // filter action of the common module that trims the html output
        $this->model->action( 'common', 'filterTrim', array('str' => & $outputBuffer) );        
    }

    /**
     * init some variables
     *
     */    
    private function initVars()
    {
        // template array variables
        $this->tplVar['node']       = array();
        
        $this->tplVar['relativePath'] = SMART_RELATIVE_PATH;
        
        $this->tplVar['includeHeader'] = '<?php $viewLoader->headerSmarty(); ?>';
        $this->tplVar['includeMainNavigationSmarty'] = '<?php $viewLoader->mainNavigationSmarty(); ?>';
        
        // template var with charset used for the html pages
        $this->tplVar['charset']   = & $this->config['charset'];
        // template var with css folder
        $this->tplVar['cssFolder'] = & $this->config['css_folder'];         
    }
}

?>