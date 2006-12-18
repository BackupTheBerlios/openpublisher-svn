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
 * Setup action of the common module 
 *
 */
 
class ActionNavigationSetup extends JapaAction
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
        
        $sql = "CREATE TABLE IF NOT EXISTS {$this->config->getVar('_dbTablePrefix')}navigation_node (
                   `id_node`       int(11) unsigned NOT NULL auto_increment,
                   `id_parent`     int(11) unsigned NOT NULL default 0,
                   `id_sector`     int(11) unsigned NOT NULL default 0,
                   `id_controller` int(11) unsigned NOT NULL default 0,
                   `status`        tinyint(1) NOT NULL default 0,
                   `rank`          smallint(4) unsigned NOT NULL default 0,
                   `modifydate`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                   `lang`          char(2) NOT NULL default 'en',
                   `title`         text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `short_text`    text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `body`          mediumtext CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `logo`          varchar(255) NOT NULL default '',
                   `media_folder`  char(32) NOT NULL,
                   PRIMARY KEY       (`id_node`),
                   KEY               (`id_parent`,`rank`,`status`),
                   KEY `node_status` (`status`),
                   KEY `id_sector`   (`id_sector`),
                   KEY `modifydate`  (`modifydate`), 
                   KEY `id_controller` (`id_controller`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}navigation_index (
                   `id_node`    int(11) unsigned NOT NULL default 0,
                   `text1`      text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `text2`      text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `text3`      text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `text4`      text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',                   
                   UNIQUE KEY `id_node` (`id_node`),
                   FULLTEXT   (`text1`,`text2`,`text3`,`text4`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}navigation_node_lock (
                   `id_node`      int(11) unsigned NOT NULL default 0,
                   `lock_time`    datetime NOT NULL default '0000-00-00 00:00:00',
                   `by_id_user`   int(11) unsigned NOT NULL default 0,
                   UNIQUE KEY `id_node` (`id_node`),
                   KEY `lock_time`  (`lock_time`),
                   KEY `by_id_user` (`by_id_user`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}navigation_media_pic (
                   `id_pic`       int(11) unsigned NOT NULL auto_increment,
                   `id_node`      int(11) unsigned NOT NULL default 0,
                   `file`         varchar(255) NOT NULL default '',
                   `size`         int(11) unsigned NOT NULL default 0,
                   `mime`         varchar(255) NOT NULL default '',
                   `rank`         smallint(4) unsigned NOT NULL default 0,
                   `width`        smallint(4) unsigned NOT NULL default 0,
                   `height`       smallint(4) unsigned NOT NULL default 0,
                   `tumbnail`     tinyint(1) NOT NULL default 0,
                   `title`        text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `description`  text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   PRIMARY KEY    (`id_pic`),
                   KEY            (`id_node`,`rank`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}navigation_media_file (
                   `id_file`      int(11) unsigned NOT NULL auto_increment,
                   `id_node`      int(11) unsigned NOT NULL default 0,
                   `file`         varchar(255) NOT NULL default '',
                   `size`         int(11) NOT NULL default 0,
                   `mime`         varchar(255) NOT NULL default '',
                   `rank`         smallint(4) unsigned NOT NULL default 0,
                   `title`        text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `description`  text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   PRIMARY KEY    (`id_file`),
                   KEY            (`id_node`,`rank`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);        

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}navigation_public_controller (
                   `id_controller` int(11) unsigned NOT NULL auto_increment,
                   `name`         varchar(255) NOT NULL default '',
                   `description`  text NOT NULL default '',
                   PRIMARY KEY    (`id_controller`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);      

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}navigation_keyword (
                   `id_node`     int(11) unsigned NOT NULL default 0,
                   `id_key`      int(11) unsigned NOT NULL default 0,
                   KEY `id_node` (`id_node`),
                   KEY `id_key`  (`id_key`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $_default_config = array(
                 'thumb_width'           => 120,
                 'img_size_max'          => 500000,
                 'file_size_max'         => 5000000,
                 'default_lang'          => 'en',
                 'default_order'         => '',
                 'default_ordertype'     => '',
                 'use_url_rewrite'       => 1,
                 'use_keywords'          => 0,
                 'use_short_text'        => 0,
                 'use_body'              => 0,
                 'use_logo'              => 0,
                 'use_images'            => 0,
                 'use_files'             => 0);
 
        $_config = serialize($_default_config);

        $sql = "INSERT INTO {$this->config->getVar('_dbTablePrefix')}common_module
                   (`name`, `alias`, `rank`, `version`, `visibility`, `perm`, `release`,`config`)
                  VALUES
                   ('navigation',
                    'Navigation Nodes Management',
                    2,
                    '0.2',
                    1,
                    20,
                    'DATE: 6.5.2005 AUTHOR: Armand Turpel <cms@open-publisher.net>',
                    '{$_config}')";
        $this->model->dba->query($sql);       
    } 
    
    /**
     * Rollback setup
     * Delete db tables of this module 
     *
     */    
    public function rollback()
    {
        $sql = "DROP TABLE IF EXISTS {$this->config->getVar('_dbTablePrefix')}navigation_node,
                                     {$this->config->getVar('_dbTablePrefix')}navigation_node_lock,
                                     {$this->config->getVar('_dbTablePrefix')}navigation_media_pic,
                                     {$this->config->getVar('_dbTablePrefix')}navigation_media_file,
                                     {$this->config->getVar('_dbTablePrefix')}navigation_index,
                                     {$this->config->getVar('_dbTablePrefix')}navigation_keyword,
                                     {$this->config->getVar('_dbTablePrefix')}navigation_public_controller";
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