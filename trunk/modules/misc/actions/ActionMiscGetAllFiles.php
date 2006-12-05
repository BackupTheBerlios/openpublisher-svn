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
 * $model->action('misc','getAllFiles',
 *                array('id_text' => int, 
 *                      'result'  => & array, 
 *                      'fields'  => array('id_file','rank','file',
 *                                         'title','description',
 *                                         'mime','size')))
 *
 */
 
class ActionMiscGetAllFiles extends JapaAction
{
    private $tblFields_pic = array('id_file' => TRUE,
                                   'id_text' => TRUE,
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
                {$this->config->dbTablePrefix}misc_text_file
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

        if(!isset($data['id_text']))
        {
            throw new JapaModelException("No 'id_text' defined");
        }

        if(!is_int($data['id_text']))
        {
            throw new JapaModelException("'id_text' isnt from type int");
        }
        return TRUE;
    }
}

?>
