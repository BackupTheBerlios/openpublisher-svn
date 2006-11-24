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
 * ControllerArticleNodeRelatedPublicController class
 *
 */

class ControllerArticleNodeRelatedPublicController extends JapaControllerAbstractPage
{
    /**
     * this child controller return the view in order to echo
     * @var bool $returnView
     */
    public $returnView = true;
    
    /**
     * Execute the view
     *
     */
    public function perform()
    {
        $modifynodedata = $this->httpRequest->getParameter('modifynodedata', 'post', 'alnum');
        $id_node = $this->httpRequest->getParameter('id_node', 'request', 'digits');
        
        if( !empty($modifynodedata) )
        {
            $article_id_controller = $this->httpRequest->getParameter('article_id_controller', 'post', 'int');
            
            if($article_id_controller != 0)
            {
                $this->updateArticleNodeView( (int)$id_node, (int)$article_id_controller );
                
                // update subnodes
                $articleviewssubnodes = $this->httpRequest->getParameter('articleviewssubnodes', 'post', 'digits');
                
                if(!empty($articleviewssubnodes) && ($articleviewssubnodes == 1))
                {
                    // check if the nodeTree array was previously init by an other view
                    if( !isset($this->controllerVar['nodeTree']) )
                    {
                        $this->controllerVar['nodeTree'] = array();
                        $this->model->action('navigation','getTree',
                                             array('id_node' => (int)$id_node,
                                                   'result'  => &$this->controllerVar['nodeTree'], 
                                                   'status'  => array('>',0),
                                                   'fields'  => array('id_node','id_parent','status')));
                    }
                    foreach($this->controllerVar['nodeTree'] as $node)
                    {
                        $this->updateArticleNodeController( (int)$node['id_node'], (int)$article_id_controller );
                    }
                }
            }
        }        

        // get article associated public Controller
        $this->viewVar['articleAssociatedPublicController'] = array();
        $this->viewVar['articleAssociatedPublicController']['id_controller'] = 0;
        
        $this->model->action( 'article','getNodeAssociatedController',
                              array('result'  => &$this->viewVar['articleAssociatedPublicController'],
                                    'id_node' => (int)$id_node) );     
        
        // get all available registered article public Controllers
        $this->viewVar['articlePublicControllers'] = array();
        $this->model->action( 'article','getPublicControllers',
                              array('result' => &$this->viewVar['articlePublicControllers'],
                                    'fields' => array('id_controller','name')) );         
    }     

    /**
     * update node related article controller
     *
     */   
    private function updateArticleNodeController( $id_node, $id_controller )
    {
        $this->model->action( 'article','updateNodeView',
                              array('id_node'       => (int)$id_node,
                                    'id_controller' => (int)$id_controller) );     
    }
    
    
}

?>