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

class ActionUserCheckLogin extends JapaAction
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
                    {$this->config->dbTablePrefix}user_user
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
            $this->model->session->set('loggedUserGmt',  (int)$row['user_gmt']);
            
            // create log session
            if($this->config->getModuleVar('user', 'use_log') == 1)
            {
                $this->config->setModuleVar('user', 'log_id_session', $this->createUserLogSession( $row['id_user'] ));
                $this->model->session->set('logIdSession', $this->config->getModuleVar('user', 'log_id_session'));
            }

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
            throw new JapaModelException("'login' isnt from type string!");
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
            throw new JapaModelException("'passwd' isnt from type string!");
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

    /**
     * create  user log session
     *
     * @param  int $id_user
     * @return int id_session
     */   
    private function createUserLogSession( $id_user )
    {
        $id_session = 0;
        
        if(isset($_SERVER['REMOTE_ADDR']))
        {
            $remoteIP = $_SERVER['REMOTE_ADDR'];
            
            if (strstr($remoteIP, ','))
            {
                $ips = explode(',', $remoteIP);
                $remoteIP = trim($ips[0]);
            }
        }
        else
        {
            $remoteIP = '0.0.0.0';
        }
        
        if(isset($_SERVER['HTTP_USER_AGENT']))
        {
            $remoteAgent = $_SERVER['HTTP_USER_AGENT'];
        }
        else
        {
            $remoteAgent = '';
        }

        $this->model->action('user','logAddSession',
                             array('id_session' => & $id_session,
                                   'user'       => array('id_user' => (int)$id_user,
                                                         'host'    => (string)gethostbyaddr($remoteIP),
                                                         'ip'      => (string)$remoteIP,
                                                         'agent'   => (string)$remoteAgent)));    

        return $id_session;
    }
}

?>
