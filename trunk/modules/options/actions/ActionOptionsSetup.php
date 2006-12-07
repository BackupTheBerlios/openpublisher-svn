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
 * Setup action of the common module 
 *
 */
 
class ActionOptionsSetup extends JapaAction
{
    /**
     * Run setup process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        if(isset($data['rollback']))
        {
            return TRUE;
        }

        $server_timezone = (int)(date("Z") / 3600);
        
        if( ($server_timezone < -12 ) || ($server_timezone > -12 ) )
        {
            $server_timezone = 1;
        }

        $_default_config = array(
                 `op_version`          => '1.1a',
                 `charset`             => '{$this->config->getVar("_charset")}',
                 `site_url`            => '',
                 `views_folder`        => 'default/',
                 `styles_folder`       => 'default/',
                 `controllers_folder`  => 'default/',
                 `disable_cache`       => 1,
                 `textarea_rows`       => 25,
                 `server_gmt`          => $server_timezone,
                 `default_gmt`         => $server_timezone,
                 `recycler_time`       => 7200,
                 `max_lock_time`       => 7200,
                 `session_maxlifetime` => 7200,
                 `rejected_files`      => '.php,.php3,.php4,.php5,.phps,.pl,.py,.phps');

        $sql = "INSERT INTO {$this->config->getVar('_dbTablePrefix')}common_module
                   (`name`, `alias`, `rank`, `version`, `visibility`, `perm`, `release`,`config`)
                  VALUES
                   ('options',
                    'Global Options Management',
                    7,
                    '0.1',
                    1,
                    20,
                    'DATE: 25.7.2005 AUTHOR: Armand Turpel <cms@open-publisher.net>',
                    '{serialize($_default_config)}')";
                    
        $this->model->dba->query($sql);            
    } 
    
    /**
     * validate $data
     *
     */ 
    public function validate( $data = FALSE )
    {
        return true;
    }
}

?>