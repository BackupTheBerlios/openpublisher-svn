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
 * ActionArticleGetArticles class 
 * USAGE:
 *
 * $model->action('article','getArticles',
 *                array('result'      => & array,
 *                      'status'      => array('>|<|=|>=|<=|!=',1|2|3|4|5), // optional
 *                      'node_status' => array('>|<|=|>=|<=|!=',1|2|3), // optional
 *                      'pubdate' => array('>|<|=|>=|<=|!=', 'CURRENT_TIMESTAMP'),
 *                      'limit'   => array('perPage' => int,
 *                                         'numPage' => int),
 *                      'order'   => array('rank|title|
 *                                          articledate|pubdate|
 *                                          overtitle|subtitle', 'asc|desc'),// optional
 *                      'fields   => array('id_node','id_article','status','rank',
 *                                         'activedate','inactivedate','pubdate',
 *                                         'lang','title','overtitle',
 *                                         'subtitle','header','description',
 *                                         'body','ps','fulltextbody',
 *                                         'format','media_folder') ));
 *
 */


class ActionArticleGetArticles extends SmartAction
{
    /**
     * Allowed sql caching
     */
    protected $sqlCache = 'SQL_CACHE';
    
    /**
     * Allowed article fields and its type
     */
    protected $tblFields_article = array('id_article'   => 'Int',
                                         'id_node'      => 'Int',
                                         'status'       => 'Int',
                                         'rank'         => 'Int',
                                         'pubdate'      => 'String',
                                         'articledate'  => 'String',
                                         'modifydate'   => 'String',
                                         'lang'         => 'String',
                                         'title'        => 'String',
                                         'overtitle'    => 'String',
                                         'subtitle'     => 'String',
                                         'header'       => 'String',
                                         'description'  => 'String',
                                         'body'         => 'String',
                                         'ps'           => 'String',
                                         'format'       => 'Int',
                                         'logo'         => 'String',
                                         'media_folder' => 'String',
                                         'num_comments' => 'Int',
                                         'allow_comment' => 'Int',
                                         'close_comment' => 'Int');

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
        $get_num_comments = FALSE;

        // we need Id_article field
        if(!in_array('id_article', $data['fields'] ))
        {
            $data['fields'][] = 'id_article';
        }
        
        foreach ($data['fields'] as $f)
        {
            if($f == 'num_comments')
            {
                $get_num_comments = TRUE;
                continue;
            }
            // Modify dates depended on gmt+X settings
            if(($f == 'pubdate') || ($f == 'modifydate') || ($f == 'articledate'))
            {
                $_fields .= $comma."DATE_ADD(aa.`{$f}`,INTERVAL {$this->model->action('common', 'getGmtOffset')}  HOUR) AS `{$f}`";
            }
            else
            {
                $_fields .= $comma.'aa.`'.$f.'`';
            }
            $comma = ',';
        }
        
        if(isset($data['status']))
        {
            $sql_where = " AND aa.`status`{$data['status'][0]}{$data['status'][1]}";
        }
        else
        {
            $sql_where = "aa.`status`>=4";
        }
        
        if(isset($data['pubdate']))
        {
            if($data['pubdate'][1] == "CURRENT_TIMESTAMP")
            {
                $_date = $this->config['gmtDate'];
            }
            else
            {
                $_date = $data['pubdate'][1];
            }
            $sql_pubdate = " AND aa.`pubdate`{$data['pubdate'][0]}'{$_date}'";
        }
        else
        {
            $sql_pubdate = "";
        }  

        if(isset($data['modifydate']))
        {
            $sql_modifydate = " AND aa.`modifydate`{$data['modifydate'][0]}{$data['modifydate'][1]}()";
        }
        else
        {
            $sql_modifydate = "";
        }  

        if(isset($data['exclude']))
        {
            $exclude = implode(",", $data['exclude']);
            $sql_exclude = " AND aa.`id_article` NOT IN($exclude)";
        }
        else
        {
            $sql_exclude = "";
        }

        if(isset($data['exclude_node']))
        {
            $exclude_node = implode(",", $data['exclude_node']);
            $sql_exclude_node = " AND aa.`id_node` NOT IN($exclude_node)";
        }
        else
        {
            $sql_exclude_node = "";
        }

        if(isset($data['exclude_sector']))
        {
            $exclude_sector = implode(",", $data['exclude_sector']);
            $sql_exclude_sector = " AND nn.`id_sector` NOT IN($exclude_sector)";
        }
        else
        {
            $sql_exclude_sector = "";
        }
        
        if(isset($data['exclude_article']))
        {
            $exclude_article = implode(",", $data['exclude_article']);
            $sql_exclude_article = " AND aa.`id_article` NOT IN({$exclude_article})";
        }
        else
        {
            $sql_exclude_article = "";
        }
        
        if(isset($data['order']))
        {
            if(preg_match("/rand/i",$data['order'][0]))
            {
                $sql_order = " ORDER BY RAND()";
            }
            else
            {
                $sql_order = " ORDER BY aa.`{$data['order'][0]}` {$data['order'][1]}";
            }
        }
        else
        {
            $sql_order = "ORDER BY aa.`title` ASC";
        }        

        if(isset($data['id_article']))
        {
            $id_article = implode(",", $data['id_article']);
            $sql_where .= " AND aa.`id_article` IN({$id_article})";
        }
        else
        {
            $sql_where .= "";
        }

        if(isset($data['id_node']))
        {
            $nodes = implode(",", $data['id_node']);
            $sql_where .= " AND aa.`id_node` IN({$nodes})";
        }
        else
        {
            $sql_where .= "";
        }

