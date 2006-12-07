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
 * ControllerSetupIndex class
 *
 */
 
class ControllerSetupIndex extends JapaControllerAbstractPage
{
    /**
     * Launch setup process
     *
     */
    public function perform( $data = FALSE )
    { 
        // Init setup_config array
        $this->controllerVar['setup_config'] = array();
        // Init setup_error array
        $this->viewVar['error']  = array();
        
        // Init setup_error array
        $this->viewVar['folder_error']  = array();   
        $this->viewVar['adminWebController']  = $this->config->getVar('default_module_application_controller');     
        // get url base
        $this->viewVar['url_base'] = $this->httpRequest->getBaseUrl();
        $this->controllerVar['url_base'] = $this->viewVar['url_base']; 
        
        // Send a broadcast setup message to all modules to check folder rights 
        $this->model->broadcast( 'checkFolderRights', array('error' => & $this->viewVar['folder_error']));

        $do_setup = $this->httpRequest->getParameter('do_setup', 'post', 'alnum');

        // launch setup
        if( !empty($do_setup) && (TRUE == $this->validate()) && (count($this->viewVar['folder_error']) == 0) )
        {
            try
            {                
                // if insert sample content, use always utf-8 charset
                if(!empty($this->insert_sample_content))
                {
                    $this->charset = 'utf-8';     
                }
                
                $data = array('superuser_passwd' => JapaCommonUtil::stripSlashes($this->syspassword),
                              'dbtablesprefix'   => JapaCommonUtil::stripSlashes($this->dbtablesprefix),
                              'dbhost'           => JapaCommonUtil::stripSlashes($this->dbhost),
                              'dbport'           => JapaCommonUtil::stripSlashes($this->dbport),
                              'dbuser'           => JapaCommonUtil::stripSlashes($this->dbuser),
                              'dbpasswd'         => JapaCommonUtil::stripSlashes($this->dbpasswd),
                              'dbname'           => JapaCommonUtil::stripSlashes($this->dbname),
                              'charset'          => JapaCommonUtil::stripSlashes($this->charset),
                              'config'           => & $this->controllerVar['setup_config']); 
                              
                // Send a broadcast setup message to all modules  
                $this->model->broadcast( 'setup', $data );            

                $_db = array( 'dbtablesprefix'   => $this->dbtablesprefix,
                              'dbhost'           => $this->dbhost,
                              'dbport'           => $this->dbport,
                              'dbuser'           => $this->dbuser,
                              'dbpasswd'         => $this->dbpasswd,
                              'dbname'           => $this->dbname,
                              'charset'          => $this->config->getVar('_dbcharset'));

                // write config file with database connection settings      
                $this->model->action( $this->config->getVar('base_module'),'setDbConfig', 
                                      array( 'dbConnect' => & $_db) );     

                // insert sample content
                if(!empty($this->insert_sample_content))
                {
                    $this->model->action('setup','insertSampleContent', 
                                         array('prefix' => $this->dbtablesprefix));     
                }
                
                // reload the admin interface after successfull setup
                ob_clean();
                $this->router->redirect( $this->viewVar['adminWebController'] );
            }
            catch(JapaDbException $e)
            {
                // set path to the log file
                $e->flag['logs_path'] = $this->config->getVar('logs_path');
                JapaExceptionLog::log( $e );
                $this->viewVar['error'][] = $e->getMessage();

                // Rollback all module setup actions 
                $this->rollback();
            }  
            catch(JapaModelException $e)
            {
                // set path to the log file
                $e->flag['logs_path'] = $this->config->getVar('logs_path');
                JapaExceptionLog::log( $e );
                $this->viewVar['error'][] = $e->getMessage();             
                $this->rollback();
            }   
            catch(Exception $e)
            {
                // set path to the log file
                $e->flag['logs_path'] = $this->config->getVar('logs_path');
                // log this exception
                JapaExceptionLog::log( $e );
                // set template error variables                
                $this->viewVar['error'][] = $e->getMessage();
            }            
        }

        // Fill up the form field variables with posted data        
        if(!empty($this->charset))
        {
          $this->viewVar['charset'] = JapaCommonUtil::stripSlashes($this->charset);   
        }             
        if(!empty($this->dbhost))
        {
          $this->viewVar['form_dbhost'] = JapaCommonUtil::stripSlashes($this->dbhost);   
        }
        if(!empty($this->dbport))
        {
          $this->viewVar['form_dbport'] = JapaCommonUtil::stripSlashes($this->dbport);   
        }    
        elseif(!isset($this->viewVar['form_dbport']))
        {
          $this->viewVar['form_dbport'] = '3306';           
        }
        if(!empty($this->dbuser))
        {
          $this->viewVar['form_dbuser'] = JapaCommonUtil::stripSlashes($this->dbuser);   
        }        
        if(!empty($this->dbname))
        {
          $this->viewVar['form_dbname'] = JapaCommonUtil::stripSlashes($this->dbname);   
        }
        if(!empty($this->dbpasswd))
        {
          $this->viewVar['form_dbpasswd'] = JapaCommonUtil::stripSlashes($this->dbpasswd);   
        }        
        if(!empty($this->dbtablesprefix))
        {
          $this->viewVar['form_dbtableprefix'] = JapaCommonUtil::stripSlashes($this->dbtablesprefix);   
        }        
        if(!empty($this->syspassword))
        {
          $this->viewVar['form_syspassword'] = JapaCommonUtil::stripSlashes($this->syspassword);   
        }        
      
        return TRUE;
    }   
    /**
     * Validate form data
     *
     * @return bool true on success else false
     */    
    private function validate()
    {
        $this->insert_sample_content = $this->httpRequest->getParameter('insert_sample_content', 'post', 'alnum');
        $this->charset = $this->httpRequest->getParameter('charset', 'request', 'raw');
        $this->syspassword = trim($this->httpRequest->getParameter('syspassword', 'request', 'raw'));
        $this->dbtablesprefix = trim($this->httpRequest->getParameter('dbtablesprefix', 'request', 'raw'));
        $this->dbhost = trim($this->httpRequest->getParameter('dbhost', 'request', 'raw'));
        $this->dbport = trim($this->httpRequest->getParameter('dbport', 'request', 'digits'));
        $this->dbuser = trim($this->httpRequest->getParameter('dbuser', 'request', 'raw'));
        $this->dbname = trim($this->httpRequest->getParameter('dbname', 'request', 'raw'));
        $this->dbpasswd = trim($this->httpRequest->getParameter('dbpasswd', 'request', 'raw'));
                
        if(empty($this->dbhost))
        {
            $this->viewVar['error'][] = 'Database Host field is empty';
        }
        if(empty($this->dbuser))
        {
            $this->viewVar['error'][] = 'Database User field is empty';
        }  
        if(empty($this->dbname))
        {
            $this->viewVar['error'][] = 'Database Name field is empty';
        }  
        elseif(preg_match("/[^a-zA-Z_0-9-]/",$this->dbname))
        {
            $this->viewVar['error'][] = 'Only a-z A-Z _ 0-9 - chars for database name are accepted';
        }    
        if(preg_match("/[^a-zA-Z_0-9-]/",$this->dbtablesprefix))
        {
            $this->viewVar['error'][] = 'Only a-z A-Z _ 0-9 - chars for database name prefix are accepted';
        }         
        
        if(empty($this->syspassword))
        {
            $this->viewVar['error'][] = 'Sysadmin password field should not be empty!';
        } 
        if(preg_match("/[^a-zA-Z0-9-_]/",$this->syspassword))
        {
            $this->viewVar['error'][] = 'Only a-z A-Z 0-9 - _ chars for superuser password are accepted';
        }        
        
        if(count($this->viewVar['error']) > 0)
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * Rollback setup of each module
     *
     */    
    private function rollback()
    {
        $data = array('superuser_passwd' => JapaCommonUtil::stripSlashes($this->syspassword),
                      'dbtablesprefix'   => JapaCommonUtil::stripSlashes($this->dbtablesprefix),
                      'dbhost'           => JapaCommonUtil::stripSlashes($this->dbhost),
                      'dbport'           => JapaCommonUtil::stripSlashes($this->dbport),
                      'dbuser'           => JapaCommonUtil::stripSlashes($this->dbuser),
                      'dbpasswd'         => JapaCommonUtil::stripSlashes($this->dbpasswd),
                      'dbname'           => JapaCommonUtil::stripSlashes($this->dbname),
                      'charset'          => JapaCommonUtil::stripSlashes($this->charset),
                      'config'           => & $this->controllerVar['setup_config'],
                      'rollback'         => TRUE);            
    
        $this->model->broadcast( 'setup', $data );    
    }
}

?>