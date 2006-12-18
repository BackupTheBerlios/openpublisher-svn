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
 * ActionCommonUpdateUrlRewrite
 *
 * USAGE:
 * $model->action( 'common', 'updateUrlRewrite',     
 *                array('id_map'        => int,        
                        'request_name'  => string );    
 *
 */

class ActionCommonUpdateUrlRewrite extends JapaAction
{
    /**
     * Add http headers to disable browser caching
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {       
        $request_name = $this->model->dba->escape($data['request_name']);
        $id_map       = crc32($data['request_name']);
        
        $sql = "
            UPDATE {$this->config->dbTablePrefix}common_public_controller_map
                SET
                   `request_name`='{$request_name}',
                   `id_map`='{$id_map}'
                WHERE
                `id_map`={$data['id_map']}";
        
        $this->model->dba->query($sql);            
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if(!isset($data['id_map']))
        {
            throw new JapaModelException('Missing "id_map" int var: '); 
        }
        
        if(!is_int($data['id_map']))
        {
            throw new JapaModelException('"id_map" isnt from type int'); 
        }
        
        if(!isset($data['request_name']))
        {
            throw new JapaModelException('Missing "request_name" array var: '); 
        }
        
        return true;
    }    
}

?>