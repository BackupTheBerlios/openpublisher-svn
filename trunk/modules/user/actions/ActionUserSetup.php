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
 * Setup action of the user module 
 *
 */
 
class ActionUserSetup extends SmartAction
{
    /**
     * Run setup process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        if(isset($data['rollback']))
        {
            $this->rollback( $data );
            return TRUE;
        }
        
        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}user_user (
                   `id_user`      int(11) unsigned NOT NULL auto_increment,
                   `login`        varchar(30) CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `passwd`       char(32) NOT NULL,
                   `role`         tinyint(3) unsigned NOT NULL default 10,
                   `status`       tinyint(1) NOT NULL default 1,
                   `user_gmt`     tinyint(2) NOT NULL default 1,
                   `name`         varchar(255) CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `lastname`     varchar(255) CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `email`        varchar(255) NOT NULL default '',
                   `description`  text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `format`       tinyint(1) NOT NULL default 0,
                   `logo`         varchar(255) NOT NULL default '',
                   `media_folder` char(32) NOT NULL,
                   PRIMARY KEY       (`id_user`),
                   KEY               (`login`,`passwd`,`status`),
                   KEY `user_status` (`status`),
                   KEY `role`        (`role`),
                   FULLTEXT          (`login`,`name`,`lastname`,`description`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}user_access (
                   `id_user`   int(11) unsigned NOT NULL default 0,
                   `access`    datetime NOT NULL default '0000-00-00 00:00:00',
                   UNIQUE KEY `id_user` (`id_user`),
                   KEY `access`         (`access`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}user_lock (
                   `id_user`      int(11) unsigned NOT NULL default 0,
                   `lock_time`    datetime NOT NULL default '0000-00-00 00:00:00',
                   `by_id_user`   int(11) unsigned NOT NULL default 0,
                   UNIQUE KEY `id_user`    (`id_user`),
                   KEY `lock_time`  (`lock_time`),
                   KEY `by_id_user` (`by_id_user`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}user_media_pic (
                   `id_pic`       int(11) unsigned NOT NULL auto_increment,
                   `id_user`      int(11) unsigned NOT NULL default 0,
                   `file`         varchar(255) NOT NULL default '',
                   `size`         int(11) unsigned NOT NULL default 0,
                   `mime`         varchar(255) NOT NULL default '',
                   `width`        smallint(4) unsigned NOT NULL default 0,
                   `height`       smallint(4) unsigned NOT NULL default 0,
                   `rank`         smallint(4) unsigned NOT NULL default 0,
                   `title`        text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `description`  text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   PRIMARY KEY     (`id_pic`),
                   KEY (`id_user`,`rank`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}user_media_file (
                   `id_file`      int(11) unsigned NOT NULL auto_increment,
                   `id_user`      int(11) unsigned NOT NULL default 0,
                   `file`         varchar(255) NOT NULL default '',
                   `size`         int(11) NOT NULL default 0,
                   `mime`         varchar(255) NOT NULL default '',
                   `rank`         smallint(4) unsigned NOT NULL default 0,
                   `title`        text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `description`  text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   PRIMARY KEY     (`id_file`),
                   KEY `id_user`   (`id_user`,`rank`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);        

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}user_config (
                 `thumb_width`    smallint(4) NOT NULL default 200,
                 `img_size_max`   int(11) NOT NULL default 500000,
                 `file_size_max`  int(11) NOT NULL default 5000000,
                 `force_format`   tinyint(1) NOT NULL default 2,
                 `default_format` tinyint(1) NOT NULL default 2,
                 `use_keywords`   tinyint(1) NOT NULL default 1) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}user_keyword (
                   `id_user`     int(11) unsigned NOT NULL default 0,
                   `id_key`      int(11) unsigned NOT NULL default 0,
                   KEY `id_user` (`id_user`),
                   KEY `id_key`  (`id_key`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "INSERT INTO {$data['dbtablesprefix']}user_config
                   (`thumb_width`, `img_size_max`,`file_size_max`)
                  VALUES
                   (120,2000000,10000000)";
        $this->model->dba->query($sql); 

        $passwd = md5($data['superuser_passwd']);

        $sql = "INSERT INTO {$data['dbtablesprefix']}user_user
                   (`login`, `passwd`,`name`,`lastname`,`email`,`status`, `role`)
                  VALUES
                   ('superuser','{$passwd}','super','user','foo@smart5.net',2,10)";
        $this->model->dba->query($sql); 

        // insert module info data
        $sql = "INSERT INTO {$data['dbtablesprefix']}common_module
                   (`name`, `alias`, `rank`, `version`, `visibility`, `perm`, `release`)
                  VALUES
                   ('user','User Management',8,'0.2',1,40,'DATE: 6.5.2005 AUTHOR: Armand Turpel <cms@open-publisher.net>')";
        
        $this->model->dba->query($sql);         
    } 

    /**
     * Rollback setup
     * Delete db tables of this module 
     *
     */    
    public function rollback( & $data )
    {
        $sql = "DROP TABLE IF EXISTS 
                     {$data['dbtablesprefix']}user_user,
                     {$data['dbtablesprefix']}user_access,
                     {$data['dbtablesprefix']}user_lock,
                     {$data['dbtablesprefix']}user_keyword,
                     {$data['dbtablesprefix']}user_media_pic,
                     {$data['dbtablesprefix']}user_media_file,
                     {$data['dbtablesprefix']}user_config";
        $this->model->dba->query($sql);  
    }    
}

?>