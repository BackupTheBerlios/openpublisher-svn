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
 *
 * USAGE:
 * $model->action('navigation','getNode',
 *                array('id_node' => int, 
 *                      'result'  => & array, 
 *                      'status'  => array('<|>|<=|>=|=', 1|2),     // optional
 *                      'fields'  => array('id_node','status','rank'
 *                                         'format','media_folder','id_parent','id_sector',
 *                                         'title','short_text',
 *                                         'body','id_view','logo')))
 *
 */
 
class ActionNavigationGetNode extends JapaAction
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
    function perform( $data = FALSE )
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
        
        $sql = "
            SELECT SQL_CACHE
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}navigation_node
            WHERE
                `id_node`={$data['id_node']} 
                {$sql_where}";
        
        $rs = $this->model->dba->query($sql);
        if($rs->numRows() > 0)
        {
            $data['result'] = $rs->fetchAssoc();     
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
            throw new JapaModelException('"id_node" isnt defined');        
        }
        if(!is_int($data['id_node']))
        {
            throw new JapaModelException('"id_node" isnt from type int');        
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
        
        return TRUE;
    }
}

?>
