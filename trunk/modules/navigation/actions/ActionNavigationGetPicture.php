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
 * ActionNavigationGetPicture class 
 *
 * USAGE:
 *
 * $model->action('navigation','getPicture',
 *                array('id_pic'  => int, 
 *                      'result'  => & array, 
 *                      'fields'  => array('id_pic','id_node','rank','file',
 *                                         'title','description','media_folder'
 *                                         'mime','size','width','height')))
 *
 */
 
class ActionNavigationGetPicture extends JapaAction
{
    private $tblFields_pic = array('id_node' => TRUE,
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
            $table = ",{$this->config['dbTablePrefix']}navigation_node AS n ";
            $where = " AND p.id_node=n.id_node";
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
                {$this->config['dbTablePrefix']}navigation_media_pic AS p
                {$table}
            WHERE
                p.`id_pic`={$data['id_pic']}
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
