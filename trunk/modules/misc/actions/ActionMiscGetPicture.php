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
 * ActionMiscGetPicture class 
 *
 * USAGE:
 *
 * $model->action('misc','getPicture',
 *                array('id_pic' => int, 
 *                      'result'  => & array, 
 *                      'fields'  => array('id_pic','rank','file',
 *                                         'title','description',
 *                                         'mime','size')))
 *
 */
 
class ActionMiscGetPicture extends SmartAction
{
    private $tblFields_pic = array('id_text' => TRUE,
                                   'id_pic'  => TRUE,
                                   'rank'    => TRUE,
                                   'file'    => TRUE,
                                   'title'   => TRUE,
                                   'width'   => TRUE,
                                   'height'  => TRUE,
                                   'description'  => TRUE,
                                   'media_folder' => TRUE,
                                   'mime'    => TRUE,
                                   'size'    => TRUE);
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
                continue;
            }
            $_fields .= $comma.'p.`'.$f.'`';
            $comma = ',';
        }
        
        if(in_array('media_folder',$data['fields']))
        {
            $sel = $comma.'n.`media_folder`';
            $table = ",{$this->config['dbTablePrefix']}misc_text AS n ";
            $where = " AND p.id_text=n.id_text";
        }
        else
        {
            $sel = '';
            $table = '';
            $where = '';
        }

        $sql = "
            SELECT SQL_CACHE
                {$_fields}
                {$sel}
            FROM
                {$this->config['dbTablePrefix']}misc_text_pic AS p
                {$table}
            WHERE
                p.`id_pic`= {$data['id_pic']}
                {$where}";

        $rs = $this->model->dba->query($sql);
        if($rs->numRows() > 0)
        {
            $data['result'] = $rs->fetchAssoc();     
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

        if(!isset($data['id_pic']))
        {
            throw new SmartModelException("No 'id_pic' defined");
        }

        if(!is_int($data['id_pic']))
        {
            throw new SmartModelException("'id_pic' isnt numeric");
        }

        if(isset($data['media_folder']) && !is_string($data['media_folder']))
        {
            throw new SmartModelException("'media_folder' isnt from type string");
        }
        
        return TRUE;
    }
}

?>
