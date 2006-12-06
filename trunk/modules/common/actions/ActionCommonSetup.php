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
 
class ActionCommonSetup extends JapaAction
{
    /**
     * Run setup process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        if(!isset($data['rollback']))
        {
            $this->checkGd();
        }
        
        $this->config->setVar('_dbTablePrefix', $data['dbtablesprefix']);    
        $this->config->setVar('_dbhost'],       $data['dbhost']);
        $this->config->setVar('_dbport'],       $data['dbport']);
        $this->config->setVar('_dbuser'],       $data['dbuser']);
        $this->config->setVar('_dbpasswd'],     $data['dbpasswd']);
        $this->config->setVar('_dbname'],       $data['dbname']);
        $this->config->setVar('_dbcharset'],    $this->mysqlEncoding( $data['charset'] ));

        try
        {
            $this->model->dba = new DbMysql(  $data['dbhost'] ,
                                              $data['dbuser'],
                                              $data['dbpasswd'],
                                              $data['dbname'],
                                              $data['dbport']);
                                              
            $this->model->dba->connect();  
            $this->model->dba->query("SET NAMES '{$this->config->getVar('_dbcharset')}'"); 
        }
        catch(JapaDbException $e)
        {
            // if no database connection stop here
            throw new Exception("Connection to the database (server?) fails. Please check connection data!",0);
        }
        
        // Rollback if there are somme error in other modules setup actions
        if(isset($data['rollback']))
        {
            $this->rollback($data);
            return TRUE;
        }

        $sql = "CREATE TABLE IF NOT EXISTS {$this->config->getVar('_dbTablePrefix')}common_session (
                 `session_id`   varchar(32) NOT NULL default '', 
                 `modtime`      int(11) NOT NULL default '0',
                 `session_data` text NOT NULL default '',
                 PRIMARY KEY   (`session_id`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $server_timezone = (int)(date("Z") / 3600);
        
        if( ($server_timezone < -12 ) || ($server_timezone > -12 ) )
        {
            $server_timezone = 1;
        }
            
        $sql = "CREATE TABLE IF NOT EXISTS {$this->config->getVar('_dbTablePrefix')}common_config (
                 `op_version`          varchar(20) NOT NULL default '',
                 `charset`             varchar(50) NOT NULL default '',
                 `site_url`            varchar(255) NOT NULL default '',
                 `views_folder`        varchar(255) NOT NULL default '',
                 `styles_folder`       varchar(255) NOT NULL default '',
                 `controllers_folder`  varchar(255) NOT NULL default '',
                 `disable_cache`       tinyint(1) NOT NULL default 1,
                 `textarea_rows`       tinyint(2) NOT NULL default 25,
                 `server_gmt`          tinyint(2) NOT NULL default {$server_timezone},
                 `default_gmt`         tinyint(2) NOT NULL default {$server_timezone},
                 `recycler_time`       int(11) NOT NULL default 7200,
                 `max_lock_time`       int(11) NOT NULL default 7200,
                 `session_maxlifetime` int(11) NOT NULL default 7200,
                 `rejected_files`      text NOT NULL default '') 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);



        $sql = "INSERT INTO {$this->config->getVar('_dbTablePrefix')}common_config
                 (`op_version`,`charset`, `templates_folder`, `css_folder`, 
                  `views_folder`,`rejected_files`)
                VALUES
                 ('1.0a','{$data['charset']}', 'templates_home/', 'css_home/',
                  'views_home/', '.php,.php3,.php4,.php5,.phps,.pl,.py')";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$this->config->getVar('_dbTablePrefix')}common_module (
                 `id_module`   int(11) NOT NULL auto_increment,
                 `rank`        smallint(3) NOT NULL default 0,
                 `name`        varchar(255) NOT NULL,
                 `alias`       varchar(255) NOT NULL,
                 `version`     varchar(255) NOT NULL,
                 `visibility`  tinyint(1) NOT NULL default 0,
                 `perm`        tinyint(3) NOT NULL default 0,
                 `release`     text latin1_general_ci NOT NULL,
                 `config`      text latin1_general_ci NOT NULL,
                 PRIMARY KEY   (`id_module`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "INSERT INTO {$this->config->getVar('_dbTablePrefix')}common_module
                 (`name`, `alias`, `rank`, `version`, `visibility`, `perm`, `release`)
                VALUES
                 ('common','', 0,'0.6',0,10,'DATE: 6.5.2005 AUTHOR: Armand Turpel <cms@open-publisher.net>')";
        $this->model->dba->query($sql);            

        return TRUE;
    } 

    /**
     * Check if GD extension is loaded
     *
     */ 
    private function checkGd()
    {
        if(! extension_loaded('gd'))
        {
            throw new Exception('The php extension GD is required !');    
        }    
    }

    /**
     * Get mysql charset encoding
     * 
     * @param string $charset 
     * @return string Mysql encoding
     */    
    public function mysqlEncoding( $charset )
    {
        $_charset = array("iso-8859-1"   => 'latin1',
                          "iso-8859-2"   => 'latin2',
                          "iso-8859-13"  => 'latin7',
                          "iso-8859-7"   => 'greek',
                          "iso-8859-8"   => 'hebrew',
                          "iso-8859-9"   => 'latin5',
                          "utf-8"        => 'utf8',
                          "windows-1250" => 'cp1250',
                          "windows-1256" => 'cp1256',
                          "windows-1257" => 'cp1257',
                          "windows-1251" => 'cp1251',
                          "GB2312"       => 'gb2312',
                          "Big5"         => 'big5',
                          "EUC-KR"       => 'euckr',
                          "TIS-620"      => 'tis620',
                          "EUC-JP"       => 'ujis',
                          "KOI8-U"       => 'koi8u',
                          "KOI8-R"       => 'koi8r');
                          
        if(isset($_charset[$charset])) 
        {
            return $_charset[$charset];
        }
        else
        {
            throw new JapaModelException('Charset not supported: '.$charset);
        }
    } 
    
    /**
     * Rollback setup
     * Delete db tables of this module 
     *
     */    
    public function rollback( &$data )
    {
        if(is_resource($this->model->db))
        {
            $sql = "DROP TABLE IF EXISTS 
                        {$data['dbtablesprefix']}common_module,
                        {$data['dbtablesprefix']}common_config,
                        {$data['dbtablesprefix']}common_session";
            $this->model->dba->query($sql); 
        }
        
        if(file_exists($this->config->getVar('config_path') . 'dbConnect.php'))
        {
            @unlink($this->config->getVar('config_path') . 'dbConnect.php');
        }
    }     
}

?>