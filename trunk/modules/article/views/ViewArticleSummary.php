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
 * ViewArticleSummary
 *
 */
 
class ViewArticleSummary extends SmartView
{
   /**
     * template for this view
     * @var string $template
     */
    public $template = 'summary';
    
   /**
     * template folder for this view
     * @var string $template_folder
     */    
    public $templateFolder = 'modules/article/templates/'; 
        
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
                             array('result'  => & $this->tplVar['art_pubdate'], 
                                   'limit'   => array('perPage' => 10,
                                                      'numPage' => 1),  
                                   'order'   => array('pubdate','desc'),
                                   'fields'  => array('id_article','title','id_node',
                                                      'status','pubdate') )); 

        // get node + node branch of each article
        foreach($this->tplVar['art_pubdate'] as & $article)
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
        }
        
        // get last modified articles                                                    
        $this->model->action('article','select',
                             array('result'  => & $this->tplVar['art_modifydate'], 
                                   'limit'   => array('perPage' => 10,
                                                      'numPage' => 1), 
                                   'order'   => array('modifydate','desc'),
                                   'fields'  => array('id_article','title','id_node',
                                                      'status','modifydate') )); 

        // get node + node branch of each article
        foreach($this->tplVar['art_modifydate'] as & $article)
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
                                           'by_id_user' => (int)$this->viewVar['loggedUserId']) );
                                           
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
        $this->tplVar['art_pubdate']    = array();
        $this->tplVar['art_modifydate'] = array();
    }
}

?>