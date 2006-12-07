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
 * Setup action of the link module 
 *
 */
 
class ActionLinkSetup extends JapaAction
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
        
        $sql = "CREATE TABLE IF NOT EXISTS {$this->config->getVar('_dbTablePrefix')}link_links (
                   `id_link`     int(11) unsigned NOT NULL auto_increment,
                   `id_node`     int(11) unsigned NOT NULL default 0,
                   `status`      tinyint(1) NOT NULL default 0,
                   `title`       text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `description` text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `url`         text NOT NULL default '',
                   `hits`        int(11) unsigned NOT NULL default 0,
                   PRIMARY KEY   (`id_link`),
                   KEY `id_node` (`id_node`),
                   KEY `status`  (`status`),
                   KEY `hits`    (`hits`),
                   FULLTEXT      (`title`,`description`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}link_lock (
                   `id_link`      int(11) unsigned NOT NULL default 0,
                   `lock_time`    datetime NOT NULL default '0000-00-00 00:00:00',
                   `by_id_user`   int(11) unsigned NOT NULL default 0,
                   UNIQUE KEY `id_link`    (`id_link`),
                   KEY `lock_time`  (`lock_time`),
                   KEY `by_id_user` (`by_id_user`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}link_keyword (
                   `id_link`     int(11) unsigned NOT NULL default 0,
                   `id_key`      int(11) unsigned NOT NULL default 0,
                   KEY `id_link` (`id_link`),
                   KEY `id_key`  (`id_key`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql); 

        $_default_config = array(
                 `use_keywords` => 1) 
        );
       
        $sql = "INSERT INTO {$this->config->getVar('_dbTablePrefix')}common_module
                   (`name`, `alias`, `rank`, `version`, `visibility`, `perm`, `release`, `config`)
                  VALUES
                   ('link',
                    'Links Management',
                    6,
                    '0.1',
                    1,
                    60,
                    'DATE: 23.8.2005 AUTHOR: Armand Turpel <cms@open-publisher.net>',
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
        $sql = "DROP TABLE IF EXISTS {$this->config->getVar('_dbTablePrefix')}link_links,
                                     {$this->config->getVar('_dbTablePrefix')}link_lock,
                                     {$this->config->getVar('_dbTablePrefix')}link_keyword,
                                     {$this->config->getVar('_dbTablePrefix')}link_node_rel";
        $this->model->dba->query($sql);  
    }
}

?>