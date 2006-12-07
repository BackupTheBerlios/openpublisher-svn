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
 * Setup action of the keyword module 
 *
 */
 
class ActionKeywordSetup extends JapaAction
{
    /**
     * Run setup process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        if(isset($data['rollback']))
        {
            $this->rollback();
            return TRUE;
        }
        
        $sql = "CREATE TABLE IF NOT EXISTS {$this->config->getVar('_dbTablePrefix')}keyword (
                   `id_key`      int(11) unsigned NOT NULL auto_increment,
                   `id_parent`   int(11) unsigned NOT NULL default 0,
                   `status`      tinyint(1) NOT NULL default 1,
                   `modifydate`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                   `title`       text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `description` text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   PRIMARY KEY      (`id_key`),
                   KEY              (`id_parent`, `status`),
                   KEY `key_status` (`status`),
                   FULLTEXT         (`title`,`description`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}keyword_lock (
                   `id_key`      int(11) unsigned NOT NULL default 0,
                   `lock_time`   datetime NOT NULL default '0000-00-00 00:00:00',
                   `by_id_user`  int(11) unsigned NOT NULL default 0,
                   UNIQUE KEY `id_key` (`id_key`),
                   KEY `lock_time`  (`lock_time`),
                   KEY `by_id_user` (`by_id_user`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $_default_config = array(
                 `default_lang`          => 'en');
 
        $sql = "INSERT INTO {$this->config->getVar('_dbTablePrefix')}common_module
                   (`name`, `alias`, `rank`, `version`, `visibility`, `perm`, `release`, `config`)
                  VALUES
                   ('keyword', 'Keywords Management',
                    4,
                    '0.1',
                    1,
                    20,
                    'DATE: 27.10.2005 AUTHOR: Armand Turpel <cms@open-publisher.net>',
                    '{serialize($_default_config)}')";
        $this->model->dba->query($sql);            
    } 
    
    /**
     * Rollback setup
     * Delete db tables of this module 
     *
     */    
    public function rollback()
    {
        $sql = "DROP TABLE IF EXISTS {$this->config->getVar('_dbTablePrefix')}keyword,
                                     {$this->config->getVar('_dbTablePrefix')}keyword_lock";
        $this->model->dba->query($sql);  
    }
    /**
     * validate $data
     *
     */ 
    public function validate( $data = FALSE )
    {
        return true;
    }
}

?>