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
 * ActionUserAdd class 
 * 
 * USAGE:
 * 
 * $model->action('user','add',
 *                array('error' => & array(),
 *                      'user'  => array('login'   => string,
 *                                       'passwd'  => string,
 *                                       'status'  => int,
 *                                       'role'    => int,
 *                                       'name'    => string,
 *                                       'lastname => string,
 *                                       'email'   => string
 *                                       )))
 *
 */

include_once(JAPA_BASE_DIR . 'modules/user/includes/ActionUser.php');
 
class ActionUserAdd extends ActionUser
{
    /**
     * add user
     *
     * @param array $data
     * @return int user id or false on error
     */
    function perform( $data = FALSE )
    { 
        // encrypt password
        $data['user']['passwd'] = md5($data['user']['passwd']);
        
        $comma  = "";
        $fields = "";
        $quest  = "";
        
        foreach($data['user'] as $key => $val)
        {
            $fields .= $comma."`".$key."`";
            $quest  .= $comma."'".$this->model->dba->escape($val)."'";
            $comma   = ",";
        }    
        
        $sql = "INSERT INTO {$this->config->dbTablePrefix}user_user
                   ($fields)
                  VALUES
                   ($quest)";

        $this->model->dba->query($sql);
        
        // return user id
        return $this->model->dba->lastInsertID();
    }
    
    /**
     * validate user data
     *
     * @param array $data User data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {
        // check if database fields exists
        foreach($data['user'] as $key => $val)
        {
            if(!isset($this->tblFields_user[$key]))
            {
                throw new JapaModelException("Field '".$key."' dosent exists!");
            }
        }

        if(!isset($data['error']))
        {
            throw new JapaModelException("'error' var isnt set!");
        }
        elseif(!is_array($data['error']))
        {
            throw new JapaModelException("'error' var isnt from type array!");
        }
                
        // Check user data field values
        //
        if(isset($data['user']['login']))
        {
            if(!is_string($data['user']['login']))
            {
                throw new JapaModelException("'login' isnt from type string!");
            }
            elseif(empty($data['user']['login']))
            {
                $data['error'][] = 'Login is empty';      
            }
            $str_len = strlen( $data['user']['login'] );
            if( ($str_len < 3) || ($str_len > 20) )
            {
                $data['error'][] = 'Only 3-20 login chars are accepted.';     
            }   
            if( @preg_match("/[^a-zA-Z0-9]/", $data['user']['login']) )
            {
                $data['error'][] = 'Login entry is not correct! Only 3-30 chars a-zA-Z0-9 are accepted.';       
            }    
            // Check if login exists
            elseif($this->loginExists($data['user']['login']) > 0)
            {
                $data['error'][] = 'Login exists';
            }    
        }
        else
        {
           $data['error'][] = 'Login isnt defined';  
        }


        if(isset($data['user']['passwd']))
        {
            if(!is_string($data['user']['passwd']))
            {
                throw new JapaModelException("'passwd' isnt from type string!");
            }
            elseif(empty($data['user']['passwd']))
            {
                $data['error'][] = 'Password is empty';       
            } 
            $str_len = strlen( $data['user']['passwd'] );
            if( ($str_len < 3) || ($str_len > 20) )
            {
                $data['error'][] = 'Only 3-20 password chars are accepted.';     
            }
            if( @preg_match("/[^a-zA-Z0-9]/", $data['user']['passwd']) )
            {
                $data['error'][] = 'Password entry is not correct! Only 3-30 chars a-zA-Z0-9 are accepted.';       
            }          
        }
        else
        {
           $data['error'][] = 'Password isnt defined'; 
        }           
  
        if(isset($data['user']['name']) && !is_string($data['user']['name']))
        {
            throw new JapaModelException("'name' isnt from type string!");
        }    

        if(isset($data['user']['lastname']) && !is_string($data['user']['lastname']))
        {
            throw new JapaModelException("'lastname' isnt from type string!");
        }            

        if(isset($data['user']['email']) && !empty($data['user']['email']))
        {
            if(!is_string($data['user']['email']))
            {
                throw new JapaModelException("'email' isnt from type string!");
            }
            elseif( !@preg_match("/^[a-zA-Z0-9_.+-]+@[^@]+[^@.]\.[a-zA-Z]{2,}$/", $data['user']['email']) )
            {
                $data['error'][] = 'Email format is not correct!';      
            } 
        }    

        if(isset($data['user']['status']))
        {
            if(!is_int($data['user']['status']))
            {
                throw new JapaModelException("'status' isnt from type int!");
            }
            elseif( ($data['user']['status'] <= 0) || ($data['user']['status'] > 2) )
            {
                $data['error'][] = 'Status value must be 1 or 2';      
            }     
        }            
    
        if(isset($data['user']['role']))
        {
            if(!is_int($data['user']['role']))
            {
                throw new JapaModelException("'role' isnt from type int!");
            }
            elseif( ($data['user']['role'] < 0) || ($data['user']['role'] > 250) )
            {
                $data['error'][] = 'Role value must be between 0 and 250';      
            }         
        }                      
    

        
        if( count($data['error']) > 0 )
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * check if login exist
     *
     * @param string $login User login
     * @return int Number of logins
     */    
    function loginExists( $login )
    {
        $login = $this->model->dba->escape( $login );
        
        $sql = "
            SELECT
                id_user
            FROM
                {$this->config->dbTablePrefix}user_user
            WHERE
                login='$login'";
        
        $stmt = $this->model->dba->query($sql);

        return $stmt->numRows();    
    } 
    
}

?>
