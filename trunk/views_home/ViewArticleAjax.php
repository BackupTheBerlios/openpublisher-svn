<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ViewArticleAjax class
 *
 * 
 *
 */

// NOTICE:
// An ajax view class extends the "SmartAjaxView" class !!!

class ViewArticleAjax extends SmartAjaxView
{
    /**
     * Registered Ajax methods
     * @var array $methods
     */
    public $methods = array('simpleText',
                            'showAlertBox',
                            'calculate',
                            'search');    
    
    /**
     * simpleText
     */    
    public function simpleText() 
    {       
        return 'This text was produced by the php methode "simpleText"';
    }
    
    /**
     * showAlertBox
     */     
    public function showAlertBox() 
    {  
        // an ajax action has to return the object !!!
        return $this->model->action('common','ajaxAction',
                                array('insertAlert' => 'This text was produced by the php method "showAlertBox"' ));               
    }
    
    /**
     * calculate
     * @param object $calculateObject Contains form vars
     * @return string Result or error string
     */   
    public function calculate( $calculateObject ) 
    {
        $error = FALSE;
        
        if(preg_match("/[^0-9]/",$calculateObject->number1) || empty($calculateObject->number1) )
        {
            $error = 'Field 1 is not numeric or empty! ';
        }
        if(preg_match("/[^0-9]/",$calculateObject->number2) || empty($calculateObject->number2) )
        {
            $error .= 'Field 2 is not numeric or empty!';
        }        
        
        if($error == FALSE)
        {
            return $calculateObject->number1 + $calculateObject->number2;
        }
        else
        {
            return $error;
        }
    }
    
    /**
     * search
     * @param object $searchObject Contains form vars
     * @return array Result or false
     */   
    public function search( $searchObject ) 
    {
        $searchResult = array();     

        // search articles                                                   
        $this->model->action('article','search',
                             array('result'     => & $searchResult, 
                                   'search'     => (string)$searchObject->search,
                                   'status'     => array('=', 4),
                                   'nodeStatus' => array('>=', 2),
                                   'order'      => array('title', 'ASC'),
                                   'pubdate' => array('<=', 'CURRENT_TIMESTAMP'),                                  
                                   'fields'  => array('id_node','id_article','title') ));       

        // get node + node branch of each article
        foreach($searchResult as & $article)
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
        }
        
        return $searchResult;
    }     
    
    /**
     * authentication
     * This methode is executed before any ajax methode.
     * You may add some authentication stuff here
     *
     */
    public function auth()
    {
    }

    /**
     * prepend filter chain
     * This methode is executed before any ajax methode and after auth().
     * You may add some filter stuff here
     */
    public function prependFilterChain()
    {
    }       
}

?>