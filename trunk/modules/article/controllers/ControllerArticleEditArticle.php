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
 * ControllerArticleEditArticle
 *
 * Article Status:
 * 0 = delete
 * 1 = cancel
 * 2 = propose
 * 3 = edit
 * 4 = publish
 * 5 = protect
 *
 */
 
class ControllerArticleEditArticle extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
      
   /**
     * current id_node
     * @var int $current_id_node
     */
    private $current_id_node;    
    
   /**
     * current id_article
     * @var int $current_id_article
     */
    private $current_id_article;   
    
   /**
     * execute the perform methode
     * @var bool $dontPerform
     */
    private $dontPerform = FALSE;  

   /**
     * user log message for this view
     * @var string $logMessage
     */    
    private $logMessage = '';
    
    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        $this->current_id_article = $this->httpRequest->getParameter('id_article', 'request', 'digits');
        
        // if no rights for the logged user, show error template
        if( FALSE == $this->allowModify() )
        {
            $this->redirect();
        }

        // init variables for this view
        if(FALSE == $this->initVars())
        {
            $this->redirect();         
        }

        // is node locked by an other user
        if( TRUE !== $this->lockArticle() )
        {
            $this->redirect();     
        }
    }        
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        if($this->dontPerform == TRUE)
        {
            return;
        }

        $gotonode = $this->httpRequest->getParameter('gotonode', 'post', 'alnum');

        // forward to node x without update
        if($gotonode != '')
        {
            $this->unlockArticle();
            $this->redirect((int)$gotonode);        
        }

        $canceledit = $this->httpRequest->getParameter('canceledit', 'post', 'digits');

        // change nothing and switch back
        if($canceledit == '1')
        {
            $this->unlockArticle();
            $this->redirect((int)$this->current_id_node);        
        }

        $modifyarticledata = $this->httpRequest->getParameter('modifyarticledata', 'post', 'alnum');
        
        // update article data
        if( !empty($modifyarticledata) )
        {      
            $this->updateArticleData();
        }

        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => 0,
                                   'result'  => & $this->viewVar['tree'],
                                   'fields'  => array('id_parent','status',
                                                      'id_node','title')));   

        // article fields to get
        $articleFields = array('id_article','title','pubdate',
                               'status','allow_comment','close_comment');
                               
        // add fields depended on configuration settings
        $this->addGetArticleFields( $articleFields );

        // get demanded article data
        $this->model->action('article','getArticle', 
                             array('result'     => & $this->viewVar['article'],
                                   'id_article' => (int)$this->current_id_article,
                                   'get_controller' => TRUE,
                                   'error'      => & $this->viewVar['error'],
                                   'fields'     => $articleFields));

        // assign template date variables
        $this->assignTemplateDates();       

        // get current node data
        $this->model->action('navigation','getNode', 
                             array('result'  => & $this->viewVar['node'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','id_node')));    
    
        // get navigation node branch of the current node
        $this->model->action('navigation','getBranch', 
                             array('result'  => & $this->viewVar['branch'],
                                   'id_node' => (int)$this->current_id_node,
                                   'error'   => & $this->viewVar['error'],
                                   'fields'  => array('title','id_node')));                             

        // we need the url vars to open this page by the keyword map window
        if($this->config->getModuleVar('article','use_keywords') == 1)
        {
            $addkey = $this->httpRequest->getParameter('addkey', 'request', 'alnum');
            if(!empty($addkey))
            {
                $this->addKeyword();
            }
            $this->getArticleKeywords();
        }
        
        // we need the url vars to open this page by the keyword map window
        if($this->config->getModuleVar('article','use_article_controller') == 1)
        {
            // get all available registered article public views
            $this->viewVar['articlePublicControllers'] = array();
            $this->model->action( 'article','getPublicControllers',
                                  array('result' => &$this->viewVar['articlePublicControllers'],
                                        'fields' => array('id_controller','name')) );           
       } 
       
       if($this->config->getModuleVar('article','use_comment') == 1)
       {
          $this->viewVar['articleComments'] = array();
          
          $this->model->action('article','comments',
                               array('result' => & $this->viewVar['articleComments'],
                                     'id_article' => (int)$this->current_id_article,
                                     'status' => array('>=', 0),
                                     'fields' => array('id_comment','status',
                                                       'pubdate','body','id_user',
                                                       'author','email','url',
                                                       'ip','agent') ));   
       }  
       
       if($this->config->getModuleVar('user','use_log') == 1)
       {
           $this->viewVar['showLogLink'] = 1;
       }

       $adduser = $this->httpRequest->getParameter('adduser', 'request', 'alnum');
       if(!empty($adduser))
       {
           $this->addUser();
       }
       
       // get user of this article
       $this->viewVar['articleUsers'] = array();
       $this->model->action('article','getArticleUsers', 
                            array('result'     => & $this->viewVar['articleUsers'],
                                  'id_article' => (int)$this->current_id_article,
                                  'order'      => array('lastname','asc'),
                                  'fields'     => array('id_user','role',
                                                        'login','lastname',
                                                        'name','email'))); 
                                                        
        // get url rewrite for this node
        $this->viewVar['url_rewrite'] = array();
        $this->model->action( 'common', 'getUrlRewrite',     
                              array('result'        => & $this->viewVar['url_rewrite'],       
                                    'module'        => 'article',
                                    'request_value' => (int)$this->current_id_article) ); 
    }  

   /**
    * Update article data
    *
    */
    private function updateArticleData()
    {   
        if(count($this->viewVar['error']) == 0)
        {
            // get the node ID of this article
            $this->getNewIdNode();

            if($this->config->getModuleVar('article','use_article_controller') == 1)
            {
                $this->updateArticleController();
            }

            $this->deleteArticleKeywords();
            $this->deleteArticleUsers();
            $this->updateArticle();
            $this->urlRewrite();
            $this->addLogEvent( 3 );

            $refresh = $this->httpRequest->getParameter('refresh', 'post', 'alnum');
            
            if(empty($refresh))
            {
                $this->unlockArticle();           
                $this->redirect( $this->current_id_node );
            }
        }    
    }
     /**
     * lock this article
     *
     */   
    private function lockArticle()
    {
        return $this->model->action('article','lock',
                array('job'        => 'lock',
                      'id_article' => (int)$this->current_id_article,
                      'by_id_user' => (int)$this->controllerVar['loggedUserId']) );  
    }   
     /**
     * init variables for this view
     *
     */      
    private function initVars()
    {
        $id_node = $this->httpRequest->getParameter('id_node', 'request', 'digits');

        // get node Id of the demanded article
        if(false === $id_node)
        {
                return FALSE;
        } 
        $this->current_id_node = (int)$id_node;                 

        // get article ID
        if(false === $this->current_id_article)
        {
            return FALSE;
        } 

        // template variables
        //
        // article data
        $this->viewVar['id_article'] = $this->current_id_article;
        $this->viewVar['id_node']    = $this->current_id_node;
        
        // node tree data
        $this->viewVar['tree']   = array();
        // data of the current node
        $this->viewVar['node']   = array();
        // data of the branch nodes
        $this->viewVar['branch'] = array();  
        // article data
        $this->viewVar['article']  = array();
       
        // errors
        $this->viewVar['error']  = array();   

        $this->viewVar['use_comment']  = $this->config->getModuleVar('article','use_comment'); 
        $this->viewVar['use_url_rewrite'] = $this->config->getModuleVar('article', 'use_url_rewrite');
        

        // assign view config vars
        $this->viewVar['use_changedate']         = $this->config->getModuleVar('article', 'use_changedate');
        $this->viewVar['use_articledate']        = $this->config->getModuleVar('article', 'use_articledate');
        $this->viewVar['use_article_controller'] = $this->config->getModuleVar('article', 'use_article_controller');
        

        // we need the url vars to open this page by the keyword map window
        if($this->config->getModuleVar('article','use_keywords') == 1)
        {
            $this->viewVar['opener_url_vars'] = base64_encode('/cntr/editArticle/id_article/'.$this->current_id_article.'/id_node/'.$this->current_id_node.'/disableMainMenu=1');
        }
        $this->viewVar['use_keywords'] = $this->config->getModuleVar('article','use_keywords');
        
        return TRUE;
    }
     /**
     * has the logged user the rights to modify article data?
     * at least 'edit' (40) rights are required
     * or author (60) is assigned to this article
     *
     * @return bool
     */      
    private function allowModify()
    {      
        if($this->controllerVar['loggedUserRole'] < 60 )
        {
            return $this->allowModify = true;
        }
        elseif(($this->controllerVar['loggedUserRole'] >= 60) &&
               ($this->controllerVar['loggedUserRole'] < 100))
        {
            return $this->allowModify = $this->model->action('article','checkUserRights',
                                        array('id_article' => (int)$this->current_id_article,
                                              'id_user'    => (int)$this->controllerVar['loggedUserId']));
        }
        
        return $this->allowModify = false;
    }
 
    /**
     * Update article data
     *
     */
    private function updateArticle()
    {
        $this->getNewIdNode();

        $status = $this->httpRequest->getParameter('status', 'post', 'digits');
        
        $articleFields = array('id_node'  => (int)$this->current_id_node,
                               'status'   => (int)$status,
                               'pubdate'  => $this->buildDate('pubdate'));

        if( $this->config->getModuleVar('article','use_comment') == 1 )
        {
            $allow_comment = $this->httpRequest->getParameter('allow_comment', 'post', 'digits');
            $close_comment = $this->httpRequest->getParameter('close_comment', 'post', 'digits');
            
            if(!empty($allow_comment))
            {
                $articleFields['allow_comment'] = 1;
            }
            else
            {
                $articleFields['allow_comment'] = 0;
            }
            if(!empty($close_comment))
            {
                $articleFields['close_comment'] = 1;
            }
            else
            {
                $articleFields['close_comment'] = 0;
            }            
            
            $this->updateComments();
        }

        if(isset($this->node_has_changed))
        {
            $articleFields['rank'] = $this->getLastRank( $this->current_id_node );
        }

        // add fields depended on configuration settings
        $this->addSetArticleFields( $articleFields ); 
        
        $this->model->action('article','updateArticle',
                             array('id_article' => (int)$this->current_id_article,
                                   'error'      => &$this->viewVar['error'],
                                   'fields'     => $articleFields));    

        $this->addLogMessage( "Updated fields: \n". var_export( $articleFields, true ) );

        if(isset($this->node_has_changed))
        {
            $this->reorderRank( $this->current_id_node );
        }
    }
    
    /**
     * build datetime
     *
     */    
    private function buildDate( $_date )
    {
        $year   = $this->httpRequest->getParameter($_date.'_year', 'post', 'digits');
        $month  = $this->httpRequest->getParameter($_date.'_month', 'post', 'digits');
        $day    = $this->httpRequest->getParameter($_date.'_day', 'post', 'digits');
        $hour   = $this->httpRequest->getParameter($_date.'_hour', 'post', 'digits');
        $minute = $this->httpRequest->getParameter($_date.'_minute', 'post', 'digits');
        
        return $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute.':00';
    }
        
    /**
     * Redirect to the article node
     */
    private function redirect( $id_node = 0 )
    {
        $this->router->redirect($this->viewVar['adminWebController'].'/mod/article/id_node/'.$id_node);  
    }  
    
    /**
     * unlock article
     *
     */     
    private function unlockArticle()
    {
        $this->model->action('article','lock',
                             array('job'        => 'unlock',
                                   'id_article' => (int)$this->current_id_article));    
    }    
    
    /**
     * assignTemplateDates
     *
     */      
    private function assignTemplateDates()  
    {                   
        if( isset($this->viewVar['article']['pubdate']) )
        {
            if( preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})/",
                           $this->viewVar['article']['pubdate'], $d) )
            {
                $this->viewVar['article']['pubdate'] = array();
                $this->viewVar['article']['pubdate']['year']   = $d[1]; 
                $this->viewVar['article']['pubdate']['month']  = $d[2];
                $this->viewVar['article']['pubdate']['day']    = $d[3];
                $this->viewVar['article']['pubdate']['hour']   = $d[4];
                $this->viewVar['article']['pubdate']['minute'] = $d[5];
            }
        }
        if( isset($this->viewVar['article']['articledate']) )
        {
            if( preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})/",
                           $this->viewVar['article']['articledate'], $d2) )
            {
                $this->viewVar['article']['articledate'] = array();
                $this->viewVar['article']['articledate']['year']   = $d2[1]; 
                $this->viewVar['article']['articledate']['month']  = $d2[2];
                $this->viewVar['article']['articledate']['day']    = $d2[3];
                $this->viewVar['article']['articledate']['hour']   = $d2[4];
                $this->viewVar['article']['articledate']['minute'] = $d2[5];
            }
        }  
        if( isset($this->viewVar['article']['changedate']) )
        {
            if( preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})/",
                           $this->viewVar['article']['changedate'], $d3) )
            {
                $this->viewVar['article']['changedate'] = array();
                $this->viewVar['article']['changedate']['year']   = $d3[1]; 
                $this->viewVar['article']['changedate']['month']  = $d3[2];
                $this->viewVar['article']['changedate']['day']    = $d3[3];
                $this->viewVar['article']['changedate']['hour']   = $d3[4];
                $this->viewVar['article']['changedate']['minute'] = $d3[5];
                $this->viewVar['article']['changestatus']         = (int)$this->viewVar['article']['changestatus'];
                $this->viewVar['cd_disable'] = FALSE;
            }
        }
        elseif($this->config->getModuleVar('article','use_changedate') == 1)
        {
                $this->viewVar['article']['changedate'] = array();
                $this->viewVar['article']['changedate']['year']   = date("Y", time()); 
                $this->viewVar['article']['changedate']['month']  = date("m", time());
                $this->viewVar['article']['changedate']['day']    = date("d", time());
                $this->viewVar['article']['changedate']['hour']   = date("H", time());
                $this->viewVar['article']['changedate']['minute'] = date("i", time());  
                $this->viewVar['article']['changestatus']         = 1;
                $this->viewVar['cd_disable'] = TRUE;
        }
    }

    /**
     * add article fields to get depended on the configuration settings
     *
     */     
    private function addGetArticleFields( & $articleFields )
    {
        if($this->config->getModuleVar('article','use_articledate') == 1)
        {
            array_push($articleFields, 'articledate');
        }
        if($this->config->getModuleVar('article','use_changedate') == 1)
        {
            array_push($articleFields, 'changedate');
        }        
    }

    /**
     * set article field values depended on the configuration settings
     *
     */      
    private function addSetArticleFields( & $articleFields )
    {
        if($this->config->getModuleVar('article','use_articledate') == 1)
        {
            $articleFields['articledate'] = $this->buildDate('articledate');
        }
        if(($this->config->getModuleVar('article','use_changedate') == 1))
        {
            $changedate_year = $this->httpRequest->getParameter('changedate_year', 'post', 'alnum');
            if(empty($changedate_year))
            {
                $articleFields['changedate'] = FALSE;
            }
            else
            {
                $changestatus = $this->httpRequest->getParameter('changestatus', 'post', 'int');
                $articleFields['changedate']   = $this->buildDate('changedate');
                $articleFields['changestatus'] = (int)$changestatus;
            }
        }        
    } 
    
    /**
     * get article node ID
     *
     */      
    private function getNewIdNode()
    {
        $this->article_id_node = $this->httpRequest->getParameter('article_id_node', 'post', 'digits');
        
        if($this->article_id_node != $this->current_id_node)
        {
            if($this->article_id_node !== '0')
            {
                $this->current_id_node   = (int)$this->article_id_node;
                $this->viewVar['id_node'] = $this->current_id_node;
                $this->node_has_changed = TRUE;
                $this->addLogMessage( "Change article node: {$this->current_id_node}" );
            }
        }
    } 
    
    /**
     * Get last rank of an given id_node
     *
     * @param int $id_node
     */    
    private function getLastRank( $id_node )
    {
        $rank = 0;
        $this->model->action('article','getLastRank',
                             array('id_node' => (int)$id_node,
                                   'result'  => &$rank ));

        if($rank > 0)
        {
            $rank++;
        }
        return $rank;
    }
    /**
     * reorder rank list when moving a node
     *
     * @param int $id_node
     */      
    private function reorderRank( $id_node )
    {
        $this->model->action('article','reorderRank',
                             array('id_node' => (int)$id_node));
    }    
    /**
     * reorder rank list when moving a node
     *
     * @param int $id_node
     */      
    private function addKeyword()
    {
        $id_key = $this->httpRequest->getParameter('id_key', 'request', 'digits');
        
        // get demanded article data
        $this->model->action('article','addKeyword', 
                             array('id_key'     => (int)$id_key,
                                   'id_article' => (int)$this->current_id_article));
        $this->addLogMessage( "Add article keyword: {$id_key}" );
    }  

    /**
     * reorder rank list when moving a node
     *
     * @param int $id_node
     */      
    private function addUser()
    {
        if($this->allowModify == false)
        {
            return;
        }
        
        $id_user = $this->httpRequest->getParameter('id_user', 'request', 'digits');
        
        // get demanded article data
        $this->model->action('article','addUser', 
                             array('id_user'     => (int)$id_user,
                                   'id_article'  => (int)$this->current_id_article));
        $this->addLogMessage( "Add article user: {$id_user}" );
    }  
    
    /**
     * reorder rank list when moving a node
     *
     * @param int $id_node
     */      
    private function getArticleKeywords()
    {
        $this->viewVar['keys'] = array();
        
        $keywords = array();
        
        // get demanded article data
        $this->model->action('article','getKeywordIds', 
                             array('result'     => & $keywords,
                                   'id_article' => (int)$this->current_id_article));

        foreach($keywords as $key)
        {
            $tmp = array();
            $tmp['id_key'] = $key; 
            
            $keyword = array();
            $this->model->action('keyword','getKeyword', 
                                 array('result' => & $keyword,
                                       'id_key' => (int)$key,
                                       'fields' => array('title','id_key')));          
            $branch = array();
            // get navigation node branch of the current node
            $this->model->action('keyword','getBranch', 
                                 array('result'  => & $branch,
                                       'id_key' => (int)$key,
                                       'fields'  => array('title','id_key')));                 

            $tmp['branch'] = '';
            
            foreach($branch as $bkey)
            {
                $tmp['branch'] .= '/'.$bkey['title'];
            }
            
            $tmp['branch'] .= '/<strong>'.$keyword['title'].'</strong>';
            
            $this->viewVar['keys'][] = $tmp;
        }
        sort($this->viewVar['keys']);    
    }   
    /**
     * remove article keyword relations
     *
     */     
    private function deleteArticleKeywords()
    {
        $id_key = $this->httpRequest->getParameter('id_key', 'request', 'raw');
        
        if((false !== $id_key) && is_array($id_key))
        {
            foreach($id_key as $id)
            {
                // get navigation node branch of the current node
                $this->model->action('article','removeKeyword', 
                                 array('id_key'     => (int)$id,
                                       'id_article' => (int)$this->current_id_article)); 
                                       
                $this->addLogMessage( "Remove article keyword: {$id}" );
            }
        }
    }
    
    /**
     * remove article users relations
     *
     */     
    private function deleteArticleUsers()
    {
        // authors have no rights to remove article users
        if($this->controllerVar['loggedUserRole'] >= 60)
        {
            return;
        }

        $id_user = $this->httpRequest->getParameter('id_user', 'request', 'raw');
        
        if((false !== $id_user) && is_array($id_user))
        {
            foreach($id_user as $id)
            {
                // get navigation node branch of the current node
                $this->model->action('article','removeUser', 
                                 array('id_user'     => (int)$id,
                                       'id_article'  => (int)$this->current_id_article)); 
                                       
                $this->addLogMessage( "Remove article user: {$id}" );
            }
        }
    }
    /**
     * update article related view
     *
     */      
    private function updateArticleController()
    {
        $article_controller = $this->httpRequest->getParameter('article_controller', 'request', 'int');
        if($article_controller != 0)
        {
            $this->model->action( 'article','updateController',
                                  array('id_article'    => (int)$this->current_id_article,
                                        'id_controller' => (int)$article_controller) );
        }
        else
        {
            $this->model->action( 'article','removeArticleControllerRelation',
                                  array('id_article' => (int)$this->current_id_article) );        
        }
    } 
    
    /**
     * update article related view
     *
     */      
    private function updateComments()
    {
        $id_comment_val = $this->httpRequest->getParameter('id_comment_val', 'request', 'raw');
        
        if((false !== $id_comment_val) && is_array($id_comment_val))
        {
            foreach($id_comment_val as $id)
            {
                $this->model->action( 'article','updateComment',
                                      array('id_comment' => (int)$id,
                                            'fields'     => array('status' => 2) ) ); 
                                            
                $this->addLogMessage( "Validate article comment: {$id}" );
            }
        }
        
        $id_comment_del = $this->httpRequest->getParameter('id_comment_del', 'request', 'raw');
        
        if((false !== $id_comment_del) && is_array($id_comment_del))
        {
            foreach($id_comment_del as $id)
            {
                $this->model->action( 'article','deleteComment',
                                      array('id_comment' => (int)$id ));
                                      
                $this->addLogMessage( "Delete article comment: {$id}" );
            }
        }
    } 

    /**
     * log events of this view
     *
     * for $type values see: /modules/user/actions/ActionUserLogAddEvent.php
     *
     * @param int $type 
     */     
    private function addLogEvent( $type )
    {
        // dont log
        if($this->config->getModuleVar('user','use_log') == 0)
        {
            return;
        }
        
        $this->model->action('user','logAddEvent',
                             array('type'    => $type,
                                   'id_item' => (int)$this->current_id_article,
                                   'module'  => 'article',
                                   'controller' => 'editArticle',
                                   'message' => $this->logMessage ));
    }
    
    /**
     * log events of this view
     *
     * for $type values see: /modules/user/actions/ActionUserLogAddEvent.php
     *
     * @param int $type 
     */     
    private function urlRewrite()
    {
        $url_rewrite = trim($this->httpRequest->getParameter('url_rewrite', 'post', 'regex', "/[a-z0-9\.-_]{1,255}/i"));
        $id_map = $this->httpRequest->getParameter('id_map', 'post', 'digits');
        
        if($url_rewrite !== false)
        {
            if($id_map == 0)
            {
                $this->model->action('common','addUrlRewrite',
                                     array( 'module'        => 'article',
                                            'request_name'  => (string)$url_rewrite,
                                            'request_value' => (int)$this->current_id_article) );    
            }  
            else
            {
                $this->model->action('common','updateUrlRewrite',
                                     array( 'id_map'       => (int)$id_map,
                                            'request_name' => (string)$url_rewrite) );    
            }   
        }   
        elseif(($url_rewrite === false) && ($id_map > 0))
        {
                $this->model->action('common','removeUrlRewrite',
                                     array( 'module' => 'article',
                                            'id_map' => (int)$id_map) );    
        }     
    }
    
    /**
     * add log message string
     *
     *
     * @param string $message 
     */  
    private function addLogMessage( $message = '' )
    {
        // dont log
        if($this->config->getModuleVar('user','use_log') == 0)
        {
            return;
        }
        $this->logMessage .= $message."\n";
    }
}

?>