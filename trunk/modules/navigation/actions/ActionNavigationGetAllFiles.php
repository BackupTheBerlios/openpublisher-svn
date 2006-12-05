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
 * ActionUserGetAllFiles class 
 *
 * USAGE:
 *
 * $model->action('navigation','getAllFiles',
 *                array('id_node' => int, 
 *                      'result'  => & array, 
 *                      'fields'  => array('id_file','rank','file',
 *                                         'title','description',
 *                                         'mime','size')))
 *
 */
 
class ActionNavigationGetAllFiles extends JapaAction
{
    private $tblFields_pic = array('id_file' => TRUE,
                                   'id_node' => TRUE,
                                   'rank'    => TRUE,
                                   'file'    => TRUE,
                                   'title'   => TRUE,
                                   'description' => TRUE,
                                   'mime'    => TRUE,
                                   'size'    => TRUE);
    /**
     * get data of all files
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
                {$this->config->dbTablePrefix}navigation_media_file
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
            throw new JapaModelException("'fields' isnt set");
        }
        elseif(!is_array($data['fields']))
        {
            throw new JapaModelException("'fields' isnt from type array");
        }
        elseif(count($data['fields']) == 0)
        {
            throw new JapaModelException("'fields' array is empty");
        }        
        
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

        if(!isset($data['id_node']))
        {
            throw new JapaModelException("No 'id_node' defined");
        }
        if(!is_int($data['id_node']))
        {
            throw new JapaModelException("'id_node' isnt from type int");
        }
        
        return TRUE;
    }
}

?>
