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
 * ActionNavigationOptimizeDbTables class 
 *
 * USAGE:
 * $model->action('navigation','optimizeDbTables')
 */
 
class ActionNavigationOptimizeDbTables extends JapaAction
{                                      
    /**
     * optimize navigation module DB tables
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "OPTIMIZE NO_WRITE_TO_BINLOG TABLE 
                  {$this->config['dbTablePrefix']}navigation_node,
                  {$this->config['dbTablePrefix']}navigation_node_lock,
                  {$this->config['dbTablePrefix']}navigation_media_pic,
                  {$this->config['dbTablePrefix']}navigation_media_file,
                  {$this->config['dbTablePrefix']}navigation_view,
                  {$this->config['dbTablePrefix']}navigation_index";
        
        $this->model->dba->query($sql);
    } 
}

?>
