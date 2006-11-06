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
            $this->rollback($data);
            return TRUE;
        }
        
        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}navigation_node (
                   `id_node`       int(11) unsigned NOT NULL auto_increment,
                   `id_parent`     int(11) unsigned NOT NULL default 0,
                   `id_sector`     int(11) unsigned NOT NULL default 0,
                   `id_view`       int(11) unsigned NOT NULL default 0,
                   `status`        tinyint(1) NOT NULL default 0,
                   `rank`          smallint(4) unsigned NOT NULL default 0,
                   `modifydate`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                   `lang`          char(2) NOT NULL default 'en',
                   `title`         text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `short_text`    text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `body`          mediumtext CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `format`        tinyint(1) NOT NULL default 0,
                   `logo`          varchar(255) NOT NULL default '',
                   `media_folder`  char(32) NOT NULL,
                   PRIMARY KEY       (`id_node`),
                   KEY               (`id_parent`,`rank`,`status`),
                   KEY `node_status` (`status`),
                   KEY `id_sector`   (`id_sector`),
                   KEY `modifydate`  (`modifydate`), 
                   KEY `view`        (`id_view`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}navigation_index (
                   `id_node`    int(11) unsigned NOT NULL default 0,
                   `text1`      text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `text2`      text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `text3`      text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `text4`      text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',                   
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
                   `title`        text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `description`  text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
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
                   `title`        text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `description`  text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   PRIMARY KEY    (`id_file`),
                   KEY            (`id_node`,`rank`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);        

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}navigation_config (
                 `thumb_width`    smallint(4) NOT NULL default 120,
                 `img_size_max`   int(11) NOT NULL default 500000,
                 `file_size_max`  int(11) NOT NULL default 5000000,
                 `force_format`   tinyint(1) NOT NULL default 2,
                 `default_format` tinyint(1) NOT NULL default 2,
                 `default_lang`   char(2) NOT NULL default 'en',
                 `use_keywords`   tinyint(1) NOT NULL default 1,
                 `use_short_text` tinyint(1) NOT NULL default 1,
                 `use_body`       tinyint(1) NOT NULL default 1,
                 `use_logo`       tinyint(1) NOT NULL default 1,
                 `use_images`     tinyint(1) NOT NULL default 1,
                 `use_files`      tinyint(1) NOT NULL default 1) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}navigation_view (
                   `id_view`      int(11) unsigned NOT NULL auto_increment,
                   `name`         varchar(255) NOT NULL default '',
                   `description`  text NOT NULL default '',
                   PRIMARY KEY    (`id_view`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);      

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}navigation_keyword (
                   `id_node`     int(11) unsigned NOT NULL default 0,
                   `id_key`      int(11) unsigned NOT NULL default 0,
                   KEY `id_node` (`id_node`),
                   KEY `id_key`  (`id_key`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "INSERT INTO {$data['dbtablesprefix']}navigation_config
                   (`thumb_width`, `img_size_max`,`file_size_max`)
                  VALUES
                   (120,500000,5000000)";
        $this->model->dba->query($sql); 

        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_module
                   (`name`, `alias`, `rank`, `version`, `visibility`, `perm`, `release`)
                  VALUES
                   ('navigation',
                    'Navigation Nodes Management',
                    2,
                    '0.1',
                    1,
                    20,
                    'DATE: 6.5.2005 AUTHOR: Armand Turpel <cms@open-publisher.net>')";
        $this->model->dba->query($sql);          
    } 
    
    /**
     * Rollback setup
     * Delete db tables of this module 
     *
     */    
    public function rollback( &$data )
    {
        $sql = "DROP TABLE IF EXISTS {$data['dbtablesprefix']}navigation_node,
                                     {$data['dbtablesprefix']}navigation_node_lock,
                                     {$data['dbtablesprefix']}navigation_media_pic,
                                     {$data['dbtablesprefix']}navigation_media_file,
                                     {$data['dbtablesprefix']}navigation_config,
                                     {$data['dbtablesprefix']}navigation_index,
                                     {$data['dbtablesprefix']}navigation_view";
        $this->model->dba->query($sql);  
    }
}

?>