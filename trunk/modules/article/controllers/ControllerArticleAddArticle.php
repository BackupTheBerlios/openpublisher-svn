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
 * ViewArticleAddArticle
 *
 */
 
class ViewArticleAddArticle extends JapaControllerAbstractPage
{
   /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'addarticle';
    
   /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public  $templateFolder = 'modules/article/templates/';
        
   /**
    * Perform on the main view
    *
    */
    public function perform()
    {
        // init template array to fill with node data
        $this->tplVar['title']  = '';
        // Init error array
        $this->tplVar['error']  = array();
        $this->tplVar['tree']   = array();

        if(isset($_REQUEST['id_node']))
        {
            $this->tplVar['id_node'] = $_REQUEST['id_node'];
        }
        else
        {
            $this->tplVar['id_node'] = '0';
        }

        // add link
        if( isset($_POST['addarticle']) )
        {
            if(TRUE === $this->validate())
            {
                // check if id_node has changed
                if($_REQUEST['id_node'] != $_REQUEST['article_id_node'])
                {
                    $id_node = $_REQUEST['article_id_node'];
                } 
                else
                {
                    $id_node = $_REQUEST['id_node'];
                }
                if(FALSE !== ($new_id_article = $this->addArticle( $id_node )))
                {
                    // lock this article
                    $this->model->action('article','lock',
                                         array('job'        => 'lock',
                                               'id_article' => (int)$new_id_article,
                                               'by_id_user' => (int)$this->viewVar['loggedUserId']) );  

                    if($this->viewVar['loggedUserRole'] >= 60)
                    {
                        // get demanded article data
                        $this->model->action('article','addUser', 
                                             array('id_user'     => (int)$this->viewVar['loggedUserId'],
                                                   'id_article'  => (int)$new_id_article));
                    }
                    
                    // goto modarticle view
                    @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER.'?mod=article&view=modArticle&id_node='.$id_node.'&id_article='.$new_id_article);
                    exit;
                }
            }
            $this->resetFormData();
        }                

        // get whole node tree
        $this->model->action('navigation','getTree', 
                             array('id_node' => 0,
                                   'result'  => & $this->tplVar['tree'],
                                   'fields'  => array('id_parent','status','id_node','title')));   


        // set template variable that show the link to add users
        // only if the logged user have at least editor rights
        if($this->viewVar['loggedUserRole'] <= 40)
        {
            $this->tplVar['showAddArticleLink'] = TRUE;
        }
        else
        {
            $this->tplVar['showAddArticleLink'] = FALSE;
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
                                    'id_user' => (int)$this->viewVar['loggedUserId'],
                                    'error'   => & $this->tplVar['error'],
                                    'fields'  => array('title'  => SmartCommonUtil::stripSlashes((string)$_POST['title']),
                                                       'status'  => 2,
                                                       'format'  => $this->config['article']['default_format'])));        
    }
    
    private function validate()
    {
        if(!isset($_REQUEST['id_node']))
        {
            $this->tplVar['error'][] = '"id_node" isnt defined';
        }

        if($_REQUEST['article_id_node'] == 0)
        {
            $this->tplVar['error'][] = '"Top" navigation node isnt allowed';
        }        
        // fetch the current id_node. If no node the script assums that
        // we are at the top level with id_parent 0
        if(!isset($_REQUEST['title'])) 
        {
            $this->tplVar['error'][] = 'Field "title" isnt defined';
        }
        elseif(!is_string($_REQUEST['title']))
        {
            $this->tplVar['error'][] = 'Field "title" isnt from type string';
        }   
        elseif(empty($_REQUEST['title']))
        {
            $this->tplVar['error'][] = 'Field "title" is empty';
        }             

        if(count($this->tplVar['error']) > 0)
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
        $this->tplVar['title'] = htmlspecialchars ( SmartCommonUtil::stripSlashes((string)$_POST['title']), ENT_COMPAT, $this->config['charset'] );
    }      
}

?>