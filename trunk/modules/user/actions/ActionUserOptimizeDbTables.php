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
 * ActionUserOptimizeDbTables class 
 *
 * USAGE:
 * $model->action('user','optimizeDbTables')
 */
 
class ActionUserOptimizeDbTables extends JapaAction
{                                      
    /**
     * optimize user module DB tables
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "OPTIMIZE NO_WRITE_TO_BINLOG TABLE 
                  {$this->config['dbTablePrefix']}user_user,
                  {$this->config['dbTablePrefix']}user_access,
                  {$this->config['dbTablePrefix']}user_lock,
                  {$this->config['dbTablePrefix']}user_media_pic,
                  {$this->config['dbTablePrefix']}user_media_file";
        
        $this->model->dba->query($sql);
    } 
}

?>
