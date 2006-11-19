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
 * ActionCommonUpgrade
 *
 * USAGE:
 * $model->action( 'common', 'upgrade', 
 *                 array('new_version' => string ); // new module version
 *
 */

class ActionUserUpgrade extends JapaAction
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
        if(1 == version_compare('0.1', $this->config['module']['user']['version'], '=') )
        {
            // upgrade from module version 0.1 to 0.2
            $this->upgrade_0_1_to_0_2();          
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
        $sql = "ALTER TABLE {$this->config['dbTablePrefix']}user_user 
                ADD `user_gmt` tinyint(2) NOT NULL default 1
                AFTER `status`";
               
        $this->model->dba->query($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS {$this->config['dbTablePrefix']}user_log (
                   `id_log`       int(11) unsigned NOT NULL auto_increment,
                   `id_session`   int(11) unsigned NOT NULL default 0,
                   `logdate`      datetime NOT NULL default '0000-00-00 00:00:00',
                   PRIMARY KEY     (`id_log`),
                   KEY `id_session`(`id_session`),
                   KEY `logdate`   (`logdate`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);   
       
        $sql = "CREATE TABLE IF NOT EXISTS {$this->config['dbTablePrefix']}user_log_info (
                   `id_log`       int(11) unsigned NOT NULL auto_increment,
                   `module`       varchar(30) NOT NULL default '',
                   `type`         tinyint(1) unsigned NOT NULL default 1,
                   `view`         varchar(30) NOT NULL default '',
                   `id_item`      int(11) unsigned NOT NULL default 0,
                   `message`      text CHARACTER SET {$this->config['dbcharset']} NOT NULL default '',
                   PRIMARY KEY   (`id_log`),
                   KEY `type`    (`type`),
                   KEY `module`  (`module`,`view`,`id_item`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);   
        
        $sql = "CREATE TABLE IF NOT EXISTS {$this->config['dbTablePrefix']}user_log_session (
                   `id_session`   int(11) unsigned NOT NULL auto_increment,
                   `id_user`      int(11) unsigned NOT NULL default 0,
                   `agent`        varchar(255) NOT NULL default '',
                   `ip`           varchar(255) NOT NULL default '',
                   `host`         varchar(255) NOT NULL default '',
                   PRIMARY KEY    (`id_session`),
                   KEY `id_user`  (`id_user`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);  

        $sql = "ALTER TABLE {$this->config['dbTablePrefix']}user_config
                ADD `use_log` tinyint(1) NOT NULL default 0";
               
        $this->model->dba->query($sql);      
        
        $this->config['module']['user']['version'] = '0.2';
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
        $sql = "UPDATE {$this->config['dbTablePrefix']}common_module
                    SET
                        `version`='{$version}'
                    WHERE
                        `id_module`={$this->config['module']['user']['id_module']}";

        $this->model->dba->query($sql);          
    }   
}

?>