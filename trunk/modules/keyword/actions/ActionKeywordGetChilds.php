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
 * ActionKeywordGetChilds class 
 * USAGE:
 *
 * $model->action('navigation','getChilds',
 *                array('id_key' => int,
 *                      'result'  => & array,
 *                      'status'  => array('>|<|=|>=|<=|!=',1|2),           // optional
 *                      'order'   => array('rank|title','asc|desc'),        // optional
 *                      'fields   => array('id_key''id_parent',
 *                                         'title','description') ));
 *
 */
 
class ActionKeywordGetChilds extends JapaAction
{ 
    /**
     * Fields and the format of each of the db table keyword 
     *
     */
    private $tblFields_keyword = 
                      array('id_key'      => 'Int',
                            'id_parent'   => 'Int',
                            'status'      => 'Int',
                            'title'       => 'String',
                            'description' => 'String');
                            
    /**
     * get child keywords data
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
        
        $sql = "
            SELECT SQL_CACHE 
                {$_fields}
            FROM
                {$this->config->dbTablePrefix}keyword
            WHERE
                `id_parent`={$data['id_key']} 
                {$sql_where}
            ORDER BY `title`";
        
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
            if(!isset($this->tblFields_keyword[$val]))
            {
                throw new JapaModelException("Field '".$val."' dosent exists!");
            }
        }

        if(!isset($data['id_key']))
        {
            throw new JapaModelException('"id_key" action array instruction is required'); 
        }
        
        if(!is_int($data['id_key']))
        {
            throw new JapaModelException('"id_key" isnt from type string');        
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
        
        return TRUE;
    }
}

?>
