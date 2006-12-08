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
 * ActionMiscUpgrade
 *
 * USAGE:
 * $model->action( 'misc', 'upgrade', 
 *                 array('new_version' => string ); // new module version
 *
 */

class ActionMiscUpgrade extends JapaAction
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
            //$this->upgrade_0_1_to_0_2();          
        }
       
        // update to new module version number
        //$this->setNewModuleVersionNumber( $data['new_version'] ); 
    }

    /**
     * upgrade from module version 0.1 to 0.2
     *
     */
    private function upgrade_0_1_to_0_2()
    {
        $data['old_version'] = '0.2';
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
                        `name`='misc'";

        $this->model->dba->query($sql);          
    }  
    
}

?>