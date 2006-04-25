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
 * ActionUserCheckLogin class 
 *
 * USAGE:
 *
 * $is_login_ok = $model->action('user','checkLogin',
 *                               array('login'  => string,   // [a-zA-Z0-9_-] <= 50 chars
 *                                     'passwd' => string))  // [a-zA-Z0-9_-] <= 50 chars
 *
 * return TRUE or FALSE
 * if TRUE this action sets 2 session variables:
 * - loggedUserId
 * - loggedUserRole
 *
 */

class ActionUserCheckLogin extends SmartAction
{
    /**
     * Check login
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        $login = $this->model->dba->escape($data['login']);
        $pass =  md5($data['passwd']);  
        
        $sql = "SELECT 
                    id_user,
                    role,
                    user_gmt
                FROM
                    {$this->config['dbTablePrefix']}user_user
                WHERE
                    login='{$login}'
                AND
                    passwd='{$pass}'
                AND
                    status=2";

        $rs = $this->model->dba->query($sql);

        if($row = $rs->fetchAssoc())
        {
            $this->model->session->set('loggedUserId',   $row['id_user']);
            $this->model->session->set('loggedUserRole', $row['role']);
            $this->model->session->set('loggedUserGmt',  $row['user_gmt']);

            return TRUE;
        }
        
        return FALSE;
    } 

    /**
     * Validate data before passed to the perform methode
     *
     * @param array $data
     */    
    public function validate( $data = FALSE )
    {
        if(!is_string($data['login']))
        {
            throw new SmartModelException("'login' isnt from type string!");
        }
        elseif( preg_match("/[^a-zA-Z0-9_-]/", $data['login']) )
        { 
            return FALSE;        
        }
        elseif(strlen($data['login']) > 50)
        {
            return FALSE;
        }

        if(!is_string($data['passwd']))
        {
            throw new SmartModelException("'passwd' isnt from type string!");
        }        
        elseif( preg_match("/[^a-zA-Z0-9_-]/", $data['passwd']) )
        {
            return FALSE;        
        }  
        elseif(strlen($data['passwd']) > 50)
        {
            return FALSE;
        }
        
        return TRUE;
    }
}

?>
