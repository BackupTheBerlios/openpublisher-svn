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
 * ControllerKeywordAddKeyword
 *
 */
 
class ControllerKeywordAddKeyword extends JapaControllerAbstractPage
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
        // init template array to fill with node data
        $this->viewVar['title']  = '';
        $this->viewVar['branch'] = array();  
        $this->viewVar['childs'] = array();
        // Init template form field values
        $this->viewVar['error']  = FALSE;

        $id_key = $this->httpRequest->getParameter('id_key', 'request', 'int');

        // fetch the current id_key. If no node the script assums that
        // we are at the top level with id_parent 0
        if( false === $id_key ) 
        {
            $this->viewVar['id_key']  = 0;
            $id_key = 0;
        }
        else
        {
            $this->viewVar['id_key']  = $id_key;
            $id_key = (int)$id_key;
        }

        $addkeyword = $this->httpRequest->getParameter('addkeyword', 'post', 'alnum');
        
        // add node
        if( !empty($addkeyword) )
        {
            if(FALSE !== ($new_id_key = $this->addKeyword( $id_key )))
            {
                $this->router->redirect( $this->controllerVar['adminWebController'] . '/mod/keyword/cntr/editKeyword/id_key/'.$new_id_key ); 
            }
        }
        
        // assign the template array $B->tpl_nodes with navigation nodes
        $this->model->action('keyword', 'getChilds', 
                             array('id_key'  => (int)$id_key,
                                   'status'  => array('>=', 0),
                                   'fields'  => array('id_key','title','status'),
                                   'result'  => & $this->viewVar['childs'],
                                   'error'   => & $this->viewVar['error']));
                 
        // assign the template array $B->tpl_nodes with navigation nodes
        $this->model->action('keyword','getBranch', 
                             array('result'  => & $this->viewVar['branch'],
                                   'id_key'  => (int)$id_key,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','id_key')));                 

        // set template variable that show the link to add users
        // only if the logged user have at least editor rights
        if($this->controllerVar['loggedUserRole'] <= 40)
        {
            $this->viewVar['showAddKeywordLink'] = TRUE;
        }
        else
        {
            $this->viewVar['showAddKeywordLink'] = FALSE;
        }
    }   
   /**
    * add new node
    *
    * @param int $id_parent parent node of the new node
    */    
    private function addKeyword( $id_parent )
    {
        $title = trim($this->httpRequest->getParameter('title', 'post', 'raw'));
        
        if( empty($title) )
        {
            $this->viewVar['error'] = 'Title is empty';
            return FALSE;
        }
        
        return $this->model->action('keyword', 'add', 
                             array('fields' => array('title'     => JapaCommonUtil::stripSlashes((string)$title),
                                                     'id_parent' => (int)$id_parent,
                                                     'status'    => 1)));        
    }
}

?>