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
 * $model->action('misc','getText',
 *                array('id_text' => int, 
 *                      'result'  => & array, 
 *                      'status'  => array('<|>|<=|>=|=', 1|2),     // optional
 *                      'fields'  => array('id_text','status',
 *                                         'format','media_folder',
 *                                         'title','description',
 *                                         'body')))
 *
 *
 */
 
class ActionMiscGetText extends JapaAction
{
    /**
     * Fields and the format of each of the db table
     *
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
                {$this->config['dbTablePrefix']}misc_text
            WHERE
                `id_text`={$data['id_text']}
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
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
        foreach($data['fields'] as $val)
        {
            if(!isset($this->tblFields_text[$val]))
            {
                throw new SmartModelException("Field '".$val."' dosent exists!");
            }
        }

        if(!isset($data['id_text']))
        {
            throw new SmartModelException('"id_text" isnt defined.');        
        }
        
        if(!is_int($data['id_text']))
        {
            throw new SmartModelException('"id_text" isnt from type int.');        
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
        
        return TRUE;
    }
}

?>
