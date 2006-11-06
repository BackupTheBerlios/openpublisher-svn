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
 * $model->action('navigation','search',
 *                array('result' => & array, 
 *                      'status' => array('<|>|<=|>=|=', 1|2),     // optional
 *                      'order'  => array('rank|title|
 *                                         modifydate', 'asc|desc'),// optional
 *                      'fields  => array('id_node','id_parent','id_sector',
                                          'id_view','status','rank',
 *                                        'modifydate','lang','title',
 *                                        'short_description','body',
 *                                        'format','media_folder') ));
 *
 */
 
class ActionNavigationSearch extends JapaAction
{
    /**
     * Allowed sql caching
     */
    protected $sqlCache = 'SQL_CACHE';
    
    /**
     * Allowed article fields and its type
     */
    protected $tblFields_node = array('id_node'      => 'Int',
                                         'id_parent'    => 'Int',
                                         'id_sector'    => 'Int',
                                         'id_view'      => 'Int',
                                         'status'       => 'Int',
                                         'rank'         => 'Int',
                                         'modifydate'   => 'String',
                                         'lang'         => 'String',
                                         'title'        => 'String',
                                         'short_text'   => 'String',
                                         'body'         => 'String',
                                         'format'       => 'Int',
                                         'logo'         => 'String',
                                         'media_folder' => 'String');
    /**
     * get article data
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.'a.`'.$f.'`';
            $comma = ',';
        }
        
        $sql_status = "";      
        
        if(isset($data['status']))
        {
            $sql_status = " AND n.`status`{$data['status'][0]}{$data['status'][1]}";
        }

        if(isset($data['order']))
        {
            $sql_order = " ORDER BY n.{$data['order'][0]} {$data['order'][1]}";
        }
        else
        {
            $sql_order = "ORDER BY n.title ASC";
        } 

        if(isset($data['modifydate']))
        {
            $sql_modifydate = " AND n.`modifydate`{$data['modifydate'][0]}{$data['modifydate'][1]}()";
        }
        else
        {
            $sql_modifydate = "";
        }  

        if(isset($data['status']))
        {
            $sql_nodestatus = " AND n.`status`{$data['status'][0]}{$data['status'][1]}";
        }
        else
        {
            $sql_nodestatus = "";
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
                {$this->config['dbTablePrefix']}navigation_index AS i,
                {$this->config['dbTablePrefix']}navigation_node  AS n
            WHERE MATCH 
                (i.`text1`,i.`text2`,i.`text3`,i.`text4`) 
            AGAINST 
                ('{$search_string}' IN BOOLEAN MODE)
            AND 
                n.`id_node`=i.`id_article` 
                {$sql_status}
                {$sql_nodestatus}
                {$sql_modifydate}
                {$sql_order}
                {$sql_limit}";
        
        $rs = $this->model->dba->query($sql);
        
        while($row = $rs->fetchAssoc())
        {
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
            if(!isset($this->tblFields_node[$val]))
            {
                throw new SmartModelException("Field '".$val."' dosent exists!");
            }
        }

        if(!isset($data['result']))
        {
            throw new SmartModelException('Missing "result" array var: '); 
        }
        elseif(!is_array($data['result']))
        {
            throw new SmartModelException('"result" isnt from type array'); 
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
                if(!isset($data['status'][0]) || !preg_match("/>|<|=|>=|<=|!=/",$data['status'][0]))
                {
                    throw new SmartModelException('Wrong "status" array[0] value: '.$data['status'][0]); 
                }

                if(!isset($data['status'][1]) || !is_int($data['status'][1]))
                {
                    throw new SmartModelException('Wrong "status" array[1] value: '.$data['status'][1]); 
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
                if(!isset($this->tblFields_article[$data['order'][0]]))
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
                    $data['order'][1] = 'ASC';
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
        
        return TRUE;
    }
}

?>