        if(isset($data['id_sector']))
        {
            $sectors = implode(",", $data['id_sector']);
            $sql_where .= " AND nn.`id_sector` IN({$sectors})";
        }
        else
        {
            $sql_where .= "";
        }

        if(isset($data['node_status']))
        {
            $sql_node_where = "AND nn.`status`{$data['node_status'][0]}{$data['node_status'][1]} "; 
        }
        else
        {
            $sql_node_where = "";
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
                {$this->config['dbTablePrefix']}article_article AS aa,
                {$this->config['dbTablePrefix']}navigation_node AS nn
            WHERE
                aa.`id_node`=nn.`id_node`
                {$sql_where} 
                {$sql_node_where}
                {$sql_exclude}
                {$sql_exclude_node}
                {$sql_exclude_sector}
                {$sql_exclude_article}
                {$sql_pubdate}
                {$sql_modifydate}
                {$sql_order}
                {$sql_limit}";

        $rs = $this->model->dba->query($sql);
        
        while($row = $rs->fetchAssoc())
        {
            if($get_num_comments == TRUE)
            {
                $row['num_comments'] = $this->getNumComments( (int)$row['id_article'] );
            }
            
            if(isset($data['author']))
            {
                $row['authors'] = $this->getAuthors( $row['id_article'], $data );
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
            if(!isset($this->tblFields_article[$val]))
            {
                throw new SmartModelException("Field '".$val."' dosent exists!");
            }
        }

        if(!isset($data['result']))
        {
            throw new SmartModelException('Missing "result" array var: '); 
        }

        if(isset($data['id_node']))
        {
            if(is_array($data['id_node']))
            {
                foreach($data['id_node'] as $id_node)
                {
                    if(!is_int($id_node))
                    {
                        throw new SmartModelException('"id_node" array value isnt from type int: '.$id_node); 
                    }
                }
            }
            else
            {
                throw new SmartModelException('"id_node" isnt from type array: '); 
            }
        }    

        if(isset($data['id_sector']))
        {
            if(is_array($data['id_sector']))
            {
                foreach($data['id_sector'] as $id_sector)
                {
                    if(!is_int($id_sector))
                    {
                        throw new SmartModelException('"id_sector" array value isnt from type int: '.$id_node); 
                    }
                }
            }
            else
            {
                throw new SmartModelException('"id_sector" isnt from type array: '); 
            }
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

        if(isset($data['exclude']))
        {
            if(!is_array($data['exclude']))
            {
                throw new SmartModelException('"exclude" isnt an array'); 
            }
            else
            {
                foreach($data['exclude'] as $id_article)
                {
                    if(!is_int($id_article))
                    {
                        throw new SmartModelException('Wrong "exclude" array value: '.$id_article.'. Only integers accepted!'); 
                    }
                }
            }
        }

        if(isset($data['exclude_node']))
        {
            if(!is_array($data['exclude_node']))
            {
                throw new SmartModelException('"exclude_node" isnt an array'); 
            }
            else
            {
                foreach($data['exclude_node'] as $id_node)
                {
                    if(!is_int($id_node))
                    {
                        throw new SmartModelException('Wrong "exclude_node" array value: '.$id_node.'. Only integers accepted!'); 
                    }
                }
            }
        }

        if(isset($data['order']))
        {
            if(!is_array($data['order']))
            {
                throw new SmartModelException('"order" action array instruction isnt an array'); 
            }
            else
            {
                if(!isset($this->tblFields_article[$data['order'][0]]) && !preg_match("/rand/i",$data['order'][0]) )
                {
                    throw new SmartModelException('Wrong "order" array[0] value: '.$data['order'][0]); 
                }

                if(isset($data['order'][1]))
                {
                    if(!preg_match("/asc|desc/i",$data['order'][1]))
                    {
                        throw new SmartModelException('Wrong "order" array[1] value: '.$data['order'][1]); 
                    }
                }
                else
                {
                    $data['order'][1] = '';
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

        if(isset($data['modifydate']))
        {
            if(!is_array($data['modifydate']))
            {
                throw new SmartModelException('"modifydate" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['modifydate'][0]))
                {
                    throw new SmartModelException('Wrong "modifydate" array[0] value: '.$data['modifydate'][0]); 
                }

                if(!isset($data['modifydate'][1]) || !preg_match("/^CURRENT_TIMESTAMP$/i",$data['modifydate'][1]))
                {
                    throw new SmartModelException('Wrong "modifydate" array[1] value: '.$data['modifydate'][1]); 
                }
            }
            $this->sqlCache = 'SQL_NO_CACHE';
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
        
        if(isset($data['timezone']))
        {
            if(!is_int($data['timezone']))
            {
                    throw new SmartModelException('"timezone" isnt from type int'); 
            }
            
            $this->timezone = $data['timezone'];
        }
        
        return TRUE;
    }  
    
    private function getNumComments( $id_article )
    {
        $sql = "
            SELECT {$this->sqlCache}
                count(`id_comment`) AS `num_rows` 
            FROM
                {$this->config['dbTablePrefix']}article_comment
            WHERE
                `id_article`={$id_article}";

        $rs = $this->model->dba->query($sql);
        
        $row = $rs->fetchAssoc();
        
        return $row['num_rows'];
    }
    
    /**
     * get article users
     *
     * @param int $id_article
     * @param array $data
     * @return array
     */       
    private function getAuthors( $id_article, & $data )
    {
        $result = array();
        
        $this->model->action('article','getArticleUsers',
                 array('result'     => & $result,
                       'id_article' => (int)$id_article,
                       'status'     => &$data['author']['status'], 
                       'order'      => &$data['author']['order'],
                       'fields'     => &$data['author']['fields'] ));
        
        return $result;
    }
}

?>
