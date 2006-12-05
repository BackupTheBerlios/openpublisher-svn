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
 * ActionCommonUpdateConfig class 
 *
 * $model->action('common','updateConfig',
 *                array('data'   => array,
 *                      'module' => string) )
 */
 
class ActionCommonUpdateConfig extends JapaAction
{
    /**
     * update config values of a module
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data = false )
    {
        $_config = serialize($data['data']);
        
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
}

?>
