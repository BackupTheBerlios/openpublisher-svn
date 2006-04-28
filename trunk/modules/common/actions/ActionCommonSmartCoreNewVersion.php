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
 * ActionCommonSmartCoreNewVersion
 *
 * Delete Tinymce cache
 *
 * USAGE:
 * $model->action( 'common', 'smartCoreNewVersion', 
 *                 array('new_version' => (string) );
 *
 */

class ActionCommonSmartCoreNewVersion extends SmartAction
{
    /**
     * perform
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        // delete tiny Mce cache
        $this->model->action('common', 'deleteTinymceCache');
        
        // set new smart core version number in db
        $this->setNewSmartCoreVersionNumber( $data['new_version'] );
    }
    
    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if(!is_string($data['new_version']))
        {
            throw new SmartModelException("'new_version' isnt from type string");
        }    
        return TRUE;
    }   
    
    /**
     * update to new Open Publisher version number
     *
     * @param string $version  version number
     */
    private function setNewSmartCoreVersionNumber( $version )
    {
        $sql = "UPDATE {$this->config['dbTablePrefix']}common_config
                    SET
                        `op_version`='{$version}'";

        $this->model->dba->query($sql);          
    }       
}

?>