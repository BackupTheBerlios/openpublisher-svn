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
 * ActionCommonCheckFolderRights 
 *
 * USAGE:
 *
 * $model->action('common','checkFolderRights', array('error' => & array() )); 
 */
 
class ActionCommonCheckFolderRights extends SmartAction
{
    /**
     * check if folders are writeable by php scripts
     *
     */
    public function perform( $data = FALSE )
    {
        $captcha_folder = SMART_BASE_DIR . 'data/common/captcha';
        if(!is_writeable($captcha_folder))
        {
            $data['error'][] = 'Must be writeable by php scripts: '.$captcha_folder;    
        }

        $config_folder = $this->model->config['config_path'];
        if(!is_writeable($config_folder))
        {
            $data['error'][] = 'Must be writeable by php scripts: '.$config_folder;    
        }

        $logs_folder = $this->model->config['logs_path'];
        if(!is_writeable($logs_folder))
        {
            $data['error'][] = 'Must be writeable by php scripts: '.$logs_folder;    
        }
        
        $cache_folder = $this->model->config['cache_path'];
        if(!is_writeable($cache_folder))
        {
            $data['error'][] = 'Must be writeable by php scripts: '.$cache_folder;    
        }          

        $smartyCompiled = $this->model->config['cache_path'] . 'smartyCompiled';
        if(!is_writeable($smartyCompiled))
        {
            $data['error'][] = 'Must be writeable by php scripts: '.$smartyCompiled;    
        }    

        $tinymce_cache_folder = SMART_BASE_DIR . 'data/common/tinymce_cache';
        if(!is_writeable($tinymce_cache_folder))
        {
            $data['error'][] = 'Must be global readable, and writeable by php scripts: '.$tinymce_cache_folder;    
        }

        $rss_cache = SMART_BASE_DIR . 'data/common/rss_cache';
        if(!is_writeable($rss_cache))
        {
            $data['error'][] = 'Must be global readable, and writeable by php scripts: '.$rss_cache;    
        }
        
        return TRUE;
    } 
    /**
     * validate $data
     *
     */ 
    public function validate( $data = FALSE )
    {
        if(!isset($data['error']))
        {
            throw new SmartModelException("'error' isnt defined");
        }
        if(!is_array($data['error']))
        {
            throw new SmartModelException("'error' isnt from type array");
        }
        
        return TRUE;
    }
}

?>