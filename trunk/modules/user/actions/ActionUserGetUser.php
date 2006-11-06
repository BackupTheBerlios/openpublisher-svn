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
 * USAGE:
 * $model->action('user','getUser',
 *                array('id_user' => int, 
 *                      'result'  => & array, 
 *                      'fields'  => array('id_user','login','role',
 *                                         'status','lock','lock_time',
 *                                         'access','passwd,'name',
 *                                         'lastname','email','description',
 *                                         'format','logo','media_folder')))
 *
 */

include_once(JAPA_BASE_DIR . 'modules/user/includes/ActionUser.php');
 
class ActionUserGetUser extends ActionUser
{
    /**
     * get user data
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
            SELECT
                {$_fields}
            FROM
                {$this->config['dbTablePrefix']}user_user
            WHERE
                `id_user`={$data['id_user']}";
        
        $rs = $this->model->dba->query($sql);
        if($rs->numRows() > 0)
        {
            $data['result'] = $rs->fetchAssoc();     
        }
        else
        {
            return;
        }
            
        if(isset($data['translate_role']) && isset($data['result']['role']))
        {
            $data['result']['role_t'] = $this->userRole[$data['result']['role']];    
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

        if(!isset($data['id_user']))
        {
            throw new SmartModelException("No 'id_user' defined");
        }

        if(!is_int($data['id_user']))
        {
            throw new SmartModelException("'id_user' isnt numeric");
        }
        
        return TRUE;
    }
}

?>
