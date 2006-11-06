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
 * Init action of the user module 
 *
 *
 */

class ActionUserInit extends JapaAction
{
    /**
     * User Module Version
     */
    const MOD_VERSION = '0.2';    
    
    /**
     * Run init process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        $this->checkModuleVersion();
        
        // load module config variables
        $this->loadConfig();
        
        if($this->model->session->exists('loggedUserId'))
        {
            // Update the access time of the logged user
            $this->model->action('user','access',
                                 array('job'     => 'update',
                                       'id_user' => (int)$this->model->session->get('loggedUserId')));
        }
        
        if($this->config['user']['use_log'] == 1)
        {
            $this->config['user']['log_id_session'] = $this->model->session->get('logIdSession');
        }
    } 
    /**
     * Check module version and upgrade or install this module if necessairy
     *
     */    
    private function checkModuleVersion()
    {
        // get user module info
        $info = $this->model->getModuleInfo('user');

        // need install or upgrade?
        if(0 != version_compare($info['version'], self::MOD_VERSION))
        {
            // Upgrade this module
            $this->model->action('user','upgrade',array('new_version' => self::MOD_VERSION));           
        }
          
        unset($info);
    }
    
    /**
     * Load config values
     *
     */    
    private function loadConfig()
    {
        $sql = "SELECT SQL_CACHE * FROM {$this->config['dbTablePrefix']}user_config";
        
        $rs = $this->model->dba->query($sql);
        
        $fields = $rs->fetchAssoc();

        foreach($fields as $key => $val)
        {
            $this->config['user'][$key] = $val;      
        } 
    }    
    
    public function validate( $data = false )
    {
        return true;
    }    
}

?>