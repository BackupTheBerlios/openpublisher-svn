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
 * ActionMiscOptimizeDbTables class 
 *
 * USAGE:
 * $model->action('misc','optimizeDbTables')
 */
 
class ActionMiscOptimizeDbTables extends JapaAction
{                                      
    /**
     * optimize article module DB tables
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "OPTIMIZE NO_WRITE_TO_BINLOG TABLE 
                  {$this->config['dbTablePrefix']}misc_text,
                  {$this->config['dbTablePrefix']}misc_text_lock,
                  {$this->config['dbTablePrefix']}misc_text_pic,
                  {$this->config['dbTablePrefix']}misc_text_file,
                  {$this->config['dbTablePrefix']}misc_config,
                  {$this->config['dbTablePrefix']}misc_keyword,
                  {$this->config['dbTablePrefix']}article_keyword";
        
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
