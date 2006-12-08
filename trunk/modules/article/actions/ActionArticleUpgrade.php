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
 * ActionArticleUpgrade
 *
 * USAGE:
 * $model->action( 'article', 'upgrade', 
 *                 array('new_version' => string ); // new module version
 *
 */

class ActionArticleUpgrade extends JapaAction
{
    /**
     * Do upgrade
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        // do upgrade
        //
        if(0 == version_compare('0.1', $data['old_version']) )
        {
            // upgrade from module version 0.1 to 0.2
            $this->upgrade_0_1_to_0_2();     
            $data['old_version'] = '0.2';
        }

        if(0 == version_compare('0.2', $data['old_version']) )
        {
            // upgrade from module version 0.2 to 0.3
            $this->upgrade_0_2_to_0_3();     
            $data['old_version'] = '0.3';
        }

        if(0 == version_compare('0.3', $data['old_version']) )
        {
            // upgrade from module version 0.3 to 0.4
            $this->upgrade_0_3_to_0_4();     
            $data['old_version'] = '0.4';
        }
        
        if(0 == version_compare('0.4', $data['old_version']) )
        {
            // upgrade from module version 04 to 0.5
            $this->upgrade_0_4_to_0_5();     
            $data['old_version'] = '0.5';
        }
        
        if(0 == version_compare('0.5', $data['old_version']) )
        {
            // upgrade from module version 04 to 0.5
            $this->upgrade_0_5_to_0_6();     
            $data['old_version'] = '0.6';
        }
        
        // update to new module version number
        $this->setNewModuleVersionNumber( $data['new_version'] ); 
    }

    /**
     * upgrade from module version 0.1 to 0.2
     *
     */
    private function upgrade_0_1_to_0_2()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->config->dbTablePrefix}article_node_view_rel (
                   `id_view`      int(11) unsigned NOT NULL default 0,
                   `id_node`      int(11) unsigned NOT NULL default 0,
                   UNIQUE KEY `id_node` (`id_node`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);      

        $sql = "CREATE TABLE IF NOT EXISTS {$this->config->dbTablePrefix}article_view (
                   `id_view`      int(11) unsigned NOT NULL auto_increment,
                   `name`         varchar(255) NOT NULL default '',
                   PRIMARY KEY    (`id_view`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);      
    }

    /**
     * upgrade from module version 0.2 to 0.3
     *
     */
    private function upgrade_0_2_to_0_3()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->config->dbTablePrefix}article_view_rel (
                   `id_view`      int(11) unsigned NOT NULL default 0,
                   `id_article`   int(11) unsigned NOT NULL default 0,
                   UNIQUE KEY `id_article` (`id_article`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);    

        $sql = "ALTER TABLE {$this->config->dbTablePrefix}article_config
                ADD `use_article_view` tinyint(1) NOT NULL default 0 
                AFTER `default_ordertype`";
               
        $this->model->dba->query($sql);

        $rss_folder = JAPA_BASE_DIR . 'data/article/rss';
        if(!is_writeable($rss_folder))
        {
            trigger_error('Must be writeable by php scripts: '.$rss_folder, E_USER_WARNING);    
        }  
    }

    /**
     * upgrade from module version 0.3 to 0.4
     *
     */
    private function upgrade_0_3_to_0_4()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->config->dbTablePrefix}article_comment (
                   `id_comment`    int(11) unsigned NOT NULL auto_increment,
                   `id_article`    int(11) unsigned NOT NULL default 1,
                   `id_user`       int(11) unsigned NOT NULL default 0,
                   `status`        tinyint(1) NOT NULL default 0,
                   `pubdate`       datetime NOT NULL default '0000-00-00 00:00:00',
                   `author`        varchar(100) CHARACTER SET {$this->config->getVar('dbcharset')} NOT NULL default '',
                   `email`         varchar(100) NOT NULL default '',
                   `url`           varchar(255) NOT NULL default '',
                   `ip`            varchar(100) NOT NULL default '',
                   `agent`         varchar(255) NOT NULL default '',
                   `body`          text CHARACTER SET {$this->config->getVar('dbcharset')} NOT NULL default '',                  
                   PRIMARY KEY        (`id_comment`),
                   KEY                (`status`,`id_article`),
                   KEY `id_user`      (`id_user`),
                   FULLTEXT           (`body`,`author`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        
        $this->model->dba->query($sql);

        $sql = "ALTER TABLE {$this->config->dbTablePrefix}article_article
                ADD `allow_comment` tinyint(1) NOT NULL default 0,
                ADD `close_comment` tinyint(1) NOT NULL default 0";
               
        $this->model->dba->query($sql);
        
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}article_config
                ADD `use_comment` tinyint(1) NOT NULL default 0
                  AFTER `default_ordertype`,
                ADD `default_comment_status`  tinyint(1) NOT NULL default 1
                  AFTER `default_ordertype`";
               
        $this->model->dba->query($sql);      
    }

    /**
     * upgrade from module version 0.4 to 0.5
     *
     */
    private function upgrade_0_4_to_0_5()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->config->dbTablePrefix}article_user (
                   `id_article`     int(11) unsigned NOT NULL default 0,
                   `id_user`        int(11) unsigned NOT NULL default 0,
                   KEY `id_article` (`id_article`),
                   KEY `id_user`    (`id_user`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);
        
        $sql = "UPDATE {$this->config->dbTablePrefix}common_module
                    SET
                        `perm`=60
                    WHERE
                        `name`='article'";

        $this->model->dba->query($sql);     
    }
    
    /**
     * upgrade from module version 0.5 to 0.6
     *
     */
    private function upgrade_0_5_to_0_6()
    {
        $sql = "RENAME TABLE 
                   {$this->config->dbTablePrefix}article_node_view_rel 
                   TO
                   {$this->config->dbTablePrefix}article_node_controller_rel";
        $this->model->dba->query($sql);   
        
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}article_node_controller_rel
                  CHANGE `id_view` `id_controller` INT(11) unsigned";
        $this->model->dba->query($sql);

        $sql = "RENAME TABLE 
                   {$this->config->dbTablePrefix}article_view_rel
                   TO
                   {$this->config->dbTablePrefix}article_controller_rel";
        $this->model->dba->query($sql);  
        
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}article_controller_rel
                  CHANGE `id_view` `id_controller` INT(11) unsigned";
        $this->model->dba->query($sql); 
        
        $sql = "RENAME TABLE 
                   {$this->config->dbTablePrefix}article_view
                   TO
                   {$this->config->dbTablePrefix}article_public_controller";
        $this->model->dba->query($sql);   
        
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}article_public_controller
                  CHANGE `id_view` `id_controller` INT(11) unsigned NOT NULL auto_increment";
        $this->model->dba->query($sql);   
        
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}article_config
                  CHANGE `use_article_view` `use_article_controller` tinyint(1) NOT NULL default 0 ";
        $this->model->dba->query($sql); 
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if(!isset($data['new_version']))
        {
            throw new JapaModelException('data var "new_version" is required');        
        }  
        if(!is_string($data['new_version']))
        {
            throw new JapaModelException('data var "new_version" isnt from type string');        
        }   
        
        return TRUE;
    }    
    
    /**
     * update to new module version number
     *
     * @param string $version  New module version number
     */
    private function setNewModuleVersionNumber( $version )
    {
        $sql = "UPDATE {$this->config->dbTablePrefix}common_module
                    SET
                        `version`='{$version}'
                    WHERE
                        `name`='article'";

        $this->model->dba->query($sql);          
    }  
}

?>