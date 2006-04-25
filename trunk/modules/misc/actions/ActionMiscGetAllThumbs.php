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
 * ActionNavigationGetAllThumbs
 *
 * USAGE:
 *
 * $model->action('misc','getAllThumbs',
 *                array('id_text' => int, 
 *                      'result'  => & array,
 *                      'fields'  => array('id_pic','rank','file',
 *                                         'title','description',
 *                                         'mime','size','height','width')))
 *
 */
 
class ActionMiscGetAllThumbs extends SmartAction
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
            $_fields .= $comma.'`'.$f.'`';
            $comma = ',';
        }

        $sql = "
            SELECT SQL_CACHE
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}misc_text_pic
            WHERE
                (`id_text`={$data['id_text']})
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

        return TRUE;
    }
}

?>
