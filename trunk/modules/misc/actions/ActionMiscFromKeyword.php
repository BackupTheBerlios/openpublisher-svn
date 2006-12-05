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
 * ActionMiscFromKeyword class 
 *
 * Get keyword related textes
 *
 * USAGE:
 *
 * $model->action('misc','fromKeyword',
 *                array('id_key_list' => array( int, int,..,..,..),
 *                      'result'      => & array,
 *                      'status'      => array('>|<|=|>=|<=|!=',1|2), // text status - optional
 *                      'key_status'  => array('>|<|=|>=|<=|!=',1|2), // keyword status - optional
 *                      'exclude'     => array( integers ),           // exclude id_textes's optional
 *                      'order'   => array('title', 'asc|desc'), // optional
 *                      'disable_sql_cache' => TRUE,  // optional 
 *                      'fields   => array('id_text','status',
 *                                         'title','description',
 *                                         'format','media_folder') ));
 *
 */

 
class ActionMiscFromKeyword extends JapaAction
{
    /**
     * Allowed sql caching
     */
    protected $sqlCache = 'SQL_CACHE';
    
    /**
     * Allowed text fields and its type
     */
    protected $tblFields_text = 
                      array('id_text'      => 'Int',
                            'status'       => 'Int',
                            'format'       => 'Int',
                            'media_folder' => 'String',
                            'title'        => 'String',
                            'description'  => 'String',
                            'body'         => 'String');
    /**
     * get textes of some given id_key's
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        if(isset($data['disable_sql_cache']))
        {
            $this->sqlCache = 'SQL_NO_CACHE';
        }
        
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.'mt.`'.$f.'`';
            $comma = ',';
        }

        if(isset($data['exclude']))
        {
            $exclude = implode(",", $data['exclude']);
            $sql_exclude = " AND mt.`id_text` NOT IN($exclude)";
        }
        else
        {
            $sql_exclude = "";
        }
       
        if(isset($data['status']))
        {
            $sql_where = " AND mt.`status`{$data['status'][0]}{$data['status'][1]}";
        }
        else
        {
            $sql_where = "";
        }
        
        if(isset($data['order']))
        {
            $sql_order = " ORDER BY mt.{$data['order'][0]} {$data['order'][1]}";
        }
        else
        {
            $sql_order = "ORDER BY mt.`title` ASC";
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
                {$this->config->dbTablePrefix}misc_text AS mt,
                {$this->config->dbTablePrefix}misc_keyword AS mk
            WHERE
                mk.`id_key` IN({$this->id_key_list})
           {$sql_exclude}
            AND
                mk.`id_text`=mt.`id_text`
           {$sql_where}
           GROUP BY mt.`id_text`
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

        if(isset($data['id_key_list']))
        {
            if(!is_array($data['id_key_list']))
            {
                throw new JapaModelException('"id_key_list" isnt an array'); 
            }
            else
            {
                foreach($data['id_key_list'] as $id_key)
                {
                    if(!is_int($id_key))
                    {
                        throw new JapaModelException('Wrong "id_key_list" array value: '.$id_article.'. Only integers accepted!'); 
                    }
                }
                $this->id_key_list = implode(",", $data['id_key_list']);
            }
        }


        if(isset($data['exclude']))
        {
            if(!is_array($data['exclude']))
            {
                throw new JapaModelException('"exclude" isnt an array'); 
            }
            else
            {
                foreach($data['exclude'] as $id_text)
                {
                    if(!is_int($id_text))
                    {
                        throw new JapaModelException('Wrong "exclude" array value: '.$id_text.'. Only integers accepted!'); 
                    }
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
        
        return TRUE;
    }    
}

?>
