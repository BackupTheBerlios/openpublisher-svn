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
 * ActionUserGetUsers class 
 *
 *
 * USAGE:
 * $model->action('user','getUser',
 *                array('result'  => & array, 
 *                      'role'    => array(string,int),    // '=|<|>|>=|<=',allowed roles  - optional
 *                      'order'   => array(string,string), // 'name|lastname|login|role|status',asc|desc  - optional
 *                      'or_id_user => int,                // optional
 *                      'fields'  => array('id_user','login','role',
 *                                         'status','lock','lock_time',
 *                                         'access','passwd,'name',
 *                                         'lastname','email','description',
 *                                         'format','logo','media_folder')))
 *
 */

include_once(JAPA_BASE_DIR . 'modules/user/includes/ActionUser.php');
 
class ActionUserGetUsers extends ActionUser
{
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

        if(isset($data['order']))
        {
            $sql_order = " ORDER BY {$data['order'][0]} {$data['order'][1]}";
        }
        else
        {
            $sql_order = " ORDER BY `role` ASC, `login` ASC";
        }          

        if(isset($data['role']))
        {
            $role = "`role` {$data['role'][0]} {$data['role'][1]}";
        }
        else
        {
            $role = "1=1";
        }

        if(isset($data['id_user']))
        {
            $_id_user = implode(",", $data['id_user']);
            $id_user  = " AND `id_user` IN({$_id_user})";
        }
        else
        {
            $id_user = "";
        }

        if(isset($data['or_id_user']))
        {
            $or_id_user = " OR `id_user`={$data['or_id_user']}";
        }
        else
        {
            $or_id_user = "";
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}user_user
            WHERE
                {$role}
                {$id_user}
                {$or_id_user}
                {$sql_order}";

        $rs = $this->model->dba->query($sql);
        
        while($row = $rs->fetchAssoc())
        {            
            if(isset($data['translate_role']) && isset($row['role']))
            {
                $row['role_t'] = $this->userRole[$row['role']];    
            }
            
            $data['result'][] = $row;
        } 
    } 
    
    public function validate( $data = FALSE )
    {
        foreach($data['fields'] as $key)
        {
            if(!isset($this->tblFields_user[$key]))
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

        if(isset($data['or_id_user']) && !is_int($data['or_id_user']))
        {
            throw new SmartModelException("'or_id_user' isnt from type int");
        }

        if(isset($data['role']))
        {
            if(!is_array($data['role']))
            {
                throw new SmartModelException('"role" action array instruction isnt an array'); 
            }
            else
            {
                if(!preg_match("/=|<|>|>=|<=/",$data['role'][0]))
                {
                    throw new SmartModelException('Wrong "role" array[0] value: '.$data['role'][0]); 
                }

                if(isset($data['role'][1]))
                {
                    // check allowed role values
                    if(!isset($this->userRole[$data['role'][1]]))
                    {
                        throw new SmartModelException('Wrong "role" array[1] value: '.$data['role'][1]); 
                    }
                }
                else
                {
                    throw new SmartModelException('"role" array[1] value isnt set.'); 
                }
            }
        }

        if(isset($data['order']))
        {
            if(!is_array($data['order']))
            {
                throw new SmartModelException('"order" action array instruction isnt an array'); 
            }
            else
            {
                if(!preg_match("/name|lastname|login|role|status/",$data['order'][0]))
                {
                    throw new SmartModelException('Wrong "order" array[0] value: '.$data['order'][0]); 
                }

                if(isset($data['order'][1]))
                {
                    if(!preg_match("/asc|desc/i",$data['order'][1]))
                    {
                        throw new SmartModelException('Wrong "order" array[1] value: '.$data['order'][1]); 
                    }
                }
                else
                {
                    $data['order'][1] = 'ASC';
                }
            }
        }
        return TRUE;
    }
}

?>
