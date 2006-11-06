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
 * Modul-Dependencies:
 * - Navigation Modul
 * - Keyword Modul
 *
 *
 */

class ActionArticleInit extends JapaAction
{
    /**
     * Navigation Module Version
     */
    const MOD_VERSION = '0.5';    
    
    /**
     * Run init process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        $this->loadConfig();
        $this->checkModuleVersion();
        
        $this->model->action('article','changedateStatus');
        
        // delete expired articles
        if($this->config['controller_type'] == 'admin')
        {
            $this->model->action('article','deleteExpired');
        }
    } 
    /**
     * Check module version and upgrade or install this module if necessairy
     *
     */    
    private function checkModuleVersion()
    {
        // get user module info
        $info = $this->model->getModuleInfo('article');

        // need install or upgrade?
        if(0 != version_compare($info['version'], self::MOD_VERSION))
        {
            // Upgrade this module
            $this->model->action('article','upgrade',array('new_version' => self::MOD_VERSION));           
        }
        
        unset($info);
    }
    
    /**
     * Load config values
     *
     */    
    private function loadConfig()
    {
        $sql = "SELECT SQL_CACHE * FROM {$this->config['dbTablePrefix']}article_config";
        
        $rs = $this->model->dba->query($sql);
        
        $fields = $rs->fetchAssoc();

        foreach($fields as $key => $val)
        {
            $this->config['article'][$key] = $val; 
        }
        
        // this module try to find the view on the related public request var 'id_article'
        $this->config['controller_map']['id_article'] = 'article';        
    }     
    
    public function validate( $data = false )
    { 
        return true;
    }  
}

?>