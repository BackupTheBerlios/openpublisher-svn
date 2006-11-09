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
 * ViewArticle class
 *
 */

class ControllerRpcServeArticles extends JapaControllerRpcAbstractPage
{  
    /**
     * verify client user and password.
     * @return bool
     */
    private function rpcAuth( &$params )
    {
        $user   = $params->getParam(0);
        $user   = $user->scalarval();
        $passwd = $params->getParam(1);
        $passwd = $passwd->scalarval();
        
        return $this->model->action( 'user','checkLogin',
                                      array('login'  => (string)$user,
                                            'passwd' => (string)$passwd));
    }
    /**
     * Execute this view
     */
    public function perform()
    {
        $server = new XML_RPC_Server(
                  array(
                        "latestPublished" =>
                          array("function"  => array(&$this,'latestPublished'),
                                "signature" => array(array('boolean','string','string','int')),
                                "docstring" => 'Return the x latest published articles'),
                            
                        "latestModified" =>
                          array("function"  => array(&$this,'latestModified'),
                                "signature" => array(array('boolean','string','string','int')),
                                "docstring" => 'Return the x latest modified articles')
                        ));    
    }
    /**
     * get latest published articles
     *
     * @param object $params Client parameters
     * @return object XML RPC response
     */    
    public function latestPublished( &$params )
    {
        /*
        if(!$this->rpcAuth( $params ))
        {
            return new XML_RPC_Response(0, $GLOBALS['XML_RPC_erruser']+1, 'Registered user required');
        }
        */
        $this->latestArticles( $params, 'pubdate' );
        return new XML_RPC_Response( $this->val );
    }
    /**
     * get latest modifies articles
     *
     * @param object $params Client parameters
     * @return object XML RPC response
     */    
    public function latestModified( &$params )
    {
     /*
        if(!$this->rpcAuth( $params ))
        {
            return new XML_RPC_Response(0, $GLOBALS['XML_RPC_erruser']+1, 'Registered user required');
        }
        */
        $this->latestArticles( $params, 'modifydate' );
        return new XML_RPC_Response( $this->val );
    } 
    /**
     * get latest published/modifies articles
     *
     * @param object $params Client parameters
     * @param string $date_field Date field of the article to fetch
     */     
    private function latestArticles( &$params, $date_field )
    {
        $this->viewVar['latest_articles'] = array();
        
        $numArticles = $params->getParam(2);
        $numArticles = (int)$numArticles->scalarval();
        if( $numArticles < 2 )
        {
            $numArticles = 2;
        }

        // get last published/modified articles                                                   
        $this->model->action('article','select',
                             array('result'  => & $this->viewVar['latest_articles'], 
                                   'limit'   => array('perPage' => (int)$numArticles,
                                                      'numPage' => 1),  
                                   'order'   => array($date_field, 'desc'),
                                   'status'  => array('=', 4),
                                   'fields'  => array('id_article','title',
                                                      'overtitle','subtitle',
                                                      'description',$date_field) ));

        $this->addArray( $date_field );        
    }  
    /**
     * add articles content as xml_rpc struct array
     *
     * @param string $date_field Date field of the article to fetch
     */     
    private function addArray( &$date_field )
    {
        $content = array();
        
        foreach($this->viewVar['latest_articles'] as $val)
        {
            $struct = array();
            $struct['id_article']  = new XML_RPC_Value($val['id_article'], 'int');
            $struct['title']       = new XML_RPC_Value($val['title'],      'string');
            $struct['overtitle']   = new XML_RPC_Value($val['overtitle'],  'string');
            $struct['subtitle']    = new XML_RPC_Value($val['subtitle'],   'string');
            $struct['description'] = new XML_RPC_Value($val['description'],'string');
            $struct[$date_field]   = new XML_RPC_Value($val[$date_field],  'string');
            
            $content[] = new XML_RPC_Value($struct, 'struct');
        }
        $this->val = new XML_RPC_Value();
        $this->val->addArray($content);
    }
}

?>