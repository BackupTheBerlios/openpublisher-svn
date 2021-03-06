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
 * ActionArticleSearch class 
 *
 * USAGE:
 * $model->action('article','search',
 *                array('result' => & array, 
 *                      'status' => array('<|>|<=|>=|=', 1|2),     // optional
 *                      'order'  => array('rank|title|
 *                                         articledate|pubdate|
 *                                         overtitle|subtitle', 'asc|desc'),// optional
 *                      'fields  => array('id_article','status','rank',
 *                                        'pubdate','articledate','modifydate',
 *                                        'lang','title','overtitle',
 *                                        'subtitle','header','description',
 *                                        'body','ps',
 *                                        'format','media_folder') ));
 *
 */
 
class ActionArticleSearch extends JapaAction
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
                                         'articledate'  => 'String',
                                         'pubdate'      => 'String',
                                         'modifydate'   => 'String',
                                         'rewrite_name' => 'String',
                                         'lang'         => 'String',
                                         'title'        => 'String',
                                         'overtitle'    => 'String',
                                         'subtitle'     => 'String',
                                         'header'       => 'String',
                                         'description'  => 'String',
                                         'body'         => 'String',
                                         'ps'           => 'String',
                                         'logo'         => 'String',
                                         'media_folder' => 'String');
    /**
     * get article data
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        // we need Id_article field
        if(!in_array('id_article', $data['fields'] ))
        {
            $data['fields'][] = 'id_article';
        }
        
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            // Modify dates depended on gmt+X settings
            if(($f == 'pubdate') || ($f == 'modifydate') || ($f == 'articledate'))
            {
                $_fields .= $comma."DATE_ADD(a.`{$f}`,INTERVAL {$this->model->action('common', 'getGmtOffset')}  HOUR) AS `{$f}`";
            }
            else
            {
                $_fields .= $comma.'a.`'.$f.'`';
            }

            $comma = ',';
        }
        
        $sql_status = "";      
        
        if(isset($data['status']))
        {
            $sql_status = " AND a.`status`{$data['status'][0]}{$data['status'][1]}";
        }

        if(isset($data['order']))
        {
            $sql_order = " ORDER BY a.{$data['order'][0]} {$data['order'][1]}";
        }
        else
        {
            $sql_order = "ORDER BY a.title ASC";
        } 

        $_gmtDate = $this->config->getVar('gmtDate');

        if(isset($data['pubdate']))
        {
            if($data['pubdate'][1] == "CURRENT_TIMESTAMP")
            {
                $_date = $_gmtDate;
            }
            else
            {
                $_date = $data['pubdate'][1];
            }
            $sql_pubdate = " AND a.`pubdate`{$data['pubdate'][0]}'{$_date}'";
        }
        else
        {
            $sql_pubdate = "";
        }  

        if(isset($data['nodeStatus']))
        {
            $sql_nodestatus = " AND a.id_node=n.id_node 
                                AND n.`status`{$data['nodeStatus'][0]}{$data['nodeStatus'][1]}";
            $nodetable = ",{$this->config->dbTablePrefix}navigation_node AS n";
        }
        else
        {
            $sql_nodestatus = "";
            $nodetable = "";
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

        $search_string = $this->model->dba->escape( $data['search'] );
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->config->dbTablePrefix}article_index    AS i,
                {$this->config->dbTablePrefix}article_article  AS a
                {$nodetable}
            WHERE MATCH 
                (i.`text1`,i.`text2`,i.`text3`,i.`text4`) 
            AGAINST 
                ('{$search_string}' IN BOOLEAN MODE)
            AND 
                a.`id_article`=i.`id_article` 
                {$sql_status}
                {$sql_nodestatus}
                {$sql_pubdate}
                {$sql_order}
                {$sql_limit}";
        
        $rs = $this->model->dba->query($sql);
        
        while($row = $rs->fetchAssoc())
        {
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
            throw new JapaModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        foreach($data['fields'] as $val)
        {
            if(!isset($this->tblFields_article[$val]))
            {
                throw new JapaModelException("Field '".$val."' dosent exists!");
            }
        }

        if(!isset($data['result']))
        {
            throw new JapaModelException('Missing "result" array var: '); 
        }
        elseif(!is_array($data['result']))
        {
            throw new JapaModelException('"result" isnt from type array'); 
        }        

        if(isset($data['limit']))
        {        
            if(!isset($data['limit']['numPage']))
            {
                throw new JapaModelException('numPage" isnt defined'); 
            } 
            if(!is_int($data['limit']['numPage']))
            {
                throw new JapaModelException('numPage" isnt from type int'); 
            }             
            if(!isset($data['limit']['perPage']))
            {
                throw new JapaModelException('"perPage" isnt defined'); 
            } 
            if(!is_int($data['limit']['perPage']))
            {
                throw new JapaModelException('"perPage" isnt from type int'); 
            }  
            elseif( $data['limit']['perPage'] < 2 )
            {
                throw new JapaModelException('"perPage" must be >= 2');
            }
        }

        if(isset($data['status']))
        {
            if(!is_array($data['status']))
            {
                throw new JapaModelException('"status" isnt an array'); 
            }
            else
            {
                if(!isset($data['status'][0]) || !preg_match("/>|<|=|>=|<=|!=/",$data['status'][0]))
                {
                    throw new JapaModelException('Wrong "status" array[0] value: '.$data['status'][0]); 
                }

                if(!isset($data['status'][1]) || !is_int($data['status'][1]))
                {
                    throw new JapaModelException('Wrong "status" array[1] value: '.$data['status'][1]); 
                }
            }
        }

        if(isset($data['order']))
        {
            if(!is_array($data['order']))
            {
                throw new JapaModelException('"order" action array instruction isnt an array'); 
            }
            else
            {
                if(!isset($this->tblFields_article[$data['order'][0]]))
                {
                    throw new JapaModelException('Wrong "order" array[0] value: '.$data['order'][0]); 
                }

                if(isset($data['order'][1]))
                {
                    if(!preg_match("/asc|desc/i",$data['order'][1]))
                    {
                        throw new JapaModelException('Wrong "order" array[1] value: '.$data['order'][1]); 
                    }
                }
                else
                {
                    $data['order'][1] = 'ASC';
                }
            }
        }

        if(isset($data['disable_sql_cache']))
        {
            if(!preg_match("/^SQL_NO_CACHE$/",$data['disable_sql_cache']))
            {
                throw new JapaModelException('Wrong "disable_sql_cache" string value: '.$data['disable_sql_cache']); 
            }
            $this->sqlCache = 'SQL_NO_CACHE';
        }

        if(isset($data['pubdate']))
        {
            if(!is_array($data['pubdate']))
            {
                throw new JapaModelException('"pubdate" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['pubdate'][0]))
                {
                    throw new JapaModelException('Wrong "pubdate" array[0] value: '.$data['pubdate'][0]); 
                }

                if(!isset($data['pubdate'][1]) || !preg_match("/^CURRENT_TIMESTAMP$/i",$data['pubdate'][1]))
                {
                    throw new JapaModelException('Wrong "pubdate" array[1] value: '.$data['pubdate'][1]); 
                }
            }
            $this->sqlCache = 'SQL_NO_CACHE';
        }

        if(isset($data['nodeStatus']))
        {
            if(!is_array($data['nodeStatus']))
            {
                throw new JapaModelException('"nodeStatus" isnt an array'); 
            }
            else
            {
                if(!isset($data['nodeStatus'][0]) || !preg_match("/>|<|=|>=|<=|!=/",$data['nodeStatus'][0]))
                {
                    throw new JapaModelException('Wrong "nodeStatus" array[0] value: '.$data['nodeStatus'][0]); 
                }

                if(!isset($data['nodeStatus'][1]) || !is_int($data['nodeStatus'][1]))
                {
                    throw new JapaModelException('Wrong "nodeStatus" array[1] value: '.$data['nodeStatus'][1]); 
                }
            }
        }
        
        return TRUE;
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
