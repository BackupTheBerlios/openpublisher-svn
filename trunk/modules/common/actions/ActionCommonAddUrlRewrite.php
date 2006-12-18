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
 * ActionCommonAddUrlRewrite
 *
 * USAGE:
 * $model->action( 'common', 'addUrlRewrite', 
 *                array('module' => string,
                        'request_name'  => string,
                        'request_value' => int) );    
 *
 */

class ActionCommonAddUrlRewrite extends JapaAction
{
    /**
     * Add http headers to disable browser caching
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        $module       = $this->model->dba->escape($data['module']);
        $request_name = $this->model->dba->escape($data['request_name']);
        $id_map       = crc32($data['request_name']);
        
        if(!isset($data['request_value']))
        {
            $request_value = 0;
        }
        else
        {
            $request_value = $this->model->dba->escape($data['request_value']);
        }
        $request_value = $this->model->dba->escape($data['request_value']);
        
        $sql = "REPLACE INTO {$this->config->dbTablePrefix}common_public_controller_map
                   (`id_map`,`module`,`request_name`,`request_value`)
                  VALUES
                   ('{$id_map}','{$module}','{$request_name}','{$request_value}')";

        $this->model->dba->query($sql);      
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if(!isset($data['module']))
        {
            throw new JapaModelException("'module' isnt defined");
        }    
        if(!isset($data['request_name']))
        {
            throw new JapaModelException("'request_name' isnt defined");
        } 
        
        if(null !== $this->model->getControllerRequestValue( $data['request_name'] ))
        {
            return false;
        }
        
        return true;
    }    
}

?>