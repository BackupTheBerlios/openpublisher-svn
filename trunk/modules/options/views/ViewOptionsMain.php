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
 * ViewOptionsMain
 *
 */
 
class ViewOptionsMain extends JapaControllerAbstractPage
{
     /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'main';
    
     /**
     * Default template folder for this view
     * @var string $templateFolder
     */    
    public  $templateFolder = 'modules/options/templates/';

    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // if no rights for the logged user, show error template
        // only administrators can change options
        if($this->viewVar['loggedUserRole'] > 20)
        {
            $this->template       = 'error';
            $this->templateFolder = 'modules/common/templates/';
            $this->tplVar['error'] = 'You dont have the rights to change global options!';
            $this->dontPerform = TRUE;
        }
    } 
    
    /**
     * Perform on the main view
     */
    public function perform()
    {
        if(isset($_POST['updateOptions']))
        {
            if(TRUE == $this->validatePostData())
            {
                $this->model->action( 'options','updateConfigOptions',
                                      array('fields' => &$this->fields)); 
                
                $this->model->action( 'options','deletePublicCache'); 
            }        
        }
        elseif(isset($_POST['deletePublicCache']))
        {
                $this->model->action( 'options','deletePublicCache');         
        }
        elseif(isset($_POST['optimize']))
        {
                $this->model->broadcast( 'optimizeDbTables');         
        }
        elseif(isset($_POST['unlockall']))
        {
                $this->model->broadcast( 'unlockAll');         
        }        
        $this->setTemplateVars();
    }  
    
    /**
     * Set template variables
     */
    private function setTemplateVars()
    {
        $this->tplVar['siteUrl']              = $this->config['site_url'];
        $this->tplVar['publicTplFolder']      = $this->config['templates_folder'];
        $this->tplVar['publicCssFolder']      = $this->config['css_folder'];
        $this->tplVar['publicViewFolder']     = $this->config['views_folder'];
        $this->tplVar['allPublicViewFolders'] = $this->getPublicFolders( 'views_' );
        $this->tplVar['allPublicTplFolders']  = $this->getPublicFolders( 'templates_' );
        $this->tplVar['allPublicCssFolders']  = $this->getPublicFolders( 'css_' );
        $this->tplVar['rejectedFiles']        = $this->config['rejected_files'];
        $this->tplVar['maxLockTime']          = $this->config['max_lock_time'];
        $this->tplVar['recyclerTime']         = $this->config['recycler_time'];
        $this->tplVar['sessionMaxlifetime']   = $this->config['session_maxlifetime'];
        $this->tplVar['textareaRows']         = $this->config['textarea_rows'];
        $this->tplVar['serverGMT']            = $this->config['server_gmt'];
        $this->tplVar['defaultGMT']           = $this->config['default_gmt'];

        $this->tplVar['disableCache']         = $this->config['disable_cache'];
        $this->tplVar['serverTimezone']       = (int)(date("Z") / 3600);
    } 
    
    /**
     * Get all public views/templates/css folders
     */
    private function getPublicFolders( $folder_prefix )
    {
        $folders = array();
        $main_dir = SMART_BASE_DIR ;
        
        if($folder_prefix == 'templates_')
        {
            $folders[] = '';
        }
          
        if ( (($handle = @opendir( $main_dir ))) != FALSE )
        {
            while ( (( $_dir = readdir( $handle ) )) != false )
            {
                if ( ( $_dir == "." ) || ( $_dir == ".." ) )
                {
                    continue;
                }
                $match_str = "/^{$folder_prefix}/";
                if(is_dir($_dir) && preg_match("$match_str", $_dir))
                {
                    $folders[] = $_dir . '/';
                }
            }
            @closedir( $handle );
        }
        else
        {
            trigger_error( "Can not open folder to read: ".$main_dir, E_USER_ERROR  );
        }
        
        sort( $folders );
        return $folders;
    } 
    
   /**
    * Validate form data
    *
    */    
    private function validatePostData()
    {
        $this->fields  = array();

        if(isset($_POST['site_url']))
        {
            $this->fields['site_url'] = (string)$_POST['site_url'];
            $this->config['site_url'] = (string)$_POST['site_url'];
        }  
        
        if(isset($_POST['templates_folder']))
        {
            $this->fields['templates_folder'] = (string)$_POST['templates_folder'];
            $this->config['templates_folder'] = (string)$_POST['templates_folder'];
        }  

        if(isset($_POST['css_folder']))
        {
            $this->fields['css_folder'] = (string)$_POST['css_folder'];
            $this->config['css_folder'] = (string)$_POST['css_folder'];
        }  

        if(isset($_POST['views_folder']) && !empty($_POST['views_folder']))
        {
            if(preg_match("/^views_[a-zA-Z0-9_-]+/",$_POST['views_folder']) )
            {
                $this->fields['views_folder'] = (string)$_POST['views_folder'];
                $this->config['views_folder'] = (string)$_POST['views_folder'];
            }
        }  
        
       if(isset($_POST['disable_cache']) && $_POST['disable_cache'] == '1')
       {
            $this->fields['disable_cache'] = 1;
            $this->config['disable_cache'] = 1;
       }
       else
       {
            $this->fields['disable_cache'] = 0;
            $this->config['disable_cache'] = 0;
       }

       if(isset($_POST['textarea_rows']) && (strlen($_POST['textarea_rows']) <= 2))
       {
            if(preg_match("/[0-9]{1,2}/", $_POST['textarea_rows']) )
            {
                $this->fields['textarea_rows'] = (string)$_POST['textarea_rows'];
                $this->config['textarea_rows'] = (string)$_POST['textarea_rows'];
            }
       }  

       if( isset($_POST['server_gmt']) )
       {
            if( ($_POST['server_gmt'] >= -12) &&  ($_POST['server_gmt'] <= 12) )
            {
                $this->fields['server_gmt'] = (int)$_POST['server_gmt'];
                $this->config['server_gmt'] = (int)$_POST['server_gmt'];
            }
       }  
       
       if( isset($_POST['default_gmt']) )
       {
            if( ($_POST['default_gmt'] >= -12) &&  ($_POST['default_gmt'] <= 12) )
            {
                $this->fields['default_gmt'] = (int)$_POST['default_gmt'];
                $this->config['default_gmt'] = (int)$_POST['default_gmt'];
            }
       }        

       if(isset($_POST['session_maxlifetime']) && (strlen($_POST['session_maxlifetime']) <= 11))
       {
            if(preg_match("/[0-9]{1,11}/", $_POST['session_maxlifetime']) )
            {
                $this->fields['session_maxlifetime'] = (int)$_POST['session_maxlifetime'];
                $this->config['session_maxlifetime'] = (int)$_POST['session_maxlifetime'];
            }
       } 

       if(isset($_POST['max_lock_time']) && (strlen($_POST['max_lock_time']) <= 11))
       {
            if(preg_match("/[0-9]{1,11}/", $_POST['max_lock_time']) )
            {
                $this->fields['max_lock_time'] = (int)$_POST['max_lock_time'];
                $this->config['max_lock_time'] = (int)$_POST['max_lock_time'];
            }
       }  
       
       if(isset($_POST['recycler_time']) && (strlen($_POST['recycler_time']) <= 11))
       {
            if(preg_match("/[0-9]{1,11}/", $_POST['recycler_time']) )
            {
                $this->fields['recycler_time'] = (int)$_POST['recycler_time'];
                $this->config['recycler_time'] = (int)$_POST['recycler_time'];
            }
       }  

       $this->fields['rejected_files'] = (string)$_POST['rejected_files'];
       $this->config['rejected_files'] = (string)$_POST['rejected_files'];
        
        return TRUE;
    }    
}

?>