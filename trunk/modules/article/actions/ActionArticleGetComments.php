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
 * ActionArticleComments class 
 * USAGE:
 *
 * $model->action('article','comments',
 *                array('result'      => & array,
 *                      'status'      => array('>|<|=|>=|<=|!=',1|2|3|4|5), // optional
 *                      'node_status' => array('>|<|=|>=|<=|!=',1|2|3), // optional
 *                      'pubdate' => array('>|<|=|>=|<=|!=', 'CURRENT_TIMESTAMP'),
 *                      'limit'   => array('perPage' => int,
 *                                         'numPage' => int),
 *                      'order'   => 'asc'|'desc',// optional
 *                      'fields   => array('id_node','id_article','status','rank',
 *                                         'activedate','inactivedate','pubdate',
 *                                         'lang','title','overtitle',
 *                                         'subtitle','header','description',
 *                                         'body','ps','fulltextbody',
 *                                         'format','media_folder') ));
 *
 */


class ActionArticleGetComments extends SmartAction
{
    /**
     * Allowed sql caching
     */
    protected $sqlCache = 'SQL_CACHE';
    
    /**
     * Allowed article fields and its type
     */
    protected $tblFields = array('id_article' => 'Int',
                                 'id_comment' => 'Int',
                                 'id_user'    => 'Int',
                                 'status'     => 'Int',
                                 'pubdate'    => 'String',
                                 'body'       => 'String',
                                 'author'     => 'String',
                                 'email'      => 'String',
                                 'url'        => 'String',
                                 'ip'         => 'String',
                                 'agent'      => 'String');

    /**
     * get articles data of a given id_node
     *
     * @param array $data
     * @return bool true or false on error
     */
    public function perform( $data = FALSE )
    {
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.'ac.`'.$f.'`';
            $comma = ',';
        }
        
        if(isset($data['status']))
        {
            $sql_where = "ac.`status`{$data['status'][0]}{$data['status'][1]}";
        }
        else
        {
            $sql_where = "ac.`status`=2";
        }

        if(isset($data['id_article']))
        {
            $sql_where .= "AND ac.`id_article`={$data['id_article']}";
        }
        
        if(isset($data['pubdate']))
        {
            $sql_pubdate = " AND ac.`pubdate`{$data['pubdate'][0]}{$data['pubdate'][1]}";
        }
        else
        {
            $sql_pubdate = "";
        }  
        
        if(isset($data['order']))
        {
            $sql_order = " ORDER BY ac.`{$data['order'][0]}` {$data['order'][1]}";
        }
        else
        {
            $sql_order = "ORDER BY ac.`pubdate` DESC";
        }        

        if(isset($data['article_status']))
        {
            $article_table       = ",{$this->config['dbTablePrefix']}article_article AS aa";
            $sql_article_status  = "AND aa.`id_article`=ac.`id_article` "; 
            $sql_article_status .= "AND aa.`status`{$data['article_status'][0]}{$data['article_status'][1]} "; 
        }
        else
        {
            $article_table      = "";
            $sql_article_status = "";
        }

        if(isset($data['node_status']))
        {
            $node_table       = ",{$this->config['dbTablePrefix']}navigation_node AS nn";
            $sql_node_status  = "AND nn.`id_node`=ac.`id_node` "; 
            $sql_node_status .= "AND nn.`status`{$data['node_status'][0]}{$data['node_status'][1]} "; 
        }
        else
        {
            $node_table     = "";
            $sql_node_status = "";
        }

        if(isset($data['limit']))
        { 
            if( $data['limit']['numPage'] < 1 )
            {
                $data['limit']['numPage'] = 1;
            }        
            $numPage = ($data['limit']['numPage'] - 1) * $data['limit']['perPage'];
            $sql_limit = " LIMIT {$numPage},{$data['limit']['perPage']}";
        }
        else
        {
            $sql_limit = "";
        }   
        
