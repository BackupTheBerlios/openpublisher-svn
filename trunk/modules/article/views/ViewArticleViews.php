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
 * ViewArticleViews
 *
 */
 
class ViewArticleViews extends SmartView
{
   /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'views';
    
   /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public  $templateFolder = 'modules/article/templates/';

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // if no rights for the logged user, show error template
        // Only administrators 
        if($this->viewVar['loggedUserRole'] > 20)
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            $this->tplVar['error'] = 'You have not the rights to access navigation node views!';
            $this->dontPerform = TRUE;
        }
    } 
    
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        if(isset($_REQUEST['register']))
        {
            if(isset($_POST['availableview']) && is_array($_POST['availableview']))
            {
                foreach($_POST['availableview'] as $name)
                {
                    $this->model->action('article','registerViews',
                                         array('action' => 'register',
                                               'name'   => (string)$name) );  
                }
            }
        }
        elseif(isset($_REQUEST['unregister']))
        {
            if(isset($_POST['registeredview']) && is_array($_POST['registeredview']))
            {
                foreach($_POST['registeredview'] as $id_view)
                {
                    $this->model->action('article','registerViews',
                                         array('action'  => 'unregister',
                                               'id_view' => (int)$id_view) );  
                }
            }
        }        
        
        // get all available public views
        $this->tplVar['availableViews'] = array();
        $this->model->action( 'common','getAllPublicViews',
                              array('result' => &$this->tplVar['availableViews']) );   
                                    
        // get all available public views
        $this->tplVar['registeredViews'] = array();
        $this->model->action( 'article','getPublicViews',
                              array('result' => &$this->tplVar['registeredViews'],
                                    'fields' => array('id_view','name')) );
        
        return TRUE;
    }   
    
    private function isRegisteredPublicView()
    {
        $this->current_reg_nodes = array();
        $this->model->action( 'article','getPublicViews',
                              array('result' => &$this->current_reg_nodes,
                                    'fields' => array('id_view','name')) );  
                                    
        foreach($_POST['view'] as $v_name)
        {
            foreach($this->current_reg_nodes as $_v)
            {
                if($v_name == $_v['name'])
                {
                    return $_v['id_view'];
                }
            }
        }
        return FALSE;
    }
}

?>