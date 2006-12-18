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
 * Setup action of the article module 
 *
 */
 
class ActionArticleSetup extends JapaAction
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
            return;
        }
        
        $sql = "CREATE TABLE IF NOT EXISTS {$this->config->getVar('_dbTablePrefix')}article_article (
                   `id_article`    int(11) unsigned NOT NULL auto_increment,
                   `id_node`       int(11) unsigned NOT NULL default 1,
                   `status`        tinyint(1) NOT NULL default 0,
                   `rank`          smallint(4) unsigned NOT NULL default 0,
                   `lang`          char(2) NOT NULL default 'en', 
                   `pubdate`       datetime NOT NULL default '0000-00-00 00:00:00',
                   `articledate`   datetime NOT NULL default '0000-00-00 00:00:00',
                   `modifydate`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                   `allow_comment` tinyint(1) NOT NULL default 0,
                   `close_comment` tinyint(1) NOT NULL default 0,
                   `title`         text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `overtitle`     text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `subtitle`      text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `header`        text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `description`   text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `body`          mediumtext CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `ps`            text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `logo`          varchar(255) NOT NULL default '',
                   `media_folder`  char(32) NOT NULL,                   
                   PRIMARY KEY        (`id_article`),
                   KEY                (`status`,`pubdate`,`modifydate`),
                   KEY `articledate`  (`articledate`),
                   KEY `lang`         (`lang`),
                   KEY `rank`         (`rank`),
                   KEY `id_node`      (`id_node`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_changedate (
                   `id_article`     int(11) unsigned NOT NULL default 0,
                   `changedate`     datetime NOT NULL default '0000-00-00 00:00:00',
                   `status`         tinyint(1) NOT NULL default 0,
                   KEY `changedate` (`changedate`),
                   UNIQUE KEY `id_article` (`id_article`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_index (
                   `id_article` int(11) unsigned NOT NULL default 0,
                   `text1`      text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `text2`      text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `text3`      text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `text4`      text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',                   
                   UNIQUE KEY `id_article` (`id_article`),
                   FULLTEXT   (`text1`,`text2`,`text3`,`text4`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_lock (
                   `id_article`      int(11) unsigned NOT NULL default 0,
                   `lock_time`       datetime NOT NULL default '0000-00-00 00:00:00',
                   `by_id_user`      int(11) unsigned NOT NULL default 0,
                   UNIQUE KEY `id_article` (`id_article`),
                   KEY `lock_time`  (`lock_time`),
                   KEY `by_id_user` (`by_id_user`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);
  
        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_media_pic (
                   `id_pic`       int(11) unsigned NOT NULL auto_increment,
                   `id_article`   int(11) unsigned NOT NULL default 0,
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
                   KEY            (`id_article`,`rank`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_media_file (
                   `id_file`      int(11) unsigned NOT NULL auto_increment,
                   `id_article`   int(11) unsigned NOT NULL default 0,
                   `file`         varchar(255) NOT NULL default '',
                   `size`         int(11) NOT NULL default 0,
                   `mime`         varchar(255) NOT NULL default '',
                   `rank`         smallint(4) unsigned NOT NULL default 0,
                   `title`        text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `description`  text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   PRIMARY KEY    (`id_file`),
                   KEY            (`id_article`,`rank`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);        

        $sql = "CREATE TABLE IF NOT EXISTS {$this->config->getVar('_dbTablePrefix')}article_comment (
                   `id_comment`    int(11) unsigned NOT NULL auto_increment,
                   `id_article`    int(11) unsigned NOT NULL default 1,
                   `id_user`       int(11) unsigned NOT NULL default 0,
                   `status`        tinyint(1) NOT NULL default 0,
                   `pubdate`       datetime NOT NULL default '0000-00-00 00:00:00',
                   `author`        varchar(100) CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `email`         varchar(100) CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',
                   `url`           varchar(255) NOT NULL default '',
                   `ip`            varchar(100) NOT NULL default '',
                   `agent`         varchar(255) NOT NULL default '',
                   `body`          text CHARACTER SET {$this->config->getVar('_dbcharset')} NOT NULL default '',                  
                   PRIMARY KEY        (`id_comment`),
                   KEY                (`status`,`id_article`),
                   KEY `id_user`      (`id_user`),
                   FULLTEXT           (`body`,`author`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_user (
                   `id_article`     int(11) unsigned NOT NULL default 0,
                   `id_user`        int(11) unsigned NOT NULL default 0,
                   KEY `id_article` (`id_article`),
                   KEY `id_user`    (`id_user`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);     

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_keyword (
                   `id_article`     int(11) unsigned NOT NULL default 0,
                   `id_key`         int(11) unsigned NOT NULL default 0,
                   KEY `id_article` (`id_article`),
                   KEY `id_key`     (`id_key`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_node_controller_rel (
                   `id_controller` int(11) unsigned NOT NULL default 0,
                   `id_node`       int(11) unsigned NOT NULL default 0,
                   UNIQUE KEY `id_node` (`id_node`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);      

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_controller_rel (
                   `id_controller` int(11) unsigned NOT NULL default 0,
                   `id_article`    int(11) unsigned NOT NULL default 0,
                   UNIQUE KEY `id_article` (`id_article`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_public_controller (
                   `id_controller` int(11) unsigned NOT NULL auto_increment,
                   `name`          varchar(255) NOT NULL default '',
                   PRIMARY KEY    (`id_controller`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);        

        $_default_config = array(
                 'thumb_width'           => 120,
                 'img_size_max'          => 500000,
                 'file_size_max'         => 5000000,
                 'default_lang'          => 'en',
                 'default_order'         => 'title',
                 'default_ordertype'     => 'asc',
                 'default_comment_status'=> 2,
                 'use_comment'           => 1,
                 'use_article_controller'=> 1,
                 'use_users'             => 0,
                 'use_keywords'          => 1,
                 'use_articledate'       => 0,
                 'use_changedate'        => 0,
                 'use_overtitle'         => 0,
                 'use_subtitle'          => 0,
                 'use_header'            => 0,
                 'use_description'       => 0,
                 'use_ps'                => 0,
                 'use_logo'              => 0,
                 'use_images'            => 1,
                 'use_files'             => 1);
                 
        $_config = serialize($_default_config);
  
        $sql = "INSERT INTO {$this->config->getVar('_dbTablePrefix')}common_module
                   (`name`, `alias`, `rank`, `version`, `visibility`, `perm`, `release`,`config`)
                  VALUES
                   ('article',
                    'Article Management',
                    3,
                    '0.6',
                    1,
                    60,
                    'DATE: 28.12.2005 AUTHOR: Armand Turpel <cms@open-publisher.net>',
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
        $sql = "DROP TABLE IF EXISTS {$this->config->getVar('_dbTablePrefix')}article_article,
                                     {$this->config->getVar('_dbTablePrefix')}article_index,
                                     {$this->config->getVar('_dbTablePrefix')}article_changedate,
                                     {$this->config->getVar('_dbTablePrefix')}article_lock,
                                     {$this->config->getVar('_dbTablePrefix')}article_media_pic,
                                     {$this->config->getVar('_dbTablePrefix')}article_media_file,
                                     {$this->config->getVar('_dbTablePrefix')}article_comment,
                                     {$this->config->getVar('_dbTablePrefix')}article_controller,
                                     {$this->config->getVar('_dbTablePrefix')}article_controller_rel,
                                     {$this->config->getVar('_dbTablePrefix')}article_keyword,
                                     {$this->config->getVar('_dbTablePrefix')}article_public_controller,
                                     {$this->config->getVar('_dbTablePrefix')}article_user,
                                     {$this->config->getVar('_dbTablePrefix')}article_node_controller_rel";
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