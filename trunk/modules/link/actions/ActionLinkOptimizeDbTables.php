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
 * ActionLinkOptimizeDbTables class 
 *
 * USAGE:
 * $model->action('link','optimizeDbTables')
 */
 
class ActionLinkOptimizeDbTables extends JapaAction
{                                      
    /**
     * optimize link module DB tables
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "OPTIMIZE NO_WRITE_TO_BINLOG TABLE 
                  {$this->config['dbTablePrefix']}link_links,
                  {$this->config['dbTablePrefix']}link_keyword,
                  {$this->config['dbTablePrefix']}link_lock";
        
        $this->model->dba->query($sql);
    } 
    /**
     * validate data
     *
     * @param array $data 
     * @return bool 
     */    
    public function validate( $data = FALSE )
    {     
        return true;
    }
}

?>
