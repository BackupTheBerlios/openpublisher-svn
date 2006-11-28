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
 * ViewIndex class
 *
 * The parent variables are:
 * $tplVar  - Array that may contains template variables
 * $viewVar - Array that may contains view variables, which
 *            are needed by some followed nested views.
 * $model   - The model object
 *            We need it to call modules actions
 * $template - Here you can define an other template name as the default
 * $renderTemplate - Is there a template associated with this view?
 *                   JAPA_TEMPLATE_RENDER or JAPA_TEMPLATE_RENDER_NONE
 * $viewData - Data passed to this view by the caller
 * $cacheExpire - Expire time in seconds of the cache for this view. 0 means cache disabled
 */

class ControllerIndex extends JapaControllerAbstractPage
{
    /**
     * Cache expire time in seconds for this view
     * 0 = cache disabled
     */
    public $cacheExpire = 3600;
    
    /**
     * Execute the view of the "index" template
     */
    public function perform()
    {
        // template var with charset used for the html pages
        $this->viewVar['charset']   = & $this->config['charset'];
        // template var with css folder
        $this->viewVar['cssFolder'] = JAPA_PUBLIC_DIR . 'styles/default/';
        
        // relative path to the smart directory
        $this->viewVar['relativePath'] = JAPA_PUBLIC_DIR;
        // template var with css folder
        $this->viewVar['cssFolder'] = JAPA_PUBLIC_DIR . 'styles/default/';
        $this->viewVar['urlBase'] = $this->router->getBase();
        $this->viewVar['urlCss'] = 'http://'.$this->router->getHost().$this->viewVar['urlBase'].'/'.$this->viewVar['cssFolder'];     
        // we need this template vars to show admin links if the user is logged
        $this->viewVar['loggedUserRole']     = $this->controllerVar['loggedUserRole'];
        $this->viewVar['isUserLogged']       = $this->controllerVar['isUserLogged'];
        $this->viewVar['adminWebController'] = 'Module'; 
        
        $this->viewVar['text']    = array();

        // get text for the front page
        $this->model->action('misc','getText', 
                             array('id_text' => 1,
                                   'result'  => & $this->viewVar['text'],
                                   'fields'  => array('body')));  
                                
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
            $this->controllerVar['isUserLogged'] = FALSE; 
        }
        else
        {
            $this->controllerVar['isUserLogged'] = TRUE;
        }
        
        $this->controllerVar['loggedUserRole'] = $this->model->session->get('loggedUserRole');
        
        // set tpl var to show edit link if user is logged
        if( ($this->controllerVar['isUserLogged'] == TRUE) && ($this->controllerVar['loggedUserRole'] < 100) )
        {
            $this->viewVar['showEditLink'] = TRUE; 
        }
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
     * append filter chain
     *
     */
    public function appendFilterChain( & $outputBuffer )
    {
        // filter action of the common module that trims the html output
        // $this->model->action( 'common', 'filterTrim', array('str' => & $outputBuffer) );        
    }
}

?>