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
 * ActionUserDelete class 
 *
 * USAGE:
 *
 * $model->action('user','delete',
 *                array('id_user'  => int))
 * 
 */
 
class ActionUserDelete extends JapaAction
{
    /**
     * delete user
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "DELETE FROM {$this->config->dbTablePrefix}user_access
                  WHERE
                   `id_user`={$data['id_user']}";

        $this->model->dba->query($sql);

        $sql = "DELETE FROM {$this->config->dbTablePrefix}user_lock
                  WHERE
                   `id_user`={$data['id_user']}";

        $this->model->dba->query($sql);

        $sql = "DELETE FROM {$this->config->dbTablePrefix}user_media_pic
                  WHERE
                   `id_user`={$data['id_user']}";

        $this->model->dba->query($sql);

        $sql = "DELETE FROM {$this->config->dbTablePrefix}user_media_file
                  WHERE
                   `id_user`={$data['id_user']}";

        $this->model->dba->query($sql);

        $sql = "SELECT `media_folder` FROM {$this->config->dbTablePrefix}user_user
                  WHERE
                   `id_user`={$data['id_user']}";
                   
        $rs = $this->model->dba->query($sql);

        $row = $rs->fetchAssoc();

        if(isset($row['media_folder']) && !empty($row['media_folder']))
        {
            // delete user data media folder
            JapaCommonUtil::deleteDirTree( JAPA_BASE_DIR.'data/user/'.$row['media_folder'] );
        }
        
        $sql = "DELETE FROM {$this->config->dbTablePrefix}user_user
                  WHERE
                   `id_user`={$data['id_user']}";

        $this->model->dba->query($sql);

        // inform other modules that this user (id_user) was deleted 
        $this->model->broadcast( 'deleteUser', array('id_user' => $data['id_user']) );
       
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
        // Check if id_user exists
        if(!is_int($data['id_user']))
        {
            throw new JapaModelException('var id_user has wrong format');
        }  
        
        return TRUE;
    }
}

?>
