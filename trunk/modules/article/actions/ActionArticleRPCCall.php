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
 * ActionNavigationGetNodeStatus class 
 *
 * USAGE:
 * $node_status = $model->action('navigation','getNodeStatus',
 *                               array('id_node' => int))
 *
 */
 
class ActionArticleGetArticlesRPC extends SmartAction
{   
    /**
     * get node status
     *
     * @param array $data
     * @return node status id or FALSE
     */
    function perform( $data = FALSE )
    {
        // map methode related node date fields
        $methodeField = array('latestModified'  => 'modifydate',
                              'latestPublished' => 'pubdate');

        // start rpc client
        $client = new XML_RPC_Client("{$data['domainPath']}{$data['rpcServer']}", $data['domain'], $data['port']); 
        //$client->setDebug(1);

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
                $data['result'][$i]['date'] = $rec->structmem($methodeField[$methode]);
                $data['result'][$i]['date'] = $article_date->scalarval();
        
                $data['result'][$i]['overtitle'] = $rec->structmem('overtitle');
                $data['result'][$i]['overtitle'] = $overtitle->scalarval();
        
                $data['result'][$i]['id_article'] = $rec->structmem('id_article');
                $data['result'][$i]['id_article'] = $id_article->scalarval();
        
                $data['result'][$i]['title']      = $rec->structmem('title');
                $data['result'][$i]['title']      = $title->scalarval();
                
                $data['result'][$i]['subtitle'] = $rec->structmem('subtitle');
                $data['result'][$i]['subtitle'] = $subtitle->scalarval();
        
                $data['result'][$i]['description'] = $rec->structmem('description');
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

        return TRUE;
    }
}

?>
