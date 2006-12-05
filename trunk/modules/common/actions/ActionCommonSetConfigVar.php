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
 * ActionCommonSetConfigVar class 
 *
 * $model->action('common','setConfigVar',
 *                array('data'   => array,
 *                      'module' => string) )
 */
 
class ActionCommonSetConfigVar extends JapaAction
{
    /**
     * update config values of a module
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data = false )
    {
        $_vars = $this->get_vars($data['module']);
        
        foreach($data['data'] as $key => $val)
        {
            $_vars[$key] = $val;
        }
        
        $_config = serialize($_vars);
        
        $sql = "
            UPDATE {$this->config->dbTablePrefix}common_module
                SET `config`='{$_config}'
            WHERE
                `name`='{$data['module']}'";
        
        $this->model->dba->query($sql);                    
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool
     */    
    public function validate( $data = false )
    { 
        if(!isset($data['data']) || !is_array($data['data']))
        {
            throw new JapaModelException("Array key 'data' dosent exists, isnt from type array!");
        }
        
        if(!isset($data['module']) || !is_string($data['module']))
        {
            throw new JapaModelException("Array key 'module' dosent exists, isnt from type string!");
        }
        
        return true;
    }
    
    private function get_vars($module)
    {
        $sql = "SELECT SQL_CACHE `config` FROM {$this->config->dbTablePrefix}common_module WHERE `name`='{$module}'";
        
        $rs = $this->model->dba->query($sql);
        
        $row = $rs->fetchAssoc();
        return unserialize($row['config']);
    }
}

?>
