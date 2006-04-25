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
 * ActionMiscGetPictures
 *
 * USAGE:
 *
 * $model->action('misc','getPictures',
 *                array('id_text' => int, 
 *                      'status'  => array('>|<|=|>=|<=|!=',1|2), // optional
 *                      'result'  => & array,
 *                      'limit'   => array('numPage' => int,
 *                                         'perPage' => int),
 *                      'fields'  => array('id_pic','rank','file',
 *                                         'media_folder','title','description',
 *                                         'mime','size','height','width')))
 *
 */
 
class ActionMiscGetPictures extends SmartAction
{
    private $tblFields_pic = array('id_pic'  => TRUE,
                                   'id_text' => TRUE,
                                   'rank'    => TRUE,
                                   'file'    => TRUE,
                                   'width'   => TRUE,
                                   'height'  => TRUE,
                                   'title'   => TRUE,
                                   'description' => TRUE,
                                   'mime'    => TRUE,
                                   'size'    => TRUE,
                                   'media_folder' => TRUE);
    /**
     * get data of all users
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        $comma = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            if($f == 'media_folder')
            {
                $_fields  .= $comma.'mt.`'.$f.'`';          
            }
            else
            {
                $_fields .= $comma.'mtp.`'.$f.'`';
            }
            $comma = ',';
        }

        if(isset($data['status']))
        {
            $sql_status = " AND mt.`status`{$data['status'][0]}{$data['status'][1]}";
        }
        else
        {
            $sql_status = " AND mt.`status`>=2 ";
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

        $sql = "
            SELECT SQL_CACHE
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}misc_text_pic AS mtp,
                {$this->config['dbTablePrefix']}misc_text AS mt
            WHERE
                mtp.`id_text`={$data['id_text']}
            AND
                mtp.`id_text`=mt.`id_text`
            {$sql_status}
            ORDER BY
                mtp.`rank` ASC
                {$sql_limit}";

        $rs = $this->model->dba->query($sql);

        while($row = $rs->fetchAssoc())
        {
            $data['result'][] = $row;
        } 
    } 
    
    public function validate( $data = FALSE )
    {
        foreach($data['fields'] as $key)
        {
            if(!isset($this->tblFields_pic[$key]))
            {
                throw new SmartModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!isset($data['result']))
        {
            throw new SmartModelException("'result' isnt set");
        }
        elseif(!is_array($data['result']))
        {
            throw new SmartModelException("'result' isnt from type array");
        }
        
        if(!isset($data['id_text']))
        {
            throw new SmartModelException("No 'id_text' defined");
        }

        if(!is_int($data['id_text']))
        {
            throw new SmartModelException("'id_text' isnt from type int");
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
            elseif( $data['limit']['perPage'] < 1 )
            {
                throw new SmartModelException('"perPage" must be >= 1');
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

        return TRUE;
    }
}

?>
