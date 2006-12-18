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
 * ActionCommonGetUrlRewrite
 *
 * USAGE:
 * $map = $model->action( 'common', 'getRequestMap', array('request_name'  => string) );    
 *
 */

class ActionCommonGetRequestMap extends JapaAction
{
    /**
     * Add http headers to disable browser caching
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        $id_map = crc32($data['request_name']);
        
        $sql = "SELECT * FROM {$this->config->dbTablePrefix}common_public_controller_map
                   WHERE 
                   `id_map`={$id_map}";

        $this->model->dba->query($sql);  
        
        $rs = $this->model->dba->query($sql);

        if($rs->numRows() > 0)
        {
            $row = $rs->fetchAssoc();
            $row['item_name'] = $this->model->config->getModuleVar( $row['module'], 'id_item' );
            return $row;
        }
        return false;
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if(!isset($data['request_name']))
        {
            throw new JapaModelException('Missing "request_name" string var: '); 
        }
        
        return true;
    }    
}

?>