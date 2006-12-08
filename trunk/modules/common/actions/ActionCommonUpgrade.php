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

class ActionCommonUpgrade extends JapaAction
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
        }
        if(0 == version_compare('0.2', $data['old_version']) )
        {
            // upgrade from module version 0.2 to 0.3
            $this->upgrade_0_2_to_0_3();          
        }
        if(0 == version_compare('0.3', $data['old_version']) )
        {
            // upgrade from module version 0.3 to 0.4
            $this->upgrade_0_3_to_0_4();          
        }
        if(0 == version_compare('0.4', $data['old_version']) )
        {
            // upgrade from module version 0.3 to 0.4
            $this->upgrade_0_4_to_0_5();          
        }

        if(0 == version_compare('0.5', $data['old_version']) )
        {
            // upgrade from module version 0.5 to 0.6
            $this->upgrade_0_5_to_0_6();          
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
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}common_config
                ADD session_maxlifetime int(11) NOT NULL default 7200 
                AFTER max_lock_time";
               
        $this->model->dba->query($sql);
        $data['old_version'] = '0.2';
    }

    /**
     * upgrade from module version 0.2 to 0.3
     *
     */
    private function upgrade_0_2_to_0_3()
    {
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}common_config
                ADD `smart_version_num` varchar(20) NOT NULL 
                AFTER `charset`";
               
        $this->model->dba->query($sql);
        $data['old_version'] = '0.3';
    }

    /**
     * upgrade from module version 0.3 to 0.4
     *
     */
    private function upgrade_0_3_to_0_4()
    {
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}common_config
                ADD `smart_version_num` varchar(20) NOT NULL 
                AFTER `charset`";
               
        $this->model->dba->query($sql);
        $data['old_version'] = '0.4';
    }

    /**
     * upgrade from module version 0.4 to 0.5
     * Open Publisher
     *
     */
    private function upgrade_0_4_to_0_5()
    {
        $server_timezone = (int)(date("Z") / 3600);
        
        if( ($server_timezone < -12 ) || ($server_timezone > -12 ) )
        {
            $server_timezone = 1;
        }
        
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}common_config
                ADD `server_gmt` tinyint(2) NOT NULL default {$server_timezone} 
                AFTER `textarea_rows`";
               
        $this->model->dba->query($sql);
        
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}common_config
                ADD `default_gmt` tinyint(2) NOT NULL default {$server_timezone}  
                AFTER `textarea_rows`";
               
        $this->model->dba->query($sql);
        
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}common_config
                ADD `css_folder` varchar(255) NOT NULL default 'css_home' 
                AFTER `templates_folder`";
               
        $this->model->dba->query($sql);

        $sql = "ALTER TABLE {$this->config->dbTablePrefix}common_config
                ADD `op_version` varchar(20) NOT NULL default '1.0' 
                AFTER `smart_version_num`";
               
        $this->model->dba->query($sql);
        $this->config['op_version'] = '1.0';
        
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}common_config
                ADD `recycler_time` int(11) NOT NULL default 7200";
               
        $this->model->dba->query($sql);
        
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}common_config
                DROP `smart_version_num`";
               
        $this->model->dba->query($sql);
        
        $data['old_version'] = '0.5';
    }

    /**
     * upgrade from module version 0.4 to 0.5
     * Open Publisher
     *
     */
    private function upgrade_0_5_to_0_6()
    {
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}common_config
                  CHANGE `views_folder` `controllers_folder` varchar(255) NOT NULL default ''";
        $this->model->dba->query($sql);   
        
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}common_config
                  CHANGE `templates_folder` `views_folder` varchar(255) NOT NULL default ''";
        $this->model->dba->query($sql); 
        
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}common_config
                  CHANGE `css_folder` `styles_folder` varchar(255) NOT NULL default ''";
        $this->model->dba->query($sql);  
        
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}common_module
                ADD `config` text NOT NULL";    
        $this->model->dba->query($sql);
        
        $data['old_version'] = '0.6';
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
                        `name`='common'";

        $this->model->dba->query($sql);          
    }  
}

?>