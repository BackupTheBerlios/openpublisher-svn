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
 * $model->action('navigation','getAllThumbs',
 *                array('id_node' => int, 
 *                      'result'  => & array,
 *                      'fields'  => array('id_pic','rank','file',
 *                                         'title','description',
 *                                         'mime','size','height','width')))
 *
 */
 
class ActionNavigationGetAllThumbs extends JapaAction
{
    private $tblFields_pic = array('id_pic'  => TRUE,
                                   'id_node' => TRUE,
                                   'rank'    => TRUE,
                                   'file'    => TRUE,
                                   'width'   => TRUE,
                                   'height'  => TRUE,
                                   'title'   => TRUE,
                                   'description' => TRUE,
                                   'mime'    => TRUE,
                                   'size'    => TRUE);
    /**
     * get all picture thumbnail data of a node
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
                {$this->config['dbTablePrefix']}navigation_media_pic
            WHERE
                (`id_node`={$data['id_node']})
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
        if(!isset($data['fields']))
        {
            throw new SmartModelException("'fields' isnt set");
        }
        elseif(!is_array($data['fields']))
        {
            throw new SmartModelException("'fields' isnt from type array");
        }
        elseif(count($data['fields']) == 0)
        {
            throw new SmartModelException("'fields' array is empty");
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

        if(!isset($data['id_node']))
        {
            throw new SmartModelException("No 'id_node' defined");
        }
        if(!is_int($data['id_node']))
        {
            throw new SmartModelException("'id_node' isnt from type int");
        }

        return TRUE;
    }
}

?>
