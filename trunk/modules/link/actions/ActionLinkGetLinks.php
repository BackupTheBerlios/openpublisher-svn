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
 * ActionLinkGetLinks class 
 * USAGE:
 *
 * $model->action('link','getLinks',
 *                array('id_node' => int,
 *                      'result'  => & array,
 *                      'status'  => array('>|<|=|>=|<=|!=',1|2),           // optional
 *                      'order'   => array('hits|title','asc|desc'),        // optional
 *                      'fields   => array('id_node','id_link','url','title',
 *                                         'description','hits') ));
 *
 */

 
class ActionLinkGetLinks extends JapaAction
{
    /**
     * Allowed link fields and its type
     */
    protected $tblFields_link = array('id_link'     => 'Int',
                                      'id_node'     => 'Int',
                                      'status'      => 'Int',
                                      'title'       => 'String',
                                      'description' => 'String',
                                      'url'         => 'String',
                                      'hits'        => 'Int');

    /**
     * get navigation node data
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
            $_fields .= $comma.'`'.$f.'`';
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
        
        if(isset($data['order']))
        {
            $sql_order = " ORDER BY {$data['order'][0]} {$data['order'][1]}";
        }
        else
        {
            $sql_order = "ORDER BY title ASC";
        }        
        
        $sql = "
            SELECT SQL_CACHE
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}link_links
            WHERE
                `id_node`={$data['id_node']} 
                {$sql_where} 
                {$sql_order}";
        
        $rs = $this->model->dba->query($sql);

        while($row = $rs->fetchAssoc())
        {
            $data['result'][] = $row;
        }        
        
        return TRUE;
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
            if(!isset($this->tblFields_link[$val]))
            {
                throw new SmartModelException("Field '".$val."' dosent exists!");
            }
        }

        if(!isset($data['id_node']))
        {
            throw new SmartModelException('"id_node" action array instruction is required'); 
        }
        
        if(!is_int($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt from type string');        
        }

        if(!isset($data['result']))
        {
            throw new SmartModelException('Missing "result" array var: '); 
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

        if(isset($data['order']))
        {
            if(!is_array($data['order']))
            {
                throw new SmartModelException('"order" action array instruction isnt an array'); 
            }
            else
            {
                if(!preg_match("/rank|title/",$data['order'][0]))
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
        
        return TRUE;
    }    
}

?>
