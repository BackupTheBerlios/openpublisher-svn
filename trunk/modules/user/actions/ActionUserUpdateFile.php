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
 * ActionUpdateFile class 
 *
 * USAGE:
 * $model->action('user','updateFile',
 *                array('action'  => string,   // delete or update
 *                      'id_file' => int,
 *                      'id_user' => int))
 */
class ActionUserUpdateFile extends JapaAction
{
    /**
     * update/delete user picture from db table
     *
     * @param array $data
     * @return int user id or false on error
     */
    function perform( $data = FALSE )
    { 
        switch($data['action'])
        {
            case 'delete':
                    $this->delete($data);
                break;
            case 'update':
                    $this->update($data);
        }
        
        return TRUE;
    }
    
    /**
     * validate user data
     *
     * @param array $data User data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {
        if(!isset($data['action']) || (($data['action'] != 'delete') && ($data['action'] != 'update')))
        {      
            throw new SmartModelException("No/Wrong 'action'. Required!");
        }
        
        if(!isset($data['id_file']))
        {
            throw new SmartModelException("No 'id_file' defined. Required!");
        }

        if(!is_int($data['id_file']))
        {
            throw new SmartModelException("'id_file' isnt from type int");
        }

        if($data['action'] == 'delete')
        {
            if(!isset($data['id_user']))
            {
                throw new SmartModelException("No 'id_user' defined. Required!");
            }

            if(!is_int($data['id_user']))
            {
                throw new SmartModelException("'id_user' isnt from type int");
            }
        }
        
        return TRUE;
    }
    /**
     * delete picture entry from database and reorder the pictures rank structure
     *
     * @param array $data
     */  
    private function delete($data)
    {
        $sql = "
            DELETE FROM
                {$this->config['dbTablePrefix']}user_media_file
            WHERE
                id_file='{$data['id_file']}'";
        
        $this->model->dba->query($sql);    
        
        // Reorder the picture rank
        $sql = "
            SELECT `id_file` FROM
                {$this->config['dbTablePrefix']}user_media_file
            WHERE
                id_user='{$data['id_user']}'
            ORDER BY `rank` ASC";
        
        $stmt = $this->model->dba->query($sql);   
        $rank = 1;
        
        while($row = $stmt->fetchAssoc())
        {
            $sql = "UPDATE {$this->config['dbTablePrefix']}user_media_file
                      SET
                        `rank`={$rank}
                    WHERE
                      `id_file`={$row['id_file']}";

            $this->model->dba->query($sql);   
            $rank++;
        }
    }
    
    private function update(&$data)
    {
        $comma = '';
        $fields = '';
        
        foreach($data['user'] as $key => $val)
        {
            $fields .= $comma.'`'.$key.'`=?';
            $comma = ',';
        }
        
        $sql = "UPDATE {$this->config['dbTablePrefix']}user_user
                  SET
                   $fields
                  WHERE
                   `id_user`={$data['id_user']}";

        $stmt = $this->model->dba->prepare($sql);                    
        
        foreach($data['user'] as $key => $val)
        {
            $methode = 'set'.$this->tblFields_user[$key];
            $stmt->$methode($val);
        }
       
        $stmt->execute();  
    }    
}

?>
