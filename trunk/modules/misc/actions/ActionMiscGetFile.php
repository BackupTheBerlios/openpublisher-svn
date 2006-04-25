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
 * $model->action('misc','getFile',
 *                array('id_file' => int, 
 *                      'result'  => & array, 
 *                      'fields'  => array('id_file','rank','file',
 *                                         'title','description',
 *                                         'mime','size','media_folder')))
 *
 */
 
class ActionMiscGetFile extends SmartAction
{
    private $tblFields_pic = array('id_text' => TRUE,
                                   'id_file'  => TRUE,
                                   'rank'    => TRUE,
                                   'file'    => TRUE,
                                   'title'   => TRUE,
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
            $_fields .= $comma.'f.`'.$f.'`';
            $comma = ',';
        }
        
        if(in_array('media_folder',$data['fields']))
        {
            $sel = $comma.'t.`media_folder`';
            $table = ",{$this->config['dbTablePrefix']}misc_text AS t ";
            $where = " AND f.id_text=t.id_text";
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
                {$this->config['dbTablePrefix']}misc_text_file AS f
                {$table}
            WHERE
                f.`id_file`= {$data['id_file']}
                {$where}";

        $rs = $this->model->dba->query($sql);
        if($rs->numRows() > 0)
        {
            $data['result'] = $rs->fetchAssoc();     
        }
    } 
    
    public function validate( $data = FALSE )
    {
        if(!isset($data['fields']) || !is_array($data['fields']) || (count($data['fields'])<1))
        {
            throw new SmartModelException("Array key 'fields' dosent exists, isnt an array or is empty!");
        }
        
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

        if(!isset($data['id_file']))
        {
            throw new SmartModelException("No 'id_file' defined");
        }

        if(!is_int($data['id_file']))
        {
            throw new SmartModelException("'id_file' isnt numeric");
        }

        if(isset($data['media_folder']) && !is_string($data['media_folder']))
        {
            throw new SmartModelException("'media_folder' isnt from type string");
        }
        
        return TRUE;
    }
}

?>
