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
 * ViewArticleMain
 *
 */
 
class ViewArticleSearch extends JapaControllerAbstractPage
{
   /**
     * template for this view
     * @var string $template
     */
    public $template = 'search';
    
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

        // search articles                                                   
        $this->model->action('article','search',
                             array('result'  => & $this->tplVar['articles'], 
                                   'search'  => (string)$this->searchString,
                                   'limit'   => array('perPage' => $this->articlesPerPage,
                                                      'numPage' => (int)$this->pageNumber),                                   
                                   'fields'  => array('id_article','title','status',
                                                      'id_node','pubdate','modifydate') )); 

        // get node + node branch of each article
        foreach($this->tplVar['articles'] as & $article)
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
                                       
            // if author is logged check if he has access to edit articles
            $this->assignArticleRights( $article );
        }
                                   
        // create article pager links
        $this->model->action('article','pager', 
                             array('result'     => & $this->tplVar['pager'],
                                   'search'     => (string)$this->searchString,
                                   'perPage'    => $this->articlesPerPage,
                                   'numPage'    => (int)$this->pageNumber,
                                   'delta'      => 10,
                                   'url'        => $this->pagerUrl,
                                   'var_prefix' => 'search_',
                                   'css_class'  => 'search_pager'));          

        // get article locks
        $this->getLocks();
    }  
    
     /**
     * assign template variables with lock status of each article
     *
     */   
    private function getLocks()
    {
        $row = 0;
        
        foreach($this->tplVar['articles'] as $article)
        {
            // lock the user to edit
            $result = $this->model->action('article','lock',
                                     array('job'        => 'is_locked',
                                           'id_article' => (int)$article['id_article'],
                                           'by_id_user' => (int)$this->viewVar['loggedUserId']) );
                                           
            if(($result !== TRUE) && ($result !== FALSE))
            {
                $this->tplVar['articles'][$row]['lock'] = TRUE;  
            } 
            else
            {
                $this->tplVar['articles'][$row]['lock'] = FALSE;  
            }
            
            $row++;
        }    
    }   
     /**
     * init variables for this view
     *
     */      
    private function initVars()
    {
        if( isset($_POST['search']) )
        {
            $this->searchString = SmartCommonUtil::stripSlashes((string)$_POST['search']);
            $this->pagerUrlSearchString = urlencode(SmartCommonUtil::stripSlashes((string)$_POST['search']));
        }
        elseif( isset($_GET['search']) )
        {
            $this->searchString = urldecode(SmartCommonUtil::stripSlashes((string)$_GET['search']));
            $this->pagerUrlSearchString = SmartCommonUtil::stripSlashes((string)$_GET['search']);
        }        
        else
        {
            $this->searchString = '';
            $this->pagerUrlSearchString = '';
        }
        
        // assign template variable with search string
        $this->tplVar['search'] = & $this->searchString;
        
        // template array variables
        $this->tplVar['articles'] = array();
        $this->tplVar['pager']    = '';

        // set articles limit per page
        $this->articlesPerPage = 15;
        
        // get current article pager page
        if(isset($_GET['search_page']))
        {
            $this->pageNumber = (int)$_GET['search_page'];
            $this->tplVar['search_page'] = (int)$_GET['search_page'];
            $this->model->session->set('article_page', (int)$_GET['search_page']);        
        }
        elseif(NULL !== ($search_page = $this->model->session->get('search_page')))
        {
            $this->pageNumber = $search_page;
            $this->tplVar['search_page'] = $search_page;
        }        
        else
        {
            $this->pageNumber = 1;
            $this->tplVar['search_page'] = 1;
            $this->model->session->set('search_page', 1);
        } 
        
        // The url passed to the pager action
        $this->pagerUrl = SMART_CONTROLLER.'?nodecoration=1&mod=article&view=search&search='.$this->pagerUrlSearchString;    
             
        // set article order
        if(isset($_POST['order']))
        {
            $this->order = array((string)$_POST['order'],(string)$_POST['ordertype']);
            $this->tplVar['order'] = (string)$_POST['order']; 
            $this->tplVar['ordertype'] = (string)$_POST['ordertype'];
            $this->model->session->set('article_order', (string)$_POST['order']);
            $this->model->session->set('ordertype', (string)$_POST['ordertype']);
            $this->model->session->del('article_page');
        }
        elseif(NULL !== ($order = $this->model->session->get('article_order')))
        {
            $ordertype = $this->model->session->get('ordertype');
            $this->order = array($order,$ordertype);
            $this->tplVar['order'] = $order;
            $this->tplVar['ordertype'] = (string)$ordertype;
        }        
        else
        {
            $this->order = array($this->model->config['article']['default_order'],
                                 $this->model->config['article']['default_ordertype']);
            $this->tplVar['order'] = $this->model->config['article']['default_order'];
            $this->tplVar['ordertype'] = $this->model->config['article']['default_ordertype'];
            $this->model->session->set('article_order', 
                                       $this->model->config['article']['default_order']);
            $this->model->session->set('ordertype', 
                                       $this->model->config['article']['default_ordertype']);
        }
    }
    
     /**
     * if author (60) is logged assign rights to edit articles
     *
     * @param array $article
     */      
    private function assignArticleRights( & $article )
    {
        // if author is logged check if he has access to edit articles
        if($this->viewVar['loggedUserRole'] < 60 )
        {
            $article['hasAccess'] = true;
            return;
        }
        $article['hasAccess'] = $this->model->action('article','checkUserRights',
                                    array('id_article' => (int)$article['id_article'],
                                          'id_user'    => (int)$this->viewVar['loggedUserId']));
    }    
    
     /**
     * has the logged user the rights to modify?
     * at least edit (60) rights are required
     *
     */      
    private function allowModify()
    {      
        if($this->viewVar['loggedUserRole'] < 100 )
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