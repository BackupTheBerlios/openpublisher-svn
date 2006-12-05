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
 * ActionArticleOptimizeDbTables class 
 *
 * USAGE:
 * $model->action('article','optimizeDbTables')
 */
 
class ActionArticleOptimizeDbTables extends JapaAction
{                                      
    /**
     * optimize article module DB tables
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $sql = "OPTIMIZE NO_WRITE_TO_BINLOG TABLE 
                  {$this->config->dbTablePrefix}article_article,
                  {$this->config->dbTablePrefix}article_changedate,
                  {$this->config->dbTablePrefix}article_index,
                  {$this->config->dbTablePrefix}article_lock,
                  {$this->config->dbTablePrefix}article_media_pic,
                  {$this->config->dbTablePrefix}article_media_file,
                  {$this->config->dbTablePrefix}article_keyword,
                  {$this->config->dbTablePrefix}article_node_controller_rel,
                  {$this->config->dbTablePrefix}article_controller_rel,
                  {$this->config->dbTablePrefix}article_public_controller,
                  {$this->config->dbTablePrefix}article_comment,
                  {$this->config->dbTablePrefix}article_user";
        
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
