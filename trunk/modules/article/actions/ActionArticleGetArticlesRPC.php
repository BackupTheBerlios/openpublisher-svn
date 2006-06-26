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
 * ActionArticleGetArticlesRPC class 
 *
 * Works only if you make calls to an Open Publisher RPC server.
 * You have to modify this class for other servers.
 *
 * USAGE:
        $this->model->action('article', 'getArticlesRPC',
                             array('result'      => & array,
                                   'domain'      => string,  // domain eg. 127.0.0.1
                                   'rpcServer'   => string,  // relative path to the rpcserver eg "/openpublisher/rpcserver.php"
                                   'query'       => string,  // additional query eg. "?view=xyz" // optional
                                   'port'        => int,     // port default = 80 // optional
                                   'authUser'    => string,  // username if required // optional
                                   'authPasswd'  => string,  // user password if required // optional
                                   'methode'     => string , // methode to use eg. 'latestPublished' or 'latestModified' // optional
                                   'numArticles' => int)     // num articles to fetch  // optional
                                   );        
 *
 */
 // include the PEAR XML_RPC client class
include_once('XML/RPC.php');

class ActionArticleGetArticlesRPC extends SmartAction
{   
    /**
     * get article data through rpc
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        // set default num articles to fetch
        if(!isset($data['numArticles']))
        {
            $data['numArticles'] = 8;
        }

        // set default port
        if(!isset($data['port']))
        {
            $data['port'] = 80;
        }

        // set default user
        if(!isset($data['query']))
        {
            $data['query'] = '';
        }

        // set default methode
        if(!isset($data['methode']))
        {
            $data['methode'] = 'latestPublished';
        }
        
        // set default user
        if(!isset($data['authUser']))
        {
            $data['authUser'] = '';
        }
        
        // set default user
        if(!isset($data['authPasswd']))
        {
            $data['authPasswd'] = '';
        }
        
        // methode map of article fields
        $methodeField = array('latestModified'  => 'modifydate',
                              'latestPublished' => 'pubdate');

        // start rpc client
        $client = new XML_RPC_Client($data['rpcServer'].$data['query'], $data['domain'], $data['port']); 
        
        if(isset($data['debug']))
        {
            $client->setDebug(1);
        }

        // set rpc methode and parameters
        $msg = new XML_RPC_Message($data['methode'], 
                     array( new XML_RPC_Value($data['authUser'],    "string"),
                            new XML_RPC_Value($data['authPasswd'],  "string"),
                            new XML_RPC_Value($data['numArticles'], "int") ) );
                            
        $response = $client->send($msg);
  
        if(!$response->faultCode())
        {
            $data['error'] = false;
            
            $content  = $response->value();
            $max = $content->arraysize(); 
    
            for($i=0; $i<$max; $i++) 
            {
                // get element of the array
                $rec = $content->arraymem($i);

                // get the associative array value
                $article_date = $rec->structmem($methodeField[$data['methode']]);
                $data['result'][$i]['date'] = $article_date->scalarval();
        
                $overtitle = $rec->structmem('overtitle');
                $data['result'][$i]['overtitle'] = $overtitle->scalarval();
        
                $id_article = $rec->structmem('id_article');
                $data['result'][$i]['id_article'] = $id_article->scalarval();
        
                $title      = $rec->structmem('title');
                $data['result'][$i]['title'] = $title->scalarval();
                
                $subtitle = $rec->structmem('subtitle');
                $data['result'][$i]['subtitle'] = $subtitle->scalarval();
        
                $description = $rec->structmem('description');
                $data['result'][$i]['description'] = $description->scalarval();
            }
        }
        else
        {
            $data['error'] = true;
            trigger_error('RPC call fault. \nCode: '.$response->faultCode().'\nReason: '.$response->faultString(), E_USER_WARNING);
        }    
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    {
        $data['error'] = false;
        
        if(!isset($data['result']))
        {
            trigger_error('Missing "result" array var in ' . __FILE__, E_USER_WARNING);
            $data['error'] = true;
        }

        if( isset($data['methode']) )
        {
            if(($data['methode'] != 'latestModified') && ($data['methode'] != 'latestPublished'))
            {
                trigger_error('unknown methode '.$data['methode'].' in ' . __FILE__, E_USER_WARNING); 
                $data['error'] = true;
            }
        }

        if(!isset($data['domain']))
        {
            trigger_error('Missing "domain" string var in ' . __FILE__, E_USER_WARNING); 
            $data['error'] = true;
        }

        if(!isset($data['rpcServer']))
        {
            trigger_error('Missing "rpcServer" string var in ' . __FILE__, E_USER_WARNING);
            $data['error'] = true;
        }
        
        if($data['error'] == true)
        {
            return false;
        }
        
        return true;
    }
}

?>