        $sql = "
            SELECT {$this->sqlCache}
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}article_comment AS ac
                {$article_table}
                {$node_table}
            WHERE
                {$sql_where}
                {$sql_pubdate}
                {$sql_article_status}
                {$sql_node_status}
                {$sql_order}
                {$sql_limit}";

        $rs = $this->model->dba->query($sql);
        
        while($row = $rs->fetchAssoc())
        {
            // adjust gmt+0 date to user date
            if(isset($row['pubdate']))
            {
                $this->gmtToUserGmt( $row['pubdate'] );
            }
            
            $data['result'][] = $row;
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
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        foreach($data['fields'] as $val)
        {
            if(!isset($this->tblFields[$val]))
            {
                throw new SmartModelException("Field '".$val."' dosent exists!");
            }
        }

        if(!isset($data['result']))
        {
            throw new SmartModelException('Missing "result" array var: '); 
        }

        if(isset($data['limit']))
        {        
            if(!isset($data['limit']['numPage']))
            {
                throw new SmartModelException('numPage" isnt defined'); 
            } 
            if(!is_int($data['limit']['numPage']))
            {
                throw new SmartModelException('numPage" isnt from type int'); 
            }             
            if(!isset($data['limit']['perPage']))
            {
                throw new SmartModelException('"perPage" isnt defined'); 
            } 
            if(!is_int($data['limit']['perPage']))
            {
                throw new SmartModelException('"perPage" isnt from type int'); 
            }  
            elseif( $data['limit']['perPage'] < 2 )
            {
                throw new SmartModelException('"perPage" must be >= 2');
            }
        }
        
        if(isset($data['status']))
        {
            if(!is_array($data['status']))
            {
                throw new SmartModelException('"status" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['status'][0]))
                {
                    throw new SmartModelException('Wrong "status" array[0] value: '.$data['status'][0]); 
                }

                if(!isset($data['status'][1]) || preg_match("/[^0-9]+/",$data['status'][1]))
                {
                    throw new SmartModelException('Wrong "status" array[1] value: '.$data['status'][1]); 
                }
            }
        }

        if(isset($data['date_order']))
        {
            if(!is_string($data['date_order']))
            {
                throw new SmartModelException('"date_order" action array instruction isnt from type string'); 
            }
            else
            {
                if(isset($data['date_order']))
                {
                    if(!preg_match("/asc|desc/i",$data['date_order']))
                    {
                        throw new SmartModelException('Wrong "date_order"  value: '.$data['date_order']); 
                    }
                }
            }
        }

        if(isset($data['disable_sql_cache']))
        {
            if(!preg_match("/^SQL_NO_CACHE$/",$data['disable_sql_cache']))
            {
                throw new SmartModelException('Wrong "disable_sql_cache" string value: '.$data['disable_sql_cache']); 
            }
            $this->sqlCache = 'SQL_NO_CACHE';
        }
        
        if(isset($data['pubdate']))
        {
            if(!is_array($data['pubdate']))
            {
                throw new SmartModelException('"pubdate" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['pubdate'][0]))
                {
                    throw new SmartModelException('Wrong "pubdate" array[0] value: '.$data['pubdate'][0]); 
                }

                if(!isset($data['pubdate'][1]) || !preg_match("/^CURRENT_TIMESTAMP$/i",$data['pubdate'][1]))
                {
                    throw new SmartModelException('Wrong "pubdate" array[1] value: '.$data['pubdate'][1]); 
                }
            }
            $this->sqlCache = 'SQL_NO_CACHE';
        }

        if(isset($data['article_status']))
        {
            if(!is_array($data['article_status']))
            {
                throw new SmartModelException('"article_status" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['article_status'][0]))
                {
                    throw new SmartModelException('Wrong "article_status" array[0] value: '.$data['article_status'][0]); 
                }

                if(!isset($data['article_status'][1]) || preg_match("/[^0-9]+/",$data['article_status'][1]))
                {
                    throw new SmartModelException('Wrong "article_status" array[1] value: '.$data['article_status'][1]); 
                }
            }
        }

        if(isset($data['node_status']))
        {
            if(!is_array($data['node_status']))
            {
                throw new SmartModelException('"node_status" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['node_status'][0]))
                {
                    throw new SmartModelException('Wrong "node_status" array[0] value: '.$data['node_status'][0]); 
                }

                if(!isset($data['node_status'][1]) || preg_match("/[^0-9]+/",$data['node_status'][1]))
                {
                    throw new SmartModelException('Wrong "node_status" array[1] value: '.$data['node_status'][1]); 
                }
            }
        }
        
        return TRUE;
    }  
    
    private function gmtToUserGmt( & $_date )
    {        
        // convert date from gmt+0 to user timezone 
        $this->model->action('common', 'gmtConverter',
                             array('action'   => 'gmtToDate',
                                   'date'     => & $_date ));
    }
}

?>
