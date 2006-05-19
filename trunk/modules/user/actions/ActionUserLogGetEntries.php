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
 * ActionUserLogGetEntries class 
 *
 *
 * USAGE:
 * $model->action('user','logGetEntries',
 *                array('result'  => & array, 
 *                      'id_item' => int,
 *                      'module'  => string,
 *                      'view'    => string,
 *                      'limit'   => array('numPage' => int,
 *                                         'perPage' => int )))
 *
 */
 
class ActionUserLogGetEntries extends SmartAction
{
    /**
     * get data of all users
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {  
        $sql_module = "";
        if(isset($data['module']))
        {
            $sql_module = "AND uli.`module`='{$data['module']}'";
        }
        
        $sql_id_item = "";
        if(isset($data['id_item']))
        {
            $sql_id_item = "AND uli.`id_item`={$data['id_item']}";
        }
        
        $sql_view = "";
        if(isset($data['view']))
        {
            $sql_id_item = "AND uli.`view`='{$data['view']}'";
        }
        
        $sql_limit = "";
        if(isset($data['limit']))
        { 
            if( $data['limit']['numPage'] < 1 )
            {
                $data['limit']['numPage'] = 1;
            }        
            $numPage = ($data['limit']['numPage'] - 1) * $data['limit']['perPage'];
            $sql_limit = " LIMIT {$numPage},{$data['limit']['perPage']}";
        }  
        
        $sql = "
            SELECT
                ul.id_log,   ul.logdate,
                uu.id_user,  uu.login,
                uu.lastname, uu.name,
                uu.email
            FROM
                {$this->config['dbTablePrefix']}user_log AS ul,
                {$this->config['dbTablePrefix']}user_log_info AS uli,
                {$this->config['dbTablePrefix']}user_log_session AS uls,
                {$this->config['dbTablePrefix']}user_user AS uu
            WHERE
                ul.`id_log` = uli.`id_log`
            AND
                ul.`id_session` = uls.`id_session`
            AND
                uu.`id_user` = uls.`id_user`
            {$sql_module}
            {$sql_id_item}
            {$sql_view}
            ORDER BY 
                ul.`logdate` DESC
            {$sql_limit}";

        $rs = $this->model->dba->query($sql);
        
        while($row = $rs->fetchAssoc())
        {            
            $this->gmtToUserGmt( $row['logdate'] );
            $data['result'][] = $row;
        } 
    } 
    
    public function validate( $data = FALSE )
    {
        if(!isset($data['result']))
        {
            throw new SmartModelException("'result' isnt set");
        }
        elseif(!is_array($data['result']))
        {
            throw new SmartModelException("'result' isnt from type array");
        }
        
        if(isset($data['module']))
        {
            if(!is_string($data['module']))
            {
                throw new SmartModelException("'module' isnt from type string");
            }
        }
        
        if(isset($data['view']))
        {
            if(!is_string($data['view']))
            {
                throw new SmartModelException("'view' isnt from type string");
            }
        }
        
        if(isset($data['id_item']))
        {
            if(!is_int($data['id_item']))
            {
                throw new SmartModelException("'id_item' isnt from type int");
            }
        }
        
        if(isset($data['limit']))
        {        
            if(!isset($data['limit']['numPage']))
            {
                throw new SmartModelException('numPage" isnt defined'); 
            } 
            if(!is_int($data['limit']['numPage']))
            {
                throw new SmartModelException('numPage" isnt from type int'); 
            }             
            if(!isset($data['limit']['perPage']))
            {
                throw new SmartModelException('"perPage" isnt defined'); 
            } 
            if(!is_int($data['limit']['perPage']))
            {
                throw new SmartModelException('"perPage" isnt from type int'); 
            }  
            elseif( $data['limit']['perPage'] < 2 )
            {
                throw new SmartModelException('"perPage" must be >= 2');
            }
        }     

        return TRUE;
    }
    
    private function gmtToUserGmt( & $_date )
    {
        $_data = array('action'   => 'gmtToDate',
                       'date'     => & $_date );
                       
        if(isset($this->timezone))
        {
            $_data['timezone'] = $this->timezone;
        }
        
        // convert date from gmt+0 to user timezone 
        $this->model->action('common', 'gmtConverter', $_data);
    }
}

?>
