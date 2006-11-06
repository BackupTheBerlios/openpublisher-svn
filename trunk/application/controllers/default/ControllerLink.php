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
 * ViewLink class
 *
 */

class ControllerLink extends JapaControllerAbstractPage
{
    /**
     * Cache expire time in seconds for this view
     * 0 = cache disabled
     */
    public $cacheExpire = 300;
    
    /**
     * Execute the view of the "link" template
     */
    function perform()
    { 
        // init variables (see private function below)
        $this->initVars();
        
        // dont proceed if an error occure
        if(isset( $this->dontPerform ))
        {
            return;
        }
        
         // get requested node content
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->viewVar['node'],
                                   'id_node' => (int)$this->current_id_node,
                                   'status'  => array('>=',2),
                                   'fields'  => array('title','body','id_node','media_folder')));
         
        // get child nodes content of the requested node
        // only with status=2, means active      
        $this->model->action('navigation','getChilds', 
                             array('result'  => & $this->viewVar['childNodes'],
                                   'id_node' => (int)$this->current_id_node,
                                   'status'  => array('>=',2),
                                   'fields'  => array('title','short_text','id_node')));
 
        // get navigation node branch content of the requested node
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->viewVar['nodeBranch'],
                                   'id_node' => (int)$this->current_id_node,
                                   'fields'  => array('title','id_node')));  
                                 
        // get node related links
        $this->model->action('link','getLinks', 
                             array('result'  => & $this->viewVar['links'],
                                   'id_node' => (int)$this->current_id_node,
                                   'status'  => array('=','2'),
                                   'fields'  => array('id_link','id_node',
                                                      'title','url','description')));   
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
        
        // fetch the current id_node. If no id_node defined or not numeric
        // this view class loads the error template
        if( !isset($_REQUEST['id_node']) || is_array($_REQUEST['id_node']) || preg_match("/[^0-9]+/",$_REQUEST['id_node']) ) 
        {
            $this->template          = 'error';  
            $this->viewVar['message'] = "Wrong id_node value";
            $this->dontPerform       = TRUE;
            return;
        }
        else
        {
            $this->current_id_node    = (int)$_REQUEST['id_node'];          
        }
        
        // check if the demanded node has at least status 2
        $nodeStatus = $this->model->action('navigation','getNodeStatus', 
                                            array('id_node' => (int)$this->current_id_node));  
        
        // if the requested node isnt active
        if( $nodeStatus < 2 )
        {
            $this->template          = 'error'; 
            $this->viewVar['message'] = "The requested node isnt accessible";
            $this->dontPerform       = TRUE;
            // disable caching
            $this->cacheExpire = 0;
        } 
        // if the requested node is only available for registered users
        elseif( ($nodeStatus == 3) && ($this->viewVar['isUserLogged'] == FALSE) )
        {
              // set url vars to come back to this page after login
              $this->model->session->set('url','id_node='.$this->current_id_node);
              // switch to the login page
              @header('Location: '.SMART_CONTROLLER.'?view=login');
              exit;
        }
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

    /**
     * init some variables
     *
     */    
    private function initVars()
    {
        // template array variables
        $this->viewVar['node']         = array();
        $this->viewVar['childNodes']   = array();
        $this->viewVar['nodeBranch']   = array();
        $this->viewVar['links']        = array();
        
        // template var with charset used for the html pages
        $this->viewVar['charset'] = & $this->config['charset'];
        // relative path to the smart directory
        $this->viewVar['relativePath'] = SMART_RELATIVE_PATH;
        // template var with css folder
        $this->viewVar['cssFolder'] = & $this->config['css_folder'];

        // we need this template vars to show admin links if the user is logged
        $this->viewVar['loggedUserRole']      = $this->viewVar['loggedUserRole'];
        $this->viewVar['adminWebController']  = $this->config['admin_web_controller'];        
    }
}

?>