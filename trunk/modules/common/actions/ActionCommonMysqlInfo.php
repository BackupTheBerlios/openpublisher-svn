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
 * ActionCommonMysqlInfo class 
 *
 *
 * $model->action('common','mysqlInfo',
 *                array('result'    => & array() ))
 *
 */

class ActionCommonMysqlInfo extends JapaAction
{
    /**
     * add node picture or file
     *
     * @param array $data
     * @return int node id or false on error
     */
    function perform( $data = FALSE )
    { 
        // get mysql server version
        $sql = "SELECT VERSION() AS `version`";
        $rs = $this->model->dba->query($sql);
        $row = $rs->fetchAssoc();
        $data['result']['version'] = $row['version'];
        
        // get mysql cache status 
        $sql = "SHOW VARIABLES LIKE 'have_query_cache'";
        $rs = $this->model->dba->query($sql);
        while($row = $rs->fetchAssoc())
        {
            $data['result']['status'][$row['Variable_name']] = $row['Value'];
        }        
        
        // get mysql cache status 
        $sql = "SHOW STATUS LIKE 'Qcache%'";
        $rs = $this->model->dba->query($sql);
        while($row = $rs->fetchAssoc())
        {
            $data['result']['status'][$row['Variable_name']] = $row['Value'];
        }
      
    }
    
    /**
     * validate  data
     *
     * @param array $data
     * @return bool 
     */    
    function validate( $data = FALSE )
    {
        if(!isset($data['result']))
        {
            throw new JapaModelException("'result' var isnt set!");
        }
        elseif(!is_array($data['result']))
        {
            throw new JapaModelException("'result' var isnt from type array!");
        }      
        
        return TRUE;
    }
}

?>
