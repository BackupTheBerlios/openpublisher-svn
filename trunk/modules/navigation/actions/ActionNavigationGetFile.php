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
 * ActionUserGetFile class 
 *
 * USAGE:
 *
 * $model->action('navigation','getFile',
 *                array('id_file' => int, 
 *                      'result'  => & array, 
 *                      'fields'  => array('id_file','rank','file',
 *                                         'title','description',
 *                                         'mime','size','media_folder') ))
 *
 */
 
class ActionNavigationGetFile extends JapaAction
{
    private $tblFields_pic = array('id_node' => TRUE,
                                   'id_file' => TRUE,
                                   'rank'    => TRUE,
                                   'file'    => TRUE,
                                   'title'   => TRUE,
                                   'description'  => TRUE,
                                   'mime'         => TRUE,
                                   'size'         => TRUE,
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
            $_fields .= $comma.'`'.$f.'`';
            $comma = ',';
        }

        $sql = "
            SELECT SQL_CACHE
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}navigation_media_file
            WHERE
                `id_file`={$data['id_file']}";

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
