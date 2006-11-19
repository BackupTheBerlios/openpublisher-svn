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
 * ActionArticleGetNodeArticles class 
 * USAGE:
 *
 * $model->action('article','getNodeArticles',
 *                array('id_node' => int,
 *                      'result'  => & array,
 *                      'status'  => array('>|<|=|>=|<=|!=',1|2),            // optional
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

 
class ActionArticleGetNodeArticles extends JapaAction
{
    /**
     * Allowed sql caching
     */
    protected $sqlCache = 'SQL_CACHE';
    /**
     * Set get authors default
     */    
    protected $getAuthors = false;
    
    /**
     * Allowed article fields and its type
     */
    protected $tblFields_article = array('id_article'   => 'Int', // required
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
                                         'timezone'     => 'Int',
                                         'format'       => 'Int',
                                         'logo'         => 'String',
                                         'media_folder' => 'String');

    /**
     * get articles data of a given id_node
     *
     * @param array $data
     * @return bool true or false on error
     */
    public function perform( $data = FALSE )
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
                $_fields .= $comma."DATE_ADD(`{$f}`,INTERVAL {$this->model->action('common', 'getGmtOffset')}  HOUR) AS `{$f}`";
            }
            else
            {
                $_fields .= $comma.'`'.$f.'`';
            }
            $comma = ',';
        }
        
        if(isset($data['status']))
        {
            $sql_where = " AND `status`{$data['status'][0]}{$data['status'][1]}";
        }
        else
        {
            $sql_where = "";
        }

        if(isset($data['pubdate']))
        {
            if($data['pubdate'][1] == "CURRENT_TIMESTAMP")
            {
                $_pdate = $this->config['gmtDate'];
            }
            else
            {
                $_pdate = $data['pubdate'][1];
            }

            $sql_pubdate = " AND `pubdate`{$data['pubdate'][0]}'{$_pdate}'";
        }
        else
        {
            $sql_pubdate = "";
        }  
        
        if(isset($data['modifydate']))
        {
            if($data['modifydate'][1] == "CURRENT_TIMESTAMP")
            {
                $_mdate = $this->config['gmtDate'];
            }
            else
            {
                $_mdate = $data['modifydate'][1];
            }

            $sql_modifydate = " AND `modifydate`{$data['modifydate'][0]}'{$_mdate}'";
        }
        else
        {
            $sql_modifydate = "";
        }  
        
        if(isset($data['order']))
        {
            if(preg_match("/rand/i",$data['order'][0]))
            {
                $sql_order = " ORDER BY RAND()";
            }
            else
            {        
                $sql_order = " ORDER BY {$data['order'][0]} {$data['order'][1]}";
            }
        }
        else
        {
            $sql_order = "ORDER BY title ASC";
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
                {$this->config['dbTablePrefix']}article_article
            WHERE
                `id_node`={$data['id_node']}
                {$sql_where} 
                {$sql_pubdate}
                {$sql_modifydate}
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

        if(!isset($data['id_node']))
        {
            throw new JapaModelException('"id_node" action array instruction is required'); 
        }
        
        if(!is_int($data['id_node']))
        {
            throw new JapaModelException('"id_node" isnt from type string');        
        }

        if(!isset($data['result']))
        {
            throw new JapaModelException('Missing "result" array var: '); 
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
            elseif( $data['limit']['perPage'] < 1 )
            {
                throw new JapaModelException('"perPage" must be >= 1');
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
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['status'][0]))
                {
                    throw new JapaModelException('Wrong "status" array[0] value: '.$data['status'][0]); 
                }

                if(!isset($data['status'][1]) || preg_match("/[^0-9]+/",$data['status'][1]))
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
                if(!isset($this->tblFields_article[$data['order'][0]]) && !preg_match("/rand/i",$data['order'][0]))
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
                    $data['order'][1] = '';
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
        }
        
        if(isset($data['mofifydate']))
        {
            if(!is_array($data['mofifydate']))
            {
                throw new JapaModelException('"mofifydate" isnt an array'); 
            }
            else
            {
                if(!preg_match("/>|<|=|>=|<=|!=/",$data['mofifydate'][0]))
                {
                    throw new JapaModelException('Wrong "mofifydate" array[0] value: '.$data['mofifydate'][0]); 
                }

                if(!isset($data['mofifydate'][1]) || !preg_match("/^CURRENT_TIMESTAMP$/i",$data['mofifydate'][1]))
                {
                    throw new JapaModelException('Wrong "mofifydate" array[1] value: '.$data['mofifydate'][1]); 
                }
            }
        }

        if(isset($data['timezone']))
        {
            if(!is_int($data['timezone']))
            {
                throw new JapaModelException('"timezone" isnt from type int'); 
            }
            
            $this->timezone = $data['timezone'];
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
