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
 * Init action of the common module 
 *
 * First we do some init stuff that is common to all other modules.
 *
 */

// util class
include_once(JAPA_MODULES_DIR . 'common/includes/JapaCommonUtil.php');

// session handler class
require_once(JAPA_MODULES_DIR . 'common/includes/JapaSessionHandler.php');

// session class
require_once(JAPA_MODULES_DIR . 'common/includes/JapaCommonSession.php');

// get_magic_quotes_gpc
define ( 'JAPA_MAGIC_QUOTES', get_magic_quotes_gpc());

// set include path to additional pear packages
ini_set( 'include_path', '.' . PATH_SEPARATOR . JAPA_MODULES_DIR . 'common/includes/PEAR' . PATH_SEPARATOR . ini_get('include_path') );

class ActionCommonInit extends JapaAction
{
    /**
     * Open Publisher Version
     */
    const OPEN_PUBLISHER_VERSION = '1.1a';   
    
    /**
     * Common Module Version
     */
    const MOD_VERSION = '0.6';    
    
    /**
     * Run init process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        $mysqlExtension = $this->getMySqlExtensionType();
        // db class
        include_once(JAPA_MODULES_DIR . 'common/includes/Japa'.$mysqlExtension.'.php');
         
        // Check if a setup was successfull done else launch setup > 'setup' module
        if(file_exists($this->config['config_path'] . 'dbConnect.php'))
        {
            include_once($this->config['config_path'] . 'dbConnect.php');
        }
        else
        {
            throw new JapaForwardAdminViewException( $this->config['setup_module'] );        
        }

        // set db config vars
        $this->config['dbtype']        = 'mysql';
        $this->config['dbhost']        = $db['dbhost'];
        $this->config['dbuser']        = $db['dbuser'];
        $this->config['dbpasswd']      = $db['dbpasswd'];
        $this->config['dbname']        = $db['dbname'];
        $this->config['dbTablePrefix'] = $db['dbTablePrefix'];
        $this->config['dbcharset']     = $db['dbcharset'];

        try
        {
            $this->model->dba = new DbMysql( $db['dbhost']  ,$db['dbuser'],
                                             $db['dbpasswd'],$db['dbname'] );

            // enable debugging of sql queries
            $this->model->dba->debug = $this->config['debug']; 
                                              
            //$dbaOptions = array(MYSQLI_OPT_CONNECT_TIMEOUT => 5);
            $this->model->dba->connect();  
            $this->model->dba->query("SET NAMES '{$db['dbcharset']}'");        
        }
        catch(JapaDbException $e)
        {
            // if no database connection stop here
            throw new JapaModelException;
        }

        // load global config variables of the common module   
        $this->loadConfig(); 

        // load module descriptions into config array   
        $this->loadModulesInfo();         
        
        // check for module upgrade
        $this->checkModuleVersion();   
       
        // set session handler
        $this->model->sessionHandler = new JapaSessionHandler( $this->model->dba, $this->config['dbTablePrefix'] );

        // init and start session
        $this->startSession();
              
        // check for Open Publisher version upgrade
        $this->checkOpenPublisherVersion();
        
        // build gmt time and date
        $this->config['gmtTime'] = time() - $this->config['server_gmt'] * 3600;
        $this->config['gmtDate'] = date("Y-m-d H:i:s", $this->config['gmtTime']);

        // set base url and logged user vars, except if the cli controller is used
        if($this->config['controller_type'] != 'cli')
        {
            //$this->model->baseUrlLocation = $this->base_location();
            
            $this->config['loggedUserId']   = $this->model->session->get('loggedUserId');
            $this->config['loggedUserRole'] = $this->model->session->get('loggedUserRole');
            $this->config['loggedUserGmt']  = $this->model->session->get('loggedUserGmt');
            
            $this->config['user_gmt']       = $this->config['loggedUserGmt'];

            // if session var for public templates and css folders are defined
            // overwrite default ones
            if(  NULL != ($tplFolder = $this->model->session->get('templates_folder')) )
            {
                $this->config['templates_folder'] = $tplFolder;
            }
            if(  NULL != ($cssFolder = $this->model->session->get('css_folder')) )
            {
                $this->config['css_folder'] = $cssFolder;
            }  
        }

        // enable zlib output compression
        if($this->config['output_compression'] == TRUE)
        {
            ini_set('zlib.output_compression',       '1');     
            ini_set('zlib.output_compression_level', $this->config['output_compression_level']);
            ini_set('zlib.output_handler',           '');
        }
        
        // set charset
        ini_set( "default_charset",$this->config['charset']);
        @header( "Content-type: text/html; charset={$this->config['charset']}" );         
    } 

    /**
     * Load module descriptions in $this->config['module']['name']
     *
     */    
    private function loadModulesInfo()
    {
        $sql = "SELECT SQL_CACHE * FROM {$this->config['dbTablePrefix']}common_module ORDER BY `rank` ASC";
        
        $rs = $this->model->dba->query($sql);
        
        $this->config['module'] = array();
        
        while($row = $rs->fetchAssoc())
        {
            $this->config['module'][$row['name']] = $row;
            $this->config[$row['name']] = unserialize($row['config']);
            $this->model->register($row['name'], $row); 
        } 
    }

    /**
     * Load config values
     *
     */    
    private function loadConfig()
    {
        $sql = "SELECT SQL_CACHE * FROM {$this->config['dbTablePrefix']}common_config";
        
        $rs = $this->model->dba->query($sql);
        
        $fields = $rs->fetchAssoc();

        foreach($fields as $key => $val)
        {
            $this->config[$key] = $val;    
        } 
    }
    
    /**
     * Check module version and upgrade or install this module if necessairy
     *
     */    
    private function checkModuleVersion()
    {
        // need upgrade?
        if(0 != version_compare($this->config['module']['common']['version'], self::MOD_VERSION))
        {
            // Upgrade this module
            $this->model->action('common','upgrade',
                                 array('new_version' => self::MOD_VERSION));           
        }
    } 

    /**
     * Check smart core version and send message to all modules
     *
     */    
    private function checkOpenPublisherVersion()
    {
        if(0 != version_compare($this->config['op_version'], self::OPEN_PUBLISHER_VERSION))
        {
            $this->model->action('common','japaCoreNewVersion',
                                 array('new_version' => (string)self::OPEN_PUBLISHER_VERSION));           
        }
    } 
    
    /**
     * Get mysql extension type
     *
     */    
    private function getMySqlExtensionType()
    {
        if( function_exists('mysqli_init') )
        {
            return 'MySqli';
        }
        elseif( function_exists('mysql_connect') )
        {
            return 'MySql';
        } 
        else
        {
            throw new JapaModelException( "It seem that there isnt the php extension 'mysql' nor 'mysqli' installed on your system. Check this!" );        
        }
    }    
       
    /**
     * init and start session
     *
     */    
    private function startSession()
    {
        ini_set('session.gc_probability', 10);
        ini_set('session.gc_maxlifetime', $this->config['session_maxlifetime']);
        
        $this->model->session = new JapaCommonSession();
        // delete only expired session of the current user
        // this isnt the session garbage collector 
        $this->model->action('common', 'sessionDeleteExpired');   
    }
    
    public function validate( $data = false )
    { 
        return true;
    }  
}

?>