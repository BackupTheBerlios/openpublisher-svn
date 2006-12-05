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
 * ActionNavigationGetNode class 
 * USAGE:
 *
 * $model->action('navigation','getChilds',
 *                array('id_node' => int,
 *                      'result'  => & array,
 *                      'status'  => array('>|<|=|>=|<=|!=',1|2),           // optional
 *                      'order'   => array('rank|title','asc|desc'),        // optional
 *                      'fields   => array('id_node''id_parent''id_sector',
 *                                         'id_view','status','rank',
 *                                         'format','logo','media_folder',
 *                                         'lang','title','short_text',
 *                                         'body') ));
 *
 */
 
class ActionNavigationGetChilds extends JapaAction
{   
    /**
     * Fields and the format of each of the db table navigation_node 
     *
     */
    private $tblFields_node = 
                      array('id_node'      => 'Int',
                            'id_parent'    => 'Int',
                            'id_sector'    => 'Int',
                            'id_view'      => 'Int',
                            'status'       => 'Int',
                            'rank'         => 'Int',
                            'format'       => 'Int',
                            'logo'         => 'String',
                            'media_folder' => 'String',
                            'lang'         => 'String',
                            'title'        => 'String',
                            'short_text'   => 'String',
                            'body'         => 'String');
                            
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
            $sql_order = "";
        }        
        
        $sql = "
            SELECT SQL_CACHE 
                {$_fields}
            FROM
                {$this->config->dbTablePrefix}navigation_node
            WHERE
                `id_parent`={$data['id_node']} 
                {$sql_where} 
                {$sql_order}";
        
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
            if(!isset($this->tblFields_node[$val]))
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
                if(!preg_match("/rank|title/",$data['order'][0]))
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
