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
 * ControllerArticleSummary
 *
 */
 
class ControllerArticleSummary extends JapaControllerAbstractPage
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
        // init variables for this view
        $this->initVars();

        // get last published articles                                                   
        $this->model->action('article','select',
                             array('result'  => & $this->viewVar['art_pubdate'], 
                                   'limit'   => array('perPage' => 15,
                                                      'numPage' => 1),  
                                   'order'   => array('pubdate','desc'),
                                   'fields'  => array('id_article','title','id_node',
                                                      'status','pubdate') )); 

        // get node + node branch of each article
        foreach($this->viewVar['art_pubdate'] as & $article)
        {
            $article['nodeBranch'] = array();
            $article['node']       = array();
            
            // get navigation node branch content of the article node
            $this->model->action('navigation','getBranch', 
                             array('result'  => & $article['nodeBranch'],
                                   'id_node' => (int)$article['id_node'],
                                   'fields'  => array('title','id_node','id_parent')));   
                                   
            // get article node content
            $this->model->action('navigation','getNode', 
                                 array('result'  => & $article['node'],
                                       'id_node' => (int)$article['id_node'],
                                       'fields'  => array('title','id_node')));
            $this->getLock( $article );    
            
            // if author is logged check if he has access to edit articles
            $this->assignArticleRights( $article );
        }
        
        // get last modified articles                                                    
        $this->model->action('article','select',
                             array('result'  => & $this->viewVar['art_modifydate'], 
                                   'limit'   => array('perPage' => 15,
                                                      'numPage' => 1), 
                                   'order'   => array('modifydate','desc'),
                                   'fields'  => array('id_article','title','id_node',
                                                      'status','modifydate') )); 

        // get node + node branch of each article
        foreach($this->viewVar['art_modifydate'] as & $article)
        {
            $article['nodeBranch'] = array();
            $article['node']       = array();
            
            // get navigation node branch content of the article node
            $this->model->action('navigation','getBranch', 
                             array('result'  => & $article['nodeBranch'],
                                   'id_node' => (int)$article['id_node'],
                                   'fields'  => array('title','id_node','id_parent')));   
                                   
            // get article node content
            $this->model->action('navigation','getNode', 
                                 array('result'  => & $article['node'],
                                       'id_node' => (int)$article['id_node'],
                                       'fields'  => array('title','id_node')));
            $this->getLock( $article );
            
            // if author is logged check if he has access to edit articles
            $this->assignArticleRights( $article );
        }                   
    }  

     /**
     * assign template variables with lock status of each article
     *
     */   
    private function getLock( & $article )
    {
        // lock the user to edit
        $result = $this->model->action('article','lock',
                                     array('job'        => 'is_locked',
                                           'id_article' => (int)$article['id_article'],
                                           'by_id_user' => (int)$this->controllerVar['loggedUserId']) );
                                           
        if(($result !== TRUE) && ($result !== FALSE))
        {
            $article['lock'] = TRUE;  
        } 
        else
        {
            $article['lock'] = FALSE;  
        }  
    } 
     
     /**
     * init variables for this view
     *
     */      
    private function initVars()
    {
        // template array variables
        $this->viewVar['art_pubdate']    = array();
        $this->viewVar['art_modifydate'] = array();
    }
    
     /**
     * if author (60) is logged assign rights to edit articles
     *
     * @param array $article
     */      
    private function assignArticleRights( & $article )
    {
        // if author is logged check if he has access to edit articles
        if($this->controllerVar['loggedUserRole'] < 60 )
        {
            $article['hasAccess'] = true;
            return;
        }
        $article['hasAccess'] = $this->model->action('article','checkUserRights',
                                    array('id_article' => (int)$article['id_article'],
                                          'id_user'    => (int)$this->controllerVar['loggedUserId']));
    }    
    
     /**
     * has the logged user the rights to modify?
     * at least edit (60) rights are required
     *
     */      
    private function allowModify()
    {      
        if($this->controllerVar['loggedUserRole'] < 100 )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

?>