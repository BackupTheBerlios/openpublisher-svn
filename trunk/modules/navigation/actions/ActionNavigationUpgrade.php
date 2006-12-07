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
 * ActionNavigationUpgrade
 *
 * USAGE:
 * $model->action( 'navigation', 'upgrade', 
 *                 array('new_version' => string ); // new module version
 *
 */

class ActionNavigationUpgrade extends JapaAction
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
        if(0 == version_compare('0.1', $data['old_version'], '=') )
        {
            // upgrade from module version 0.1 to 0.2
            $this->upgrade_0_1_to_0_2();     
            $data['old_version'] = '0.2';
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
        $sql = "RENAME TABLE 
                   {$this->config->dbTablePrefix}navigation_view 
                   TO
                   {$this->config->dbTablePrefix}navigation_public_controller";
        $this->model->dba->query($sql);      

        $sql = "ALTER TABLE {$this->config->dbTablePrefix}navigation_public_controller
                  CHANGE `id_view` `id_controller` unsigned INT(11) NOT NULL auto_increment";
        $this->model->dba->query($sql);      
        
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}navigation_node
                  CHANGE `id_view` `id_controller` INT(11)";
        $this->model->dba->query($sql);  
        
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}navigation_node
                  DROP INDEX `view`";
        $this->model->dba->query($sql);  
        
        $sql = "ALTER TABLE {$this->config->dbTablePrefix}navigation_node
                  ADD KEY `id_controller` (`id_controller`)";
        $this->model->dba->query($sql);  
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
                        `name`='navigation'";

        $this->model->dba->query($sql);          
    }   
    
    public function validate( $data = false )
    {
        return true;
    }
}

?>