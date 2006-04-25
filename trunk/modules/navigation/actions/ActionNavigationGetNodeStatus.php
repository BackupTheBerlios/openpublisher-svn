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
 * ActionNavigationGetNodeStatus class 
 *
 * USAGE:
 * $node_status = $model->action('navigation','getNodeStatus',
 *                               array('id_node' => int))
 *
 */
 
class ActionNavigationGetNodeStatus extends SmartAction
{   
    /**
     * get node status
     *
     * @param array $data
     * @return node status id or FALSE
     */
    function perform( $data = FALSE )
    {
        $sql = "
            SELECT SQL_CACHE
                status
            FROM
                {$this->config['dbTablePrefix']}navigation_node
            WHERE
                `id_node`={$data['id_node']}";
        
        $rs = $this->model->dba->query( $sql );  
        
        if( $row = $rs->fetchAssoc() )
        {
            return $row['status'];
        }
        return FALSE;
    } 
    /**
     * validate data array
     *
     * @param array $data
     * @return bool true or false on error
     */    
    public function validate( $data = FALSE )
    { 
        if(!isset($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt defined');        
        }
        if(!is_int($data['id_node']))
        {
            throw new SmartModelException('"id_node" isnt from type int');        
        }
        return TRUE;
    }
}

?>
