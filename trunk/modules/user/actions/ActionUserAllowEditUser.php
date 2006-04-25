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
 * ActionUserAllowEditUser class 
 *
  * USAGE:
  * 
  * $model->action('user','allowEditUser',
  *                array('id_user'  => int))
  *
 */
 
class ActionUserAllowEditUser extends SmartAction
{
    /**
     * Check if the logged user have rights to edit data 
     * of a requested user (id_user)
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {   
        // allow the logged user edit its own data
        if($data['id_user'] == $this->model->session->get('loggedUserId'))
        {
            return TRUE;
        }
        
        $sql = "SELECT 
                    role
                FROM
                    {$this->config['dbTablePrefix']}user_user
                WHERE
                    id_user={$data['id_user']}";
        
        $stmt = $this->model->dba->query($sql);

        if( !$row = $stmt->fetchAssoc() )
        {
            return FALSE;
        }
        
        // accord permission if the role of the user to edit
        // is greater than the role of the logged user
        if($row['role'] > $this->model->session->get('loggedUserRole'))
        {
            return TRUE;
        }

        return FALSE;
    } 

    /**
     * Validate data before passed to the perform methode
     *
     * @param array $data
     */    
    function validate( $data = FALSE )
    {
        if( !isset($data['id_user']) || !is_int($data['id_user']) )
        {
            return FALSE;        
        }
        return TRUE;
    }
}

?>
