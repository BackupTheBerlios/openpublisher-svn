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
 * Init action of the Navigation module 
 *
 *
 */

class ActionNavigationInit extends JapaAction
{
    /**
     * Navigation Module Version
     */
    const MOD_VERSION = '0.2';    
    
    /**
     * Run init process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        $this->checkModuleVersion();
        
        $this->model->config->setModuleVar( 'navigation', 'id_item', 'id_node', false );
        
        $this->model->addToControllerMap( 'navigation', 'id_node' );
    } 
    /**
     * Check module version and upgrade or install this module if necessairy
     *
     */    
    private function checkModuleVersion()
    {
        // get user module info
        $info = $this->model->getModuleInfo('navigation');
        
        // need install or upgrade?
        if(0 != version_compare($info['version'], self::MOD_VERSION))
        {
            // Upgrade this module
            $this->model->action('navigation','upgrade',
                                 array('new_version' => self::MOD_VERSION,
                                       'old_version' => $info['version']));           
        }
        
        unset($info);
    }  
     
    public function validate( $data = false )
    {
        return true;
    } 
}

?>