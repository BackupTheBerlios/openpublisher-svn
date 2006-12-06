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
 * ControllerArticleAddArticle
 *
 */
 
class ControllerArticleAddArticle extends JapaControllerAbstractPage
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
        // Init error array
        $this->viewVar['error']  = array();
        $this->viewVar['tree']   = array();

        $this->id_node         = $this->httpRequest->getParameter('id_node', 'request', 'digits');
        $this->article_id_node = $this->httpRequest->getParameter('article_id_node', 'request', 'digits');
        $this->title           = trim($this->httpRequest->getParameter('title', 'request', 'raw'));
        $addarticle            = $this->httpRequest->getParameter('addarticle', 'post', 'alpha');

        if(!empty($this->id_node))
        {
            $this->viewVar['id_node'] = $this->id_node;
        }
        else
        {
            $this->viewVar['id_node'] = '0';
        }

        // add link
        if( !empty($addarticle) )
        {
            if(TRUE === $this->validate())
            {
                // check if id_node has changed
                if($this->id_node != $this->article_id_node)
                {
                    $id_node = $this->article_id_node;
                } 
                else
                {
                    $id_node = $this->id_node;
                }
                if(FALSE !== ($new_id_article = $this->addArticle( $id_node )))
                {
                    // lock this article
                    $this->model->action('article','lock',
                                         array('job'        => 'lock',
                                               'id_article' => (int)$new_id_article,
                                               'by_id_user' => (int)$this->controllerVar['loggedUserId']) );  

                    if($this->controllerVar['loggedUserRole'] >= 60)
                    {
                        // get demanded article data
                        $this->model->action('article','addUser', 
                                             array('id_user'     => (int)$this->controllerVar['loggedUserId'],
                                                   'id_article'  => (int)$new_id_article));
                    }
                    
                    // goto modarticle view
                    @header('Location: '.$this->controllerVar['url_base'].'/'.$this->viewVar['adminWebController'].'/mod/article/cntr/modArticle/id_node/'.$id_node.'/id_article/'.$new_id_article);
                    exit;
                }
            }
            $this->resetFormData();
        }                

        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => 0,
                                   'result'  => & $this->viewVar['tree'],
                                   'fields'  => array('id_parent','status','id_node','title')));   


        // set template variable that show the link to add users
        // only if the logged user have at least editor rights
        if($this->controllerVar['loggedUserRole'] <= 40)
        {
            $this->viewVar['showAddArticleLink'] = TRUE;
        }
        else
        {
            $this->viewVar['showAddArticleLink'] = FALSE;
        }
    }   
   /**
    * add new node
    *
    * @param int $id_node parent node of the new node
    */    
    private function addArticle($id_node )
    { 
        return $this->model->action('article', 'addArticle', 
                              array('id_node' => (int)$id_node,
                                    'id_user' => (int)$this->controllerVar['loggedUserId'],
                                    'error'   => & $this->viewVar['error'],
                                    'fields'  => array('title'  => JapaCommonUtil::stripSlashes((string)$this->title),
                                                       'status'  => 2,
                                                       'format'  => $this->config->getModuleVar('article','default_format'))));        
    }
    
    private function validate()
    {
        if( false === $this->id_node)
        {
            $this->viewVar['error'][] = '"id_node" isnt defined';
        }

        if($this->article_id_node == 0)
        {
            $this->viewVar['error'][] = '"Top" navigation node isnt allowed';
        }        
        // fetch the current id_node. If no node the script assums that
        // we are at the top level with id_parent 0
        if(false === $this->title) 
        {
            $this->viewVar['error'][] = 'Field "title" isnt defined';
        }
        elseif(!is_string($this->title))
        {
            $this->viewVar['error'][] = 'Field "title" isnt from type string';
        }   
        elseif(empty($this->title))
        {
            $this->viewVar['error'][] = 'Field "title" is empty';
        }             

        if(count($this->viewVar['error']) > 0)
        {
            return FALSE;
        }
        return TRUE;
    }
    /**
     * reset the form fields with old link data
     *
     * @access privat
     */       
    private function resetFormData()
    {
        $this->viewVar['title'] = htmlspecialchars ( JapaCommonUtil::stripSlashes((string)$this->title), ENT_COMPAT, $this->config->getModuleVar('common', 'charset') );
    }      
}

?>