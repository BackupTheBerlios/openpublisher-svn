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
 * ActionUserGetUsers class 
 *
 * USAGE:
 *
 * $model->action('user','getAllThumbs',
 *                array('id_user' => int, 
 *                      'result'  => & array,
 *                      'fields'  => array('id_pic','rank','file',
 *                                         'title','description',
 *                                         'mime','size','height','width')))
 *
 */
 
class ActionUserGetAllThumbs extends JapaAction
{
    // allowed fields
    private $tblFields_pic = array('id_pic' => TRUE,
                                   'rank'   => TRUE,
                                   'file'   => TRUE,
                                   'title'  => TRUE,
                                   'description' => TRUE,
                                   'mime'   => TRUE,
                                   'height' => TRUE,
                                   'width'  => TRUE,
                                   'size'   => TRUE);
    /**
     * get thumbnails picture data of an user
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
            SELECT
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}user_media_pic
            WHERE
                (`id_user`={$data['id_user']})
            ORDER BY
                `rank` ASC";

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
                throw new JapaModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!isset($data['result']))
        {
            throw new JapaModelException("'result' isnt set");
        }
        elseif(!is_array($data['result']))
        {
            throw new JapaModelException("'result' isnt from type array");
        }
        
        if(!isset($data['id_user']))
        {
            throw new JapaModelException("No 'id_user' defined");
        }

        if(!is_int($data['id_user']))
        {
            throw new JapaModelException("'id_user' isnt from type int");
        }

        return TRUE;
    }
}

?>
